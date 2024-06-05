<?php

namespace App\Repositories\Eloquent\Referensi;

use App\Models\Referensi\KkLeadSpip;
use App\Repositories\BaseRepository;

class KkLeadSpipRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param KkLeadSpip $model
     */
    public function __construct(KkLeadSpip $model = new KkLeadSpip())
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
