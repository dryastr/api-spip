<?php

namespace App\Models\Transaction;

use App\Models\Referensi\JenisSasaran;
use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sasaran extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trans_sasaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'ref_klp_id',
        'ref_jenis_sasaran_id',
        'trans_penilaian_id',
        'kode',
        'nama',
        'nsa_orientasi_hasil',
        'nsa_relevan_mandat_sa',
        'nsa_uji_kecukupan_indikator_sa',
        'catatan',
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
        'nsa_orientasi_hasil' => 'boolean',
        'nsa_relevan_mandat_sa' => 'boolean',
        'nsa_uji_kecukupan_indikator_sa' => 'boolean',
    ];

    /**
     * Get the lokasi.
     */
    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
    }

    /**
     * Get the childrens.
     */
    public function childrens()
    {
        return $this->hasMany(Sasaran::class, 'parent_id', 'id');
        // return $this->belongsToMany(Sasaran::class);
    }

    /**
     * Get the penilaian.
     */
    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'trans_penilaian_id', 'id');
    }

    public function jenis_sasaran()
    {
        return $this->hasOne(JenisSasaran::class, 'id', 'ref_jenis_sasaran_id',);
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
                $data->ref_klp_id = $auth->user_id;
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
