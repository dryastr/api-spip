<?php

namespace App\Repositories\Eloquent\Referensi;

use App\Models\Referensi\KategoriRisiko;
use App\Repositories\BaseRepository;

class KategoriRisikoRepository extends BaseRepository
{
    /**
     * Constructor.
     *
     * @param KategoriRisiko $model
     */
    public function __construct(KategoriRisiko $model = null)
    {
        parent::__construct($model ?? new KategoriRisiko());
    }

    /**
     * Get List KategoriRisiko
     *
     * @param string|array $selects
     * @param array $wheres
     * @param string|null $search
     * @param array|null $relations
     * @return mixed
     */
    public function getList(string|array $selects = '*', array $wheres = [], string $search = '', array $relations = null): mixed
    {
        $query = $this->model->select($selects)->where($wheres);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }

    public function getProgramsByKlp(int $klpId)
    {
        return $this->model->where('ref_klp_id', $klpId)->get();
    }
}
