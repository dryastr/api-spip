<?php

namespace App\Models\Transaction;

use App\Models\Referensi\Klp;
use App\Models\Transaksi\Penilaian;
use App\Models\User\User;
use App\Models\User\UserHasRole;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDaftarUser extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trans_penilaian_daftar_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ref_klp_id',
        'trans_penilaian_id',
        'no_surat',
        'tgl_surat',
        'tgl_mulai',
        'tgl_selesai',
        'created_by',
        'created_by_name',
        'updated_by',
        'updated_by_name',
    ];

    /**
     * Get the user that owns the PenilaianDaftarUser.
     */
    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the klp.
     */
    public function klp()
    {
        return $this->belongsTo(Klp::class, 'ref_klp_id', 'id');
    }

    /**
     * Get the penilaian that owns the PenilaianDaftarUser.
     */
    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'trans_penilaian_id', 'id');
    }

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'table_id','id')->where('parent_table', 'trans_penilaian_daftar_user');
    }

    /**
     * Get the users for the PenilaianDaftarUser.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_roles', 'ref_klp_id', 'user_id')
            ->withPivot('role_id');
    }

    public function hasRole()
    {
        return $this->belongsToMany(UserHasRole::class, 'user_id', 'id');
    }

    /**
     * Get the userHasRoles for the PenilaianDaftarUser.
     */
    public function userHasRoles()
    {
        return $this->hasMany(UserHasRole::class,'user_has_roles', 'ref_klp_id', 'user_id');
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
