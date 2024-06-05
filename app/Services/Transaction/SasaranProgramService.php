<?php
    namespace App\Services\Transaction;

    use App\Exceptions\ServiceErrorException;
    use App\Repositories\Eloquent\Transaction\PenilaianRepository;
    use App\Repositories\Eloquent\Transaction\SasaranProgramRepository;
    use App\Traits\ResponseTransform;
    use App\Traits\SlugHeaderRequest;
    use DB;
    use Illuminate\Support\Collection;

    class SasaranProgramService
    {
        use SlugHeaderRequest;
        use ResponseTransform;

        /**
         * Constructor.
         *
         * @param SasaranProgramRepository $repository
         */
        public function __construct(
            protected ?SasaranProgramRepository $repository = null,
            protected PenilaianRepository $repositoryPenilaian = new PenilaianRepository(),
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
         * Create Sasaran Program
         *
         * @return \Illuminate\Database\Eloquent\Model
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
        public function findBy($id, $selects = '*', $relations = null)
        {
            return $this->repository->find($id, $selects, $relations);
        }

        /**
         * Update Sasaran Program
         *
         * @return \Illuminate\Database\Eloquent\Model
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
         * Delete Sasaran Program
         *
         * @return \Illuminate\Database\Eloquent\Model
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

        public function getProgramsByPenilaian(int $penilaianId): Collection
        {
            return $this->repository->getList(
                wheres: ['trans_penilaian_id' => $penilaianId],
                relations: [
                    'penilaian:id,anggaran,status,tahun',
                    'program:id,kode,nama',
                    'sasaran:id,kode,nama,nsa_orientasi_hasil,nsa_relevan_mandat_sa,nsa_uji_kecukupan_indikator_sa,catatan',
                    'sasaran_indikator:id,kode,nama,target_kinerja,satuan,nsa_indikator_kinerja_tepat,nsa_target_kinerja_tepat',
                ]
            );
        }
    }
