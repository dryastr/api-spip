<?php

namespace App\Models\Referensi;

use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KkLeadSpip extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_kk_lead_spip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'ref_jenis_kk_lead_spip_id',
        'kode',
        'nama',
        'bobot',
        'jenis_klp',
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

    /**
     * Get the childrens.
     */
    public function childrens()
    {
        return $this->hasMany(KkLeadSpip::class, 'parent_id', 'id');
    }

    /**
     * Get the jenis kk lead spip.
     */
    public function jenis_kk_lead_spip()
    {
        return $this->belongsTo(JenisLeadSpip::class, 'ref_jenis_kk_lead_spip_id', 'id');
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = ! $value || $value === '' ? null : $value;
    }

    public function setRefJenisKkLeadSpipIdAttribute($value)
    {
        $this->attributes['ref_jenis_kk_lead_spip_id'] = ! $value || $value === '' ? null : $value;
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
