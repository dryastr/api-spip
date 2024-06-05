<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\SasaranIndikator;
use App\Repositories\BaseRepository;

class SasaranIndikatorRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param SasaranIndikator $model
     */
    public function __construct(SasaranIndikator $model = new SasaranIndikator())
    {
        parent::__construct($model);
    }

    public function getList(string|array $selects = '*', array $wheres = [], $relations = null)
    {
        $query = $this->model->select($selects)->where($wheres);
        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }
}
