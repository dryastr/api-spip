<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Models\Transaction\Attachment;
use App\Repositories\Eloquent\Referensi\KlpRepository;
use App\Repositories\Eloquent\Transaction\PenilaianDaftarUserRepository;
use App\Repositories\Eloquent\User\UserRepository;
// use App\Jobs\SendEmail;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PenilaianDaftarUserService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    protected PenilaianDaftarUserRepository $repository;
    protected UserRepository $repositoryUser;
    protected KlpRepository $repositoryKlp;

    /**
     * Constructor.
     *
     * @param PenilaianDaftarUserRepository $repository
     */
    public function __construct(PenilaianDaftarUserRepository $repository)
    {
        $this->repository = $repository;
        $this->repositoryUser = new UserRepository();
        $this->repositoryKlp = new KlpRepository();
    }

    /**
     * Get Paginate PenilaianDaftarUser
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginate($selects = '*', $request = null, $filter = [], $relations = null)
    {
        return $this->repository->getPaginate($selects, $request, $filter, $relations);
    }

    /**
     * Create PenilaianDaftarUser
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        }, config('database.deadlock_retry', 3));
    }

    /**
     * Find By Id
     *
     * @param int $id
     * @param string|array $selects
     * @param array|string|null $relations
     * @return mixed
     */
    public function findBy(int $id, $selects = '*', $relations = null)
    {
        return $this->repository->getById($id, $selects, $relations);
    }

    /**
     * Update PenilaianDaftarUser
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->repository->updateById($id, $data);
        }, config('database.deadlock_retry', 3));
    }

    /**
     * Delete PenilaianDaftarUser
     *
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id)
    {
        $query = $this->repository->find(
            id: $id,
            relations: ['attachment']
        );
        if(!$query){
            throw new ServiceErrorException('Data not found', 404);
        }
        file_helper()->delete($query['attachment']['path']);

        $ids = [
            'id' => $id,
            'idA' => $query['attachment']['id']
        ];

        return DB::transaction(function () use ($ids) {
            Attachment::destroy($ids['idA']);
            return $this->repository->deleteById($ids['id']);
        }, config('database.deadlock_retry', 3));
    }

    /**
     * Upload Surat Tugas
     *
     * @param array $data
     * @return mixed
     * @throws ServiceErrorException
     */
    public function saveAttachment(array $attachmentData)
    {
        return DB::table('trans_attachment')->insert($attachmentData);
    }

    public function uploadSuratTugas(array $data)
    {
        try {
            $userId = $data['id'];
            $parentTable = 'trans_penilaian_daftar_user';

            // Handle file upload
            /** @var UploadedFile $file */
            $file = $data['surat_tugas'];

            $fileContent = $file->getContent();
            $fileExtension = $file->getClientOriginalExtension();
            $date = now()->toDateString();

            // Upload file
            $uploadedFile = file_helper()->upload(
                fileContent: $fileContent,
                ext: $fileExtension,
                path: 'surat_tugas/' . $date
            );

            $attachmentData = [
                'parent_table' => $parentTable,
                'table_id' => $userId,
                'path' => $uploadedFile['filename'],
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'extension' => $fileExtension,
                'created_by' => auth()->id(),
                'created_at' => now(),
            ];

            // Save attachment in the database
            $this->saveAttachment($attachmentData);

            return true;
        } catch (\Exception $e) {
            // Handle exceptions or log errors
            return false;
        }
    }

    public function downloadSuratTugas($userId)
    {
        $attachmentData = $this->repository->getAttachmentData($userId); 

        if (!$attachmentData) {
            throw new ServiceErrorException('File not found', 404);
        }
        
        return [
            'filePath' => storage_path('app/' . $attachmentData['attachment']['path']),
            'attachmentData' => $attachmentData
        ];

    }

    /**
     * Autocomplete Nama
     *
     * @param array $data
     * @return Collection
     */
    public function autocompleteNama(array $data)
    {
        $search = $data['search'] ?? '';
        $userData = $this->repositoryUser->getWhere(wheres: [array('fullname', 'like', "%{$search}%")]);
        $datanya= [];
        foreach($userData as $user){
            $roles = $user->roles()->get();
            $klpIds = $roles->pluck('ref_klp_id')->unique()->toArray();
            $klp = $this->repositoryKlp->getListByIds(
                selects: [
                    'id',
                    'kode',
                    'nama',
                    'nama_pendek',
                ],
                ids: $klpIds
            )->setHidden(['logo_url']);
    
    
            $klpWithRoleId = $klp->map(function ($klp) use ($user) {
                $role = $user->roles->firstWhere('ref_klp_id', $klp['id']);
                $klp['role_id'] = $role ? $role['role_id'] : null;
                return $klp;
            });
    
            $user->default_klp = $klpWithRoleId->first();
            $datanya[] = $user->setHidden(['roles', 'password']);
        }
        return $datanya;
    }
}
