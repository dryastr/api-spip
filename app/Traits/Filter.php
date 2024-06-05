<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Schema;

trait Filter
{
    /**
     * Example filter
     *
     * [
     *    [
     *        'column' => 'name',
     *        'type' => '=',
     *        'value' => 'Fajar',
     *    ],
     *    [
     *        'column' => 'id',
     *        'type' => '=',
     *        'value' => [1,2],
     *    ],
     * ]
     */

    /**
     * Filter columns value by parameters given.
     *
     * @param Builder $query
     * @param $filters
     * @param string $joinType
     *
     * @return Builder
     *
     * @throws Exception
     */
    public function scopeFilter(Builder $query, $filters, string $joinType = 'left'): Builder
    {
        if (! empty($filters)) {
            $model = $query->getModel();
            $getColumns = Schema::connection($model->getConnectionName())->getColumnListing($model->getTable());
            $masterTable = $model->getTable();

            foreach ($filters as $filter) {
                if (isset($filter['column']) && isset($filter['type']) && isset($filter['value'])) {
                    if (query_helper()->hasRelation($filter['column'])) {
                        $column = query_helper()->parseField($filter['column']);
                        $relationNames = query_helper()->parseRelation($filter['column']);
                        $lastModel = $model;

                        foreach ($relationNames as $relationName) {
                            $relation = $lastModel->{$relationName}();
                            switch (true) {
                                case $relation instanceof BelongsTo:
                                    $table = $relation->getRelated()->getTable();
                                    $foreign = $relation->getQualifiedForeignKeyName();
                                    $other = $relation->getQualifiedOwnerKeyName();
                                    break;

                                case $relation instanceof BelongsToMany:
                                    $pivot = $relation->getTable();
                                    $pivotPK = $relation->getExistenceCompareKey();
                                    $pivotFK = $relation->getQualifiedParentKeyName();
                                    $query = query_helper()->performJoin($query, $pivot, $pivotPK, $pivotFK, $joinType);

                                    $related = $relation->getRelated();
                                    $table = $related->getTable();
                                    $tablePK = $relation->getRelatedPivotKeyName();
                                    $foreign = $pivot.'.'.$tablePK;
                                    $other = $related->getQualifiedKeyName();

                                    $query = query_helper()->performSelect($query, $table, $column);
                                    $query = query_helper()->performJoin($query, $table, $foreign, $other, $joinType);

                                    break;

                                case $relation instanceof HasOneOrMany:
                                    $table = $relation->getRelated()->getTable();
                                    $foreign = $relation->getQualifiedForeignKeyName();
                                    $other = $relation->getQualifiedParentKeyName();
                                    break;

                                case $relation instanceof HasOneThrough:
                                    $pivot = explode(
                                        '.',
                                        $relation->getQualifiedParentKeyName()
                                    )[0]; // extract pivot table from key
                                    $pivotPK = $pivot . '.' . $relation->getFirstKeyName();
                                    $pivotFK = $relation->getQualifiedLocalKeyName();
                                    $query = query_helper()->performJoin($query, $pivot, $pivotPK, $pivotFK, $joinType);

                                    $related = $relation->getRelated();
                                    $table = $related->getTable();
                                    $tablePK = $relation->getSecondLocalKeyName();
                                    $foreign = $pivot . '.' . $tablePK;
                                    $other = $table . '.' . $relation->getLocalKeyName();

                                    $query = query_helper()->performSelect($query, $table, '*');

                                    break;

                                default:
                                    throw new Exception('Relation ' . $relation::class . ' is not yet supported.');
                            }

                            if (! query_helper()->joined($query, $table)) {
                                $query->join(
                                    $table,
                                    $foreign,
                                    '=',
                                    $other,
                                    $joinType
                                );
                            }

                            $lastModel = $relation->getModel();
                        }

                        $getRelationColumns = Schema::connection($lastModel->getConnectionName())->getColumnListing(
                            $lastModel->getTable()
                        );

                        if (in_array($column, $getRelationColumns)) {
                            $query = $this->filterByType($query, $column, $filter['type'], $filter['value'], $table);
                        }
                    } else {
                        if (in_array($filter['column'], $getColumns)) {
                            $query = $this->filterByType($query, $filter['column'], $filter['type'], $filter['value'], $masterTable);
                        }
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Filtering by type given.
     *
     * @param $query
     * @param $column
     * @param $type
     * @param $value
     * @param $table
     *
     * @return mixed
     *
     * @throws Exception
     */
    private function filterByType($query, $column, $type, $value, $table): mixed
    {
        if (isset($value) && is_array($value)) {
            if (! empty($value) && ! is_array($value[0])) {
                $query = match ($type) {
                    '=' => $query->whereIn($table . '.' . $column, $value),
                    '!=' => $query->whereNotIn($table . '.' . $column, $value),
                    'between' => $query->whereBetween($table . '.' . $column, $value),
                    default => throw new Exception('Type ' . $type . ' is not yet supported.')
                };
            }
        } else {
            $query = match ($type) {
                '=', '<', '!=', '>=', '<=', '>', '<>' => $query->where($table.'.'.$column, $type, $value),
                'like' => $query->where(
                    $table.'.'.$column,
                    query_helper()->like('pgsql'),
                    '%' . $value . '%'
                ),
                'is-null' => $query->whereNull($table.'.'.$column),
                'is-not-null' => $query->whereNotNull($table.'.'.$column),
                'date' => $query->whereDate($table.'.'.$column, $value),
                'time' => $query->whereTime($table.'.'.$column, $value),
                'day' => $query->whereDay($table.'.'.$column, $value),
                'month' => $query->whereMonth($table.'.'.$column, $value),
                'year' => $query->whereYear($table.'.'.$column, $value),
                default => throw new Exception('Type ' . $type . ' is not yet supported.')
            };
        }

        return $query;
    }
}
