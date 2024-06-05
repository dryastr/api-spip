<?php 

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\PenilaianTemuan;
use App\Repositories\BaseRepository;

class PenilaianTemuanRepository extends BaseRepository
{
    public function __construct(?PenilaianTemuan $modal = null)
    {
        parent::__construct($model ?? new PenilaianTemuan());
    }    
}