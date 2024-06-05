<?php

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\VisiMisi;
use App\Repositories\BaseRepository;

class VisiMisiRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param VisiMisi $model
     */
    public function __construct(VisiMisi $model = new VisiMisi())
    {
        parent::__construct($model);
    }
}
