<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\Menu;
use App\Repositories\BaseRepository;

class MenuRepository extends BaseRepository
{
    /**
     * constructor.
     *
     * @param Menu $model
     */
    public function __construct(Menu $model = new Menu())
    {
        parent::__construct($model);
    }
}
