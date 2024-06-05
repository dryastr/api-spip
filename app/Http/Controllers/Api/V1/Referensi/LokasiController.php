<?php

namespace App\Http\Controllers\Api\V1\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\Lokasi\CreateRequest;
use App\Http\Requests\Referensi\Lokasi\UpdateRequest;
use App\Services\Referensi\LokasiService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected LokasiService $service = new LokasiService()
    ) {
        $this->middleware('permission:referensi.lokasi.view')->only(['index', 'show']);
        $this->middleware('permission:referensi.lokasi.create')->only(['store']);
        $this->middleware('permission:referensi.lokasi.edit')->only(['update']);
        $this->middleware('permission:referensi.lokasi.delete')->only(['destroy']);
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
            $id = security()->decrypt($id);
            $data = $this->service->findBy(
                id: $id,
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
            $id = security()->decrypt($id);

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
            $response = $this->service->list(
                search: $request->search,
            );

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }

    public function listProvinsi(): JsonResponse
    {
        try {
            $response = $this->service->listWhere(
                ['parent_id' => null]
            );

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }

    public function listKabKot($id): JsonResponse
    {
        try {
            $id = security()->decrypt($id);
            $response = $this->service->listWhere(
                ['parent_id' => $id]
            );

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }
}
