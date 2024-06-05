<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Sasaran\ChecklistRequest;
use App\Http\Requests\Transaction\Sasaran\CreateRequest;
use App\Http\Requests\Transaction\Sasaran\UpdateRequest;
use App\Services\Transaction\SasaranService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SasaranController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected SasaranService $service = new SasaranService()
    ) {
        $this->middleware('permission:transaction.sasaran.show')->only(['show']);
        $this->middleware('permission:transaction.sasaran.create')->only(['store']);
        $this->middleware('permission:transaction.sasaran.edit')->only(['update']);
        $this->middleware('permission:transaction.sasaran.delete')->only(['destroy']);
        $this->middleware('permission:transaction.sasaran.listByParent')->only(['listByParent']);
        $this->middleware('permission:transaction.sasaran.listByPenilaian')->only(['listByPenilaian']);
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
                $this->service->getListSasaran(
                    data: [
                        'trans_penilaian_id' => $request->trans_penilaian_id,
                    ]
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
                relations: []
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
            // return $this->response($request->validated());
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

    public function listByParent($id): JsonResponse
    {
        try {
            $data = $this->service->getList(
                where: ['parent_id' => $id ]
            );

            if (count($data) > 0) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function listByPenilaian($id): JsonResponse
    {
        try {
            $data = $this->service->getList(
                where: ['penilaian_id' => $id, 'parent_id' => null]
            );
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function listByPenilaianAll($id): JsonResponse
    {
        try {
            $data = $this->service->getList(
                where: ['penilaian_id' => $id]
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
     * Get list sasaran
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            return $this->response(
                $this->service->getList(
                    where: [
                        'trans_penilaian_id' => $request->trans_penilaian_id,
                    ]
                )
            );
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
