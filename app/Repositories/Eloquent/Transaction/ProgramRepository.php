<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\Program;
use App\Repositories\BaseRepository;

class ProgramRepository extends BaseRepository
{
    /**
     * Constructor.
     *
     * @param Program $model
     */
    public function __construct(Program $model = new Program())
    {
        parent::__construct($model);
    }

    /**
     * Get a list of programs.
     *
     * @param string|array $selects
     * @param array        $wheres
     * @param null         $relations
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
        return $this->model->where('penilaian_id', $penilaianId)->get();
    }
}
