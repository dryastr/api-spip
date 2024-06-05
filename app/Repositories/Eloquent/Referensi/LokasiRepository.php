<?php

namespace App\Repositories\Eloquent\Referensi;

use App\Models\Referensi\Lokasi;
use App\Repositories\BaseRepository;

class LokasiRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Lokasi $model
     */
    public function __construct(Lokasi $model = new Lokasi())
    {
        parent::__construct($model);
    }

    /**
     * Get List Lokasi
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
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }
}
