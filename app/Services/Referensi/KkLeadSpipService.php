<?php

namespace App\Services\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Referensi\KkLeadSpipRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class KkLeadSpipService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param KkLeadSpipRepository $repository
     */
    public function __construct(
        protected KkLeadSpipRepository $repository = new KkLeadSpipRepository(),
    ) {
    }

    /**
     * Get Paginate Sasaran Program
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
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

    public function getList(array $where = [])
    {
        return $this->repository->getList(
            wheres: $where,
        );
    }

    /**
     * Get list sasaran
     *
     * @return mixed
     */
    // public function getListSasaran($data)
    // {
    //     return $this->repository->getWhere(
    //         wheres: [
    //             'trans_penilaian_id' => $data['trans_penilaian_id'],
    //             'parent_id' => null,
    //         ],
    //         relations: [
    //             'childrens',
    //         ]
    //     );
    // }
}
