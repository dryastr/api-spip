<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Role $model
     */
    public function __construct(Role $model = new Role())
    {
        parent::__construct($model);
    }
}
