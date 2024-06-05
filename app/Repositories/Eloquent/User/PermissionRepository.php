<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\Permission;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Permission $model
     */
    public function __construct(Permission $model = new Permission())
    {
        parent::__construct($model);
    }

    /**
     * Get Permission By Role Ids
     *
     * @return mixed
     */
    public function getByPermissionIds($selects = '*', $permissionIds = [])
    {
        return $this->model->select($selects)->whereIn('id', $permissionIds)->get();
    }
}
