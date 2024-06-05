<?php

namespace App\Http\Controllers\Api\V1\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\KkLeadSpip\CreateRequest;
use App\Http\Requests\Referensi\KkLeadSpip\UpdateRequest;
use App\Services\Referensi\KkLeadSpipService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KkLeadSpipController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected KkLeadSpipService $service = new KkLeadSpipService()
    ) {
        $this->middleware('permission:referensi.kk_lead_spip.view')->only(['index']);
        $this->middleware('permission:referensi.kk_lead_spip.create')->only(['store']);
        $this->middleware('permission:referensi.kk_lead_spip.edit')->only(['update']);
        $this->middleware('permission:referensi.kk_lead_spip.delete')->only(['destroy']);
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
                    relations:  [
                        'jenis_kk_lead_spip:id,kode,nama',
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
            $id = security()->decrypt($id);
            $data = $this->service->findBy(
                id: $id,
                relations: ['jenis_kk_lead_spip:id,kode,nama']
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

    public function listParent(): JsonResponse
    {
        try {
            $data = $this->service->getList(
                where: ['parent_id' => null ]
            );

            if (count($data) > 0) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
