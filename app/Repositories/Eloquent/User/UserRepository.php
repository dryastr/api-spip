<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param User $model
     */
    public function __construct(User $model = new User())
    {
        parent::__construct($model);
    }

    public function countByActiveStatus($status)
    {
        return $this->model->where('status', $status)->count();
    }
}
