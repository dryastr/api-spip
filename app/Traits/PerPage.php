<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait PerPage
{
    /**
     * Per page
     *
     * @param $query
     *
     * @return int
     */
    public function perPage($query = null)
    {
        if ($query instanceof Builder || $query instanceof Collection) {
            $total = $query->count();
        } else {
            $total = 15;
        }

        return request()->get('page') === 'all' ? $total : (request()->filled('per_page') ? request()->get('per_page') : 15);
    }
}
