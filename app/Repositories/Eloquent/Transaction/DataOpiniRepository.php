<?php 

namespace App\Repositories\Eloquent\Transaction;

use App\Models\Transaction\DataOpini;
use App\Repositories\BaseRepository;

class DataOpiniRepository extends BaseRepository
{
    public function __construct(?DataOpini $modal = null)
    {
        parent::__construct($model ?? new DataOpini());
    }    
}