<?php

namespace App\Repositories\Eloquent\Referensi;

use App\Models\Referensi\Klp;
use App\Repositories\BaseRepository;

class KlpRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Klp $model
     */
    public function __construct(Klp $model = new Klp())
    {
        parent::__construct($model);
    }

    /**
     * Get List KLP
     *
     * @return mixed
     */
    public function getList(string|array $selects = '*', array $wheres = [], $relations = null, string $search = ''): mixed
    {
        $query = $this->model->select($selects)->where($wheres);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nama_pendek', 'like', "%{$search}%");
            });
        }

        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get List KLP By Ids
     *
     * @return mixed
     */
    public function getListByIds($selects = '*', $ids = [])
    {
        return $this->model->select($selects)->whereIn('id', $ids)->get();
    }

    public function getAllWithUsers($klpId)
    {
        return $this->model->find($klpId);
    }
}
