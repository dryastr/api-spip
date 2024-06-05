<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Transaction\PenilaianRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class PenilaianService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param PenilaianRepository $repository
     */
    public function __construct(
        protected PenilaianRepository $repository = new PenilaianRepository(),
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
     * Update Anggaran
     *
     * @param  int    $id
     * @param  array  $data
     *
     * @return Model
     */
    public function updateAnggaran($id, $data)
    {
        try {
            if (! isset($data['anggaran'])) {
                throw new ServiceErrorException('Anggaran is required.', 422);
            }

            if (! is_numeric($data['anggaran'])) {
                throw new ServiceErrorException('Anggaran must be a numeric value.', 422);
            }

            $query = $this->repository->find($id);

            if (! $query) {
                throw new ServiceErrorException('Data not found', 404);
            }

            return DB::transaction(function () use ($data, $query) {
                return $query->update($data);
            }, config('database.deadlock_retry', 3));
        } catch (ServiceErrorException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceErrorException($e->getMessage(), $e->getCode(), $e);
        }
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
    
    public function getall()
    {
        return $this->repository->getWhere();
    }
}
