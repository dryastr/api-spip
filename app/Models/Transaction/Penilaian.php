<?php

namespace App\Models\Transaction;

use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trans_penilaian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_klp_id',
        'tahun',
        'anggaran',
        'status',
        'asal_data',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'anggaran_rupiah',
        'status_description',
    ];

    /**
     * Get Status Description Attribute
     *
     * @return ?string
     */
    public function getStatusDescriptionAttribute(): ?string
    {
        if ($this->status === 'draft') {
            return 'Draft';
        }
        if ($this->status === 'preparation') {
            return 'Preparation';
        }
        if ($this->status === 'open-self-assessment') {
            return 'Open Self Assessment';
        }
        if ($this->status === 'open-verification-assessment') {
            return 'Open Verification Assessment';
        }
        if ($this->status === 'final') {
            return 'Final';
        }
        return $this->status;
    }

    /**
     * Get Anggaran Rupliah Attribute
     *
     * @return ?string
     */
    public function getAnggaranRupiahAttribute(): ?string
    {
        return 'Rp. ' . number_format($this->anggaran, 2);
    }

    /**
     * Get the lokasi.
     */
    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
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
                // $data->ref_klp_id = $auth->user_id;
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
