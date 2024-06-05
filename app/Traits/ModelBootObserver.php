<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

trait ModelBootObserver
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function userObserverBoot()
    {
        static::creating(function (Model $model) {
            $columns = Schema::connection($model->getConnectionName())->getColumnListing($model->getTable());
            $auth = auth()->user();
            if ($auth && in_array('created_by', $columns)) {
                $model->created_by = $auth ? $auth->user_id : null;
            }
            if ($auth && in_array('created_by_name', $columns)) {
                $model->created_by_name = $auth ? $auth->full_name : null;
            }
        });

        static::updating(function (Model $model) {
            $columns = Schema::connection($model->getConnectionName())->getColumnListing($model->getTable());
            $auth = auth()->user();
            if ($auth && in_array('updated_by', $columns)) {
                $model->updated_by = $auth ? $auth->user_id : null;
            }
            if ($auth && in_array('updated_by_name', $columns)) {
                $model->updated_by_name = $auth ? $auth->full_name : null;
            }
        });
    }
}
