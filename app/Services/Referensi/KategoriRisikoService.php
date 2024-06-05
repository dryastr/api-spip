<?php

namespace App\Services\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Referensi\KategoriRisikoRepository;
use App\Repositories\Eloquent\Referensi\KlpRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class KategoriRisikoService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param KategoriRisikoRepository $repository
     */
    public function __construct(
        protected KategoriRisikoRepository $repository = new KategoriRisikoRepository(),
        protected KlpRepository $repositoryKlp = new KlpRepository(),
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        $check = $this->repositoryKlp->find($data['ref_klp_id']);
        if (! $check) {
            throw new ServiceErrorException('Data not found', 422);
        }

        // $data['ref_klp_id'] = $check->ref_klp_id;

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

    public function getKlp(int $klpId): Collection
    {
        return $this->repository->getList(
            wheres: ['ref_klp_id' => $klpId],
            relations: [
                'klp:id,nama',
            ]
        );
    }
}
