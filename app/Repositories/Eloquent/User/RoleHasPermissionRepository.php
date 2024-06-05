<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\RoleHasPermission;
use App\Repositories\BaseRepository;

class RoleHasPermissionRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param RoleHasPermission $model
     */
    public function __construct(RoleHasPermission $model = new RoleHasPermission())
    {
        parent::__construct($model);
    }

    /**
     * Get Permission By Role Ids
     *
     * @return mixed
     */
    public function getByRoleIds($selects = '*', $roleIds = [])
    {
        return $this->model->select($selects)->whereIn('role_id', $roleIds)->get();
    }
}
