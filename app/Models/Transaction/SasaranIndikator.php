<?php

namespace App\Models\Transaction;

use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SasaranIndikator extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trans_sasaran_indikator';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_klp_id',
        'trans_sasaran_id',
        'kode',
        'nama',
        'target_kinerja',
        'satuan',
        'nsa_indikator_kinerja_tepat',
        'nsa_target_kinerja_tepat',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nsa_indikator_kinerja_tepat' => 'boolean',
        'nsa_target_kinerja_tepat' => 'boolean',
    ];

    /**
     * Get the lokasi.
     */
    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
    }

    /**
     * Get the lokasi.
     */
    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class, 'trans_sasaran_id', 'id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
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
