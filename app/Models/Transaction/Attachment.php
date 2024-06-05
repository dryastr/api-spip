<?php

namespace App\Models\Transaction;

use App\Models\User\Registration;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use Pest\Arch\Objects\FunctionDescription;

class Attachment extends Model
{
    protected $table = 'trans_attachment';

    protected $fillable = [
        'parent_table',
        'table_id',
        'path',
        'name',
        'size',
        'extension',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration(){
        return $this->belongsTo(Registration::class);
    }
}
