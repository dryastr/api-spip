<?php

namespace App\Http\Controllers\Api\V1\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\JenisLeadSpip\CreateRequest;
use App\Http\Requests\Referensi\JenisLeadSpip\UpdateRequest;
use App\Services\Referensi\JenisLeadSpipService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JenisLeadSpipController extends Controller
{
    protected JenisLeadSpipService $service;

    public function __construct(JenisLeadSpipService $service)
    {
        $this->service = $service;

        $this->middleware('permission:referensi.jenis_kk_lead_spip.view')->only(['index']);
        $this->middleware('permission:referensi.jenis_kk_lead_spip.create')->only(['store']);
        $this->middleware('permission:referensi.jenis_kk_lead_spip.edit')->only(['update']);
        $this->middleware('permission:referensi.jenis_kk_lead_spip.delete')->only(['destroy']);
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: [],
                )
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $id = security()->decrypt($id);
            $data = $this->service->findBy($id);
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
            $id = security()->decrypt($id);

            return $this->response($this->service->update($id, $datas));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $id = security()->decrypt($request->id);
            $data = $this->service->delete($id);
            if ($data) {
                return $this->response(true);
            }

            return $this->responseError(__('Not found'), 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function list(): JsonResponse
    {
        try {
            $response = $this->service->list();

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }
}
