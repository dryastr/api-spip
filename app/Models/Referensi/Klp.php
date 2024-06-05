<?php

namespace App\Models\Referensi;

use App\Models\User\User;
use App\Models\User\UserHasRole;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klp extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_klps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ref_lokasi_id',
        'parent_id',
        'parent_root_id',
        'kode',
        'nama',
        'nama_pendek',
        'jenis',
        'level',
        'attrs',
        'is_active',
        'lokasi',
        'pimpinan',
        'jabatan_pimpinan',
        'logo',
        'no_telp',
        'website',
        'fax',
        'alamat',
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
        'logo_url',
        'jenis_description',
        'level_description',
        'jenis_level'
    ];

    /**
     * Get Image URL Attribute
     *
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        return \Storage::url($this->logo);
    }

    /**
     * Get Jenis Description Attribute
     *
     * @return ?string
     */
    public function getJenisDescriptionAttribute(): ?string
    {
        if ($this->jenis === 'KL') {
            return 'KEMENTRIAN LEMBAGA';
        }
        if ($this->jenis === 'PEMDA') {
            return 'PEMERINTAH DAERAH';
        }
        return $this->jenis;
    }

    /**
     * Get Level Description Attribute
     *
     * @return ?string
     */
    public function getLevelDescriptionAttribute(): ?string
    {
        if ($this->level === 'PUSAT') {
            return 'PUSAT';
        }
        if ($this->level === 'NON-PUSAT') {
            return 'NON PUSAT';
        }
        return $this->level;
    }

    public function getJenisLevelAttribute(): ?string
    {
        if ($this->jenis === 'PEMDA' && $this->level === 'NON-PUSAT') {
            return 'OPD';
        } elseif ($this->jenis === 'PEMDA' && $this->level === 'PUSAT') {
            return 'Pemda';
        } elseif ($this->jenis === 'KL' && $this->level === 'NON-PUSAT' && $this->ref_lokasi_id) {
            return 'Perwakilan BPKP Wilayah';
        } elseif ($this->jenis === 'KL' && $this->level === 'PUSAT') {
            return 'Kementrian';
        } elseif ($this->jenis === 'KL' && $this->level === 'NON-PUSAT') {
            return 'Satker';
        } else {
            return '-';
        }
    }

    /**
     * Get the lokasi.
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'ref_lokasi_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_roles', 'ref_klp_id', 'user_id')
            ->withPivot('role_id');
    }

    public function userHasRoles()
    {
        // return $this->hasMany(UserHasRole::class, 'ref_klp_id', 'ref_klp_id');
        return $this->hasManyThrough(UserHasRole::class, User::class, 'ref_klp_id', 'user_id', 'id', 'id');
    }

    public function usersWithRolesAndPermissions()
    {
        return $this->belongsToMany(User::class, 'user_has_roles', 'ref_klp_id', 'user_id')
            ->withPivot('role_id')
            ->with('roles.permissions');
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
