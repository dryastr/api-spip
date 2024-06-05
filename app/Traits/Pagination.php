<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;

trait Pagination
{
    /**
     * Paginate
     *
     * @param $query
     *
     * @return paginate
     */
    public function paginate($query = null)
    {
        return $query->paginate($this->perPage($query))->withPath(config('app.url') . '/' . Request::path());
    }
}
