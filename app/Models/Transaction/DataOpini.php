<?php

namespace App\Models\Transaction;

use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOpini extends Model 
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    protected $table = 'trans_data_opini';

    protected $fillable = [
        'ref_klp_id',
        'trans_penilaian_id',
        'tahun',
        'opini',
        'persentase_bmn',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
    }

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'trans_penilaian_id', 'id');
    }

    protected static function booted(): void 
    {
        static::creating(function($data){
            $auth = auth()->user();
            if ($auth) {
                $data->created_by = $auth->user_id;
                $data->created_by_name = $auth->fullname;
                $data->updated_by = $auth->user_id;
                $data->updated_by_name = $auth->fullname;
            }
        });

        static::updating(function ($data) {
            $auth = auth()->user();
            if ($auth) {
                $data->updated_by = $auth->user_id;
                $data->updated_by_name = $auth->fullname;
            }
        });
    }
}