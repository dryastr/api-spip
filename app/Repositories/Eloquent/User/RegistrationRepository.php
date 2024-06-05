<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\Registration;
use App\Repositories\BaseRepository;

class RegistrationRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Registration $model
     */
    public function __construct(Registration $model = new Registration())
    {
        parent::__construct($model);
    }

    public function countByApprovalStatus($status)
    {
        return $this->model->where('status_approval', $status)->count();
    }

    public function getAttachmentData(int $userId)
    {
        return $this->model->with('attachment')->find($userId);
    }
}
