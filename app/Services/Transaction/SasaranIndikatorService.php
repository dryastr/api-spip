<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Transaction\SasaranIndikatorRepository;
use App\Repositories\Eloquent\Transaction\SasaranRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class SasaranIndikatorService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param SasaranIndikatorRepository $repository
     */
    public function __construct(
        protected SasaranIndikatorRepository $repository = new SasaranIndikatorRepository(),
        protected SasaranRepository $repositorySasaran = new SasaranRepository(),
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
        $penilaian = $this->repositorySasaran->findBy(
            key: [
                'id' => $data['trans_sasaran_id'],
            ],
            relations: [
                'penilaian',
            ]
        );

        $data['ref_klp_id'] = $penilaian->penilaian->ref_klp_id;

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
}
