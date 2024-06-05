<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\SasaranProgram;
use App\Repositories\BaseRepository;

class SasaranProgramRepository extends BaseRepository
{
    /**
     * Constructor.
     *
     * @param SasaranProgram $model
     */
    public function __construct(?SasaranProgram $model = null)
    {
        parent::__construct($model ?? new SasaranProgram());
    }

    /**
     * Get a list of records based on the specified criteria.
     *
     * @param string|array $selects
     * @param array $wheres
     * @param mixed $relations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList(string|array $selects = '*', array $wheres = [], $relations = null)
    {
        $query = $this->model->select($selects)->where($wheres);
        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }

    public function getProgramsByPenilaian(int $penilaianId)
    {
        return $this->model->where('trans_penilaian_id', $penilaianId)->get();
    }
}
