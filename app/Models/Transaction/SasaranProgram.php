<?php

namespace App\Models\Transaction;

use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SasaranProgram extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trans_sasaran_program';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_klp_id',
        'trans_penilaian_id',
        'trans_program_id',
        'trans_sasaran_id',
        'trans_sasaran_indikator_id',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    /**
     * Get the klp.
     */
    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
    }

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'trans_penilaian_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'trans_program_id', 'id');
    }

    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class, 'trans_sasaran_id', 'id');
    }

    public function sasaran_indikator()
    {
        return $this->belongsTo(SasaranIndikator::class, 'trans_sasaran_indikator_id', 'id');
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
