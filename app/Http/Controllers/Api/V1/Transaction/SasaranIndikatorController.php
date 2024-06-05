<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\SasaranIndikator\ChecklistRequest;
use App\Http\Requests\Transaction\SasaranIndikator\CreateRequest;
use App\Http\Requests\Transaction\SasaranIndikator\UpdateRequest;
use App\Services\Transaction\SasaranIndikatorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SasaranIndikatorController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected SasaranIndikatorService $service = new SasaranIndikatorService()
    ) {
        $this->middleware('permission:transaction.sasaranindikator.view')->only(['index']);
        $this->middleware('permission:transaction.sasaranindikator.show')->only(['show']);
        $this->middleware('permission:transaction.sasaranindikator.create')->only(['store']);
        $this->middleware('permission:transaction.sasaranindikator.edit')->only(['update']);
        $this->middleware('permission:transaction.sasaranindikator.delete')->only(['destroy']);
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
            $filter['trans_sasaran_id'] = $request->trans_sasaran_id ?? null;

            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: $filter,
                    relations: []
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
            return $this->response($this->service->create(
                data: $request->validated()
            ));
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

    public function list(Request $request): JsonResponse
    {
        try {
            $data = $this->service->getList(
                where: ['trans_sasaran_id' => $request->trans_sasaran_id]
            );
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function updateChecklist(ChecklistRequest $request,$id): JsonResponse
    {
        try {
            $datas = $request->validated();

            return $this->response($this->service->update($id, $datas));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

}
