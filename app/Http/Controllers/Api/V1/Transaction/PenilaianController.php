<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Penilaian\CreateRequest;
use App\Http\Requests\Transaction\Penilaian\UpdateRequest;
use App\Http\Requests\Transaction\Penilaian\Updatev2Request;
use App\Services\Transaction\PenilaianService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected PenilaianService $service = new PenilaianService()
    ) {
        $this->middleware('permission:transaction.penilaian.view')->only(['index']);
        $this->middleware('permission:transaction.penilaian.create')->only(['store']);
        $this->middleware('permission:transaction.penilaian.edit')->only(['update']);
        $this->middleware('permission:transaction.penilaian.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filter = [];
            if ($request->ref_klp_id) {
                $filter['ref_klp_id'] = $request->ref_klp_id;
            }
            if ($request->status) {
                $filter['status'] = $request->status;
            }

            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: $filter,
                    relations: ['klp:id,kode,nama_pendek,nama']
                )
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->service->findBy(
                id: $id,
                relations: ['klp:id,kode,nama_pendek,nama']
            );
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Store new data
     *
     * @param  CreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $klp = $request->header('X-Klp-Id');

            $validatedData = $request->validated();
            $validatedData['ref_klp_id'] = $klp;

            $existingData = $this->service->getPaginate(
                selects: 'id',
                request: $request,
                filter: [
                    'ref_klp_id' => $klp,
                    'tahun' => $validatedData['tahun'],
                ]
            );

            if ($existingData->count() > 0 && $existingData[0]->ref_klp_id == $klp) {
                return $this->responseError('Data with the same KLP and year already exists', 422);
            }

            return $this->response($this->service->create($validatedData));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Update data
     *
     * @param  UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, $id): JsonResponse
    {
        try {
            $datas = $request->validated();

            return $this->response($this->service->update($id, $datas));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function updateAnggaran(Updatev2Request $request, $id): JsonResponse
    {
        try {
            $data = $this->service->updateAnggaran($id, $request->validated());
            return $this->response($data);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $data = $this->service->delete($request->id);
            if ($data) {
                return $this->response(true);
            }

            return $this->responseError(__('Not found'), 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function listpenilaian(Request $request): JsonResponse
    {
        try {
            return $this->response($this->service->getall());
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
