<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Transaction\PenilaianRepository;
use App\Repositories\Eloquent\Transaction\VisiMisiRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;

class VisiMisiService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param VisiMisiRepository $repository
     */
    public function __construct(
        protected VisiMisiRepository $repository = new VisiMisiRepository(),
        protected PenilaianRepository $repositoryPenilaian = new PenilaianRepository(),
    ) {
    }

    /**
     * Get Paginate
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

        return DB::transaction(function () use ($data) {
            return $this->repository->updateOrCreate([
                'trans_penilaian_id' => $data['trans_penilaian_id'],
            ], $data);
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
}
