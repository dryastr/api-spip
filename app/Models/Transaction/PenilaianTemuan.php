<?php

namespace App\Models\Transaction;

use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianTemuan extends Model 
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    protected $table = 'trans_penilaian_temuan';

    protected $fillable = [
        'ref_klp_id',
        'trans_penilaian_id',
        'jenis',
        'temuan',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    protected $appends = [
        'jenis_description',
    ];

    public function getJenisDescriptionAttribute(): ?string
    {
        if($this->jenis === 'bpk-atas-keandalan-lk'){
            return 'Temuan BPK atas Keandalan LK';
        }

        if($this->jenis === 'administrasi-aset'){
            return 'Temuan Administrasi Aset';
        }

        if($this->jenis === 'kepemilikan-aset'){
            return 'Temuan Kepemilikan Aset';
        }

        if($this->jenis === 'bpk-atas-ketidakpatuhan'){
            return 'Temuan BPK atas Ketidakpatuhan';
        }

        if($this->jenis === 'keterjadian-tipikor'){
            return 'Keterjadian Tipikor';
        }

        return $this->jenis;
    }

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
        static::creating(function ($data) {
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