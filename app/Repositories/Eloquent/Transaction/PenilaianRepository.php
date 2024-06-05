<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\Penilaian;
use App\Repositories\BaseRepository;

class PenilaianRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Penilaian $model
     */
    public function __construct(Penilaian $model = new Penilaian())
    {
        parent::__construct($model);
    }
}
