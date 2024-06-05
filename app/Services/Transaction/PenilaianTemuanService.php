<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Transaction\PenilaianTemuanRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;

class PenilaianTemuanService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    public function __construct(
        protected PenilaianTemuanRepository $repository = new PenilaianTemuanRepository()
    ){

    }

    public function getPaginate($selects = '*', $request = null, $filter = [], $relations = null)
    {
        return $this->repository->getPaginate($selects, $request, $filter, $relations);
    }

    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        }, config('database.deadlock_retry', 3));
    }

    public function findBy($id, $selects = '*', $relations = null)
    {
        return $this->repository->find($id, $selects, $relations);
    }

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
    
}