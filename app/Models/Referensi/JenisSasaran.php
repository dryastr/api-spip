<?php

namespace App\Models\Referensi;

use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSasaran extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_jenis_sasaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_klp_id',
        'kode',
        'nama',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    protected $appends = [
        'pid',
    ];

    public function getPidAttribute()
    {
        return security()->encrypt($this->id);
    }

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
                $data->created_by = $auth->user_id;
                $data->created_by_name = $auth->fullname;
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
