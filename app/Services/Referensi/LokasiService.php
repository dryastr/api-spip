<?php

namespace App\Services\Referensi;

use App\Repositories\Eloquent\Referensi\LokasiRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class LokasiService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param LokasiRepository $repository
     */
    public function __construct(
        protected LokasiRepository $repository = new LokasiRepository(),
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

    /**
     * List Lokasi
     *
     * @param string $search
     * @param array $wheres
     *
     * @return Collection
     */
    public function list(?string $search = null)
    {
        return $this->repository->getList(
            search: $search ?? ''
        );
    }

    public function listWhere(?array $where = [])
    {
        return $this->repository->getList(wheres: $where);
    }
}
