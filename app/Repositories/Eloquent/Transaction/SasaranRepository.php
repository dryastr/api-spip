<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\Sasaran;
use App\Repositories\BaseRepository;

class SasaranRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Sasaran $model
     */
    public function __construct(Sasaran $model = new Sasaran())
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
