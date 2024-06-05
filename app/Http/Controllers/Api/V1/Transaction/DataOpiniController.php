<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\DataOpini\CreateRequest;
use App\Http\Requests\Transaction\DataOpini\UpdateRequest;
use App\Services\Transaction\DataOpiniService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataOpiniController extends Controller
{
    public function __construct(
        protected DataOpiniService $service = new DataOpiniService()
    )
    {
        $this->middleware('permission:transaction.data_opini.view')->only(['index','show']);
        $this->middleware('permission:transaction.data_opini.create')->only(['store']);
        $this->middleware('permission:transaction.data_opini.edit')->only(['update']);
        $this->middleware('permission:transaction.data_opini.delete')->only(['destroy']);
    }

    public function index(Request $request): JsonResponse
    {
        try {   
            $filter = [];
            if ($request->ref_klp_id) {
                $filter['ref_klp_id'] = $request->ref_klp_id;
            }

            if ($request->penilaian) {
                $filter['trans_penilaian_id'] = $request->penilaian;
            }

            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: $filter,
                    relations: []
                )
            );

        }catch(Exception | ServiceErrorException $e){
            return $this->responseException($e);
        }
    }

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

    public function update(UpdateRequest $request, $id): JsonResponse
    {
        try {
            $datas = $request->validated();

            return $this->response($this->service->update($id, $datas));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

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
}