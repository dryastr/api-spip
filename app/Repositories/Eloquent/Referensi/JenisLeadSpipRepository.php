<?php

namespace App\Repositories\Eloquent\Referensi;

use App\Models\Referensi\JenisLeadSpip;
use App\Repositories\BaseRepository;

class JenisLeadSpipRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param  $model
     */
    public function __construct(JenisLeadSpip $model = new JenisLeadSpip())
    {
        parent::__construct($model);
    }

    /**
     * Get List Jenis Lead SPIP
     *
     * @return mixed
     */
    public function getList(string|array $selects = '*', array $wheres = [], $relations = null, string $search = ''): mixed
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
}
