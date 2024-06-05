<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Transaction\PenilaianRepository;
use App\Repositories\Eloquent\Transaction\ProgramRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class ProgramService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param ProgramRepository $repository
     */
    public function __construct(
        protected ProgramRepository $repository = new ProgramRepository(),
        protected PenilaianRepository $repositoryPenilaian = new PenilaianRepository(),
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
        $check = $this->repositoryPenilaian->find($data['trans_penilaian_id']);
        if (! $check) {
            throw new ServiceErrorException('Data not found', 422);
        }

        $data['ref_klp_id'] = $check->ref_klp_id;

        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        }, config('database.deadlock_retry', 3));
    }

    /**
     * Find By
     *
     * @return Collection
     */
    public function findBy($keys, $selects = '*', $relations = null)
    {
        return $this->repository->findBy($keys, $selects, $relations);
    }

    /**
     * Update
     *
     * @return Model
     */
    public function update($id, $data)
    {
        $query = $this->repository->findBy(
            key: [
                'id' => $id,
            ]
        );

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        $result = DB::transaction(function () use ($data, $query) {
            return $query->update($data);
        }, config('database.deadlock_retry', 3));

        return $query;
    }

    /**
     * Delete
     *
     * @return Model
     */
    public function delete($id)
    {
        $query = $this->repository->findBy(
            key: [
                'id' => $id,
            ]
        );

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

    public function findById(int $id)
    {
        $program = $this->repository->find($id);

        if (! $program) {
            throw new ServiceErrorException('Program not found', 404);
        }

        return $program;
    }

    public function getProgramsByPenilaian(int $id): Collection
    {
        return $this->repository->getList(
            wheres: ['trans_penilaian_id' => $id]
        );
    }
}
