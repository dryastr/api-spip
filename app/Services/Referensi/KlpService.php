<?php

namespace App\Services\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Referensi\KlpRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class KlpService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param KlpRepository $repository
     */
    public function __construct(
        protected KlpRepository $repository = new KlpRepository(),
    ) {
    }

    /**
     * Get Paginate Klp
     *
     * @return Paginate
     */
    public function getPaginate($selects = '*', $request = null, $filter = [])
    {
        return $this->repository->getPaginate($selects, $request, $filter);
    }

    /**
     * List KLP
     *
     * @param string $search
     * @param array $wheres
     *
     * @return Collection
     */
    public function list(?string $search = null, array $wheres = [])
    {
        return $this->repository->getList(
            search: $search ?? '',
            wheres: $wheres
        );
    }

    /**
     * Create
     *
     * @return Model
     */
    public function create($data)
    {
        if (isset($data['logo'])) {
            $image = image_helper()->upload(
                file: $data['logo'],
                path: 'klp/logo',
            );
            $data['logo'] = $image;
        }

        $result = DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        }, config('database.deadlock_retry', 3));

        if (! $result && isset($data['logo'])) {
            image_helper()->delete($data['logo']);
        }

        return $result;
    }

    /**
     * Find By
     *
     * @return Collection
     */
    public function findBy($id, $selects = '*', $relations = null)
    {
        return $this->repository->find($id, $selects, $relations);
    }

    /**
     * Update
     *
     * @return Model
     */
    public function update($id, $data)
    {
        $query = $this->repository->find($id);

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        $lastLogo = $query['logo'];
        if (isset($data['logo'])) {
            $image = image_helper()->upload(
                file: $data['logo'],
                path: 'klp/logo',
            );
            $data['logo'] = $image;
        }

        $result = DB::transaction(function () use ($data, $query) {
            return $query->update($data);
        }, config('database.deadlock_retry', 3));

        if (! $result && isset($data['logo'])) {
            image_helper()->delete($data['logo']);
        } elseif ($result && $lastLogo) {
            image_helper()->delete($lastLogo);
        }

        return $query;
    }

    /**
     * Update
     *
     * @return Model
     */
    public function delete($id)
    {
        $query = $this->repository->find($id);

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        return DB::transaction(function () use ($query) {
            return $query->delete();
        }, config('database.deadlock_retry', 3));
    }
    public function getList(array $where = [])
    {
        return $this->repository->getList(
            wheres: $where
        );
    }

    public function getAllWithUsers($klpId)
    {
        $klp = $this->repository->getAllWithUsers($klpId);

        if (! $klp) {
            throw new ServiceErrorException('Data not found', 422);
        }

        // \Log::info($klp);
        $users = $klp->users->groupBy('user_id')->values();
        $formattedData = [
            'id' => $klp->id,
            'parent_id' => $klp->parent_id,
            'parent_root_id' => $klp->parent_root_id,
            'kode' => $klp->kode,
            'nama' => $klp->nama,
            'nama_pendek' => $klp->nama_pendek,
            'jenis' => $klp->jenis,
            'level' => $klp->level,
            'attrs' => $klp->attrs,
            'is_active' => $klp->is_active,
            'lokasi' => $klp->lokasi,
            'pimpinan' => $klp->pimpinan,
            'jabatan_pimpinan' => $klp->jabatan_pimpinan,
            'logo' => $klp->logo,
            'no_telp' => $klp->no_telp,
            'website' => $klp->website,
            'fax' => $klp->fax,
            'alamat' => $klp->alamat,
            'created_by' => $klp->created_by,
            'created_by_name' => $klp->created_by_name,
            'updated_by' => $klp->updated_by,
            'updated_by_name' => $klp->updated_by_name,
            'logo_url' => $klp->logo_url,
            'users' => $users->map(function ($userGroup) {
                $user = $userGroup->first();
                return [
                    'id' => $user->id,
                    'nip' => $user->nip,
                    'email' => $user->email,
                    'fullname' => $user->fullname,
                    'mobile' => $user->mobile,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'status' => $user->status,
                    'updated_at' => $user->updated_at,
                    'roles' => $user->userHasRoles->map(function ($userHasRole) {
                        return [
                            'role_id' => $userHasRole->role_id,
                            // Anda juga dapat mencoba mendapatkan nama role dari model Role di sini
                            'name' => $userHasRole->role->name,
                        ];
                    }),
                ];
            }),

        ];

        \Log::info($formattedData);
        return $formattedData;
    }
}
