<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\PenilaianDaftarUser;
use App\Models\User\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PenilaianDaftarUserRepository extends BaseRepository
{
    /**
     * Constructor.
     *
     * @param PenilaianDaftarUser $model
     */
    public $modelUser;

    public function __construct(PenilaianDaftarUser $model = null)
    {
        parent::__construct($model ?? new PenilaianDaftarUser());
        $this->modelUser = new User();
    }

    public function deleteById(int $id): ?bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Get List PenilaianDaftarUser
     *
     * @return mixed
     */
    public function getList(string|array $selects = '*', array $wheres = [], $relations = null, string $search = '')
    {
        $query = $this->model->select($selects)->where($wheres);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('fullname', 'like', "%{$search}%");
            });
        }

        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get PenilaianDaftarUser By Id
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id, $relations = null)
    {
        $query = $this->model->where('id', $id);

        if ($relations) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Autocomplete Nama
     *
     * @param string $search
     * @return Collection
     */
    public function autocompleteNama(string $search = '', int $roleId)
    {
        if($search){
            $query = $this->modelUser
            ->where('fullname', 'like', "%{$search}%")
            ->get(['id', 'fullname']);
            return $query;
        }else{
            return [];
        }
    }

    /**
     * Get attachment data by user ID.
     *
     * @param int $userId
     * @return mixed
     */
    public function getAttachmentData(int $userId)
    {
        return $this->model->with('attachment')->find($userId);
    }
}
