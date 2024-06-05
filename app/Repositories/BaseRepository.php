<?php

namespace App\Repositories;

use App\Traits\Pagination;
use App\Traits\PerPage;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    use PerPage;
    use Pagination;

    /**
     * @var mixed
     */
    protected $model = Model::class;

    /**
     * BaseRepository constructor.
     *
     * @param  mixed  $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function insertData(array $attributes): mixed
    {
        return $this->model->insert($attributes);
    }

    /**
     * @param $id
     * @param array $attributes
     *
     * @return Model
     */
    public function update($id, array $attributes)
    {
        $model = $this->model->findOrFail($id);
        $update = $model->update($attributes);
        if ($model) {
            return $model;
        }

        return $update;
    }

    /**
     * Update by
     *
     * @param array $key
     * @param array $attributes
     *
     * @return Model|int
     */
    public function updateBy(array $key, array $attributes): Model|int
    {
        $model = $this->model->where($key)->first();
        if ($model) {
            $model->update($attributes);
        }

        return $model;
    }

    /**
     * @param $id
     * @param string $selects
     * @param null $relations
     *
     * @return mixed
     */
    public function find($id, $selects = '*', $relations = null)
    {
        if ($relations) {
            return $this->model->select($selects)->where('id', $id)->with($relations)->first();
        }

        return $this->model->select($selects)->where('id', $id)->first($id);
    }

    /**
     * Find by key
     *
     * @param array $key
     * @param mixed $selects
     * @param mixed $relations
     * @param array $filter
     * @param string $sort
     *
     * @return model
     */
    public function findBy($key, $selects = '*', $relations = null, $filter = [], $sort = 'asc')
    {
        $data = $this->model->select($selects);

        if ($relations) {
            $data = $data->with($relations);
        }

        if ($filter) {
            $data = $data->filter($filter);
        }

        return $data->where($key)->orderBy('id', $sort)->first();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id): mixed
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Delete By Ids
     *
     * @param array $ids
     *
     * @return Model
     */
    public function deletes($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Delete By
     *
     * @param array $data
     *
     * @return Model
     */
    public function deleteBy($data): Model
    {
        return $this->model->where($data)->delete();
    }

    /**
     * Delete By
     *
     * @param $field
     * @param $ids
     *
     * @return Model
     */
    public function deleteBys($field, $ids): Model
    {
        return $this->model->whereIn($field, $ids)->delete();
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function getWhere(string|array $selects = '*', array $wheres = [], $relations = null): mixed
    {
        $query = $this->model->select($selects)->where($wheres);

        if ($relations) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function updateOrCreate(array $key, array $attributes)
    {
        return $this->model->updateOrCreate($key, $attributes);
    }

    /**
     * Get All
     *
     * @return mixed
     */
    public function getAll($selects = '*', $wheres = [])
    {
        return $this->model->select($selects)->where($wheres)->get();
    }

    /**
     * Get Paginate
     *
     * @return Collection
     */
    public function getPaginate($columns = '*', $request = null, $filter = [], $relations = null)
    {
        $data = $this->model->select($columns)->search($request)->sort($request);

        if (! empty($filter)) {
            foreach ($filter as $key => $value) {
                if (is_array($value)) {
                    $data = $data->whereIn($key, $value);
                } elseif ($value === 'notnull') {
                    if (strpos($key, 'user.') === 0) {
                        $relation = str_replace('user.', '', $key);
                        $data = $data->whereHas('user', function ($query) use ($relation) {
                            $query->whereNotNull($relation);
                        });
                    } else {
                        $data = $data->whereNotNull($key);
                    }
                } elseif ($value === null) {
                    $data = $data->whereNull($key);
                } else {
                    $data = $data->where($key, $value);
                }
            }
        }

        if ($relations) {
            $data->with($relations);
        }

        return $this->paginate($data);
    }
}
