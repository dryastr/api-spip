<?php

namespace App\Services\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Repositories\Eloquent\Transaction\SasaranRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Illuminate\Support\Collection;

class SasaranService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param SasaranRepository $repository
     */
    public function __construct(
        protected SasaranRepository $repository = new SasaranRepository(),
    ) {
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
    public function getListSasaran($data)
    {
        $dataAll = $this->repository->getWhere(
            wheres: [
                'trans_penilaian_id' => $data['trans_penilaian_id'],
                'parent_id' => null,
            ],
            relations: [
                'jenis_sasaran:id,kode,nama'
            ]
        );
        $collectData = collect();
        foreach($dataAll as $d){
            $d['childrens'] = $this->repository->getWhere(
                wheres: [
                    'parent_id' => $d['id'],
                ],
                relations: [
                    'jenis_sasaran:id,kode,nama'
                ]
            );
            $collectData->push($d);
        }
        // dd($dataAll);
        return $collectData;
    }
}
