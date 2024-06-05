<?php

namespace App\Models\User;

use App\Traits\ColumnSorter;
use App\Traits\ColumnFilterer;
use App\Models\Referensi\Klp;
use App\Models\Transaction\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class Registration extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jenis',
        'ref_klp_id',
        'ref_klp_nama',
        'no_telp',
        'website',
        'fax',
        'alamat',
        'file_surat_permohonan',
        'status_approval',
        'approval_at',
        'approval_by',
        'approval_by_name',
        'ref_lokasi_id',
        'ref_lokasi_kabkot_id',
        'nama_dinas',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
    }

    // public function attachment()
    // {
    //     return $this->hasOne(Attachment::class, 'table_id', 'id')->where('parent_table', 'registrations');
    // }

    public function attachment()
    {
        return $this->hasMany(Attachment::class, 'parent_id', 'id')->where('parent_table', 'registrations');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('withUser', function ($query) {
            $query->with('user');
        });
    }
}
