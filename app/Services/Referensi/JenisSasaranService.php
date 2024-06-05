<?php

namespace App\Services\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Referensi\JenisSasaranRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class JenisSasaranService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param JenisSasaranRepository $repository
     */
    public function __construct(
        protected JenisSasaranRepository $repository = new JenisSasaranRepository(),
    ) {
    }

    /**
     * Get Paginate Klp
     *
     * @return Paginate
     */
    public function getPaginate($selects = '*', $request = null, $filter = [], $relations = null)
    {
        return $this->repository->getPaginate($selects, $request, $filter, $relations);
    }

    /**
     * Create
     *
     * @return Model
     */
    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        }, config('database.deadlock_retry', 3));
    }

    /**
     * Find By
     *
     * @return Collection
     */
    public function findBy($id, $selects = '*', $relations = null)
    {
        return $this->repository->find($id, $selects, $relations);
    }

    /**
     * Update
     *
     * @return Model
     */
    public function update($id, $data)
    {
        $query = $this->repository->find($id);

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        $result = DB::transaction(function () use ($data, $query) {
            return $query->update($data);
        }, config('database.deadlock_retry', 3));

        return $query;
    }

    /**
     * Update
     *
     * @return Model
     */
    public function delete($id)
    {
        $query = $this->repository->find($id);

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        return DB::transaction(function () use ($query) {
            return $query->delete();
        }, config('database.deadlock_retry', 3));
    }

    public function listWhere(?array $where = [])
    {
        return $this->repository->getList(wheres: $where);
    }

    public function list(?string $search = null, array $wheres = [])
    {
        return $this->repository->getList(
            search: $search ?? '',
            wheres: $wheres
        );
    }
}
