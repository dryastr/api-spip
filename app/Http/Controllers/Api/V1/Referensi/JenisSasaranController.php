<?php

namespace App\Http\Controllers\Api\V1\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\JenisSasaran\CreateRequest;
use App\Http\Requests\Referensi\JenisSasaran\UpdateRequest;
use App\Services\Referensi\JenisSasaranService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JenisSasaranController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected JenisSasaranService $service = new JenisSasaranService()
    ) {
        $this->middleware('permission:referensi.jenis_sasaran.view')->only(['index']);
        $this->middleware('permission:referensi.jenis_sasaran.create')->only(['store']);
        $this->middleware('permission:referensi.jenis_sasaran.edit')->only(['update']);
        $this->middleware('permission:referensi.jenis_sasaran.delete')->only(['destroy']);
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: [],
                    relations:['klp:id,nama,kode']
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

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $wheres = [];

            $response = $this->service->list(
                search: $request->search,
                wheres: $wheres
            );

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }
}
