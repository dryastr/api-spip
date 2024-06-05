<?php

namespace App\Models\Referensi;

use App\Models\Referensi\Klp;
use App\Traits\ColumnFilterer;
use App\Traits\ColumnSorter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriRisiko extends Model
{
    use HasFactory;
    use ColumnSorter;
    use ColumnFilterer;

    protected $table = 'ref_kategori_risiko';

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
     * Mendapatkan aturan validasi yang berlaku untuk model.
     *
     * @param  int|null  $id
     *
     * @return array
     */
    public static function rules($id = null): array
    {
        return [
            'ref_klp_id' => ['nullable', 'integer', 'exists:ref_klps,id'],
            'kode' => 'required|string|max:50|unique:ref_kategori_risiko,kode,' . $id,
            'nama' => ['nullable', 'string', 'max:255'],
        ];
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
