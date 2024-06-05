<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'title',
        'icon',
        'url',
        'type',
        'order_menu',
        'is_section',
        'status',
    ];

    /**
     * Get the permissions for the menu.
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
