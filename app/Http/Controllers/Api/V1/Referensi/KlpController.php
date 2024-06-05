<?php

namespace App\Http\Controllers\Api\V1\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\Klp\CreateRequest;
use App\Http\Requests\Referensi\Klp\UpdateRequest;
use App\Services\Referensi\KlpService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KlpController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected KlpService $service = new KlpService()
    ) {
        $this->middleware('permission:referensi.klp.view')->only(['index', 'show']);
        $this->middleware('permission:referensi.klp.create')->only(['store']);
        $this->middleware('permission:referensi.klp.edit')->only(['update']);
        $this->middleware('permission:referensi.klp.delete')->only(['destroy']);
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
            if ($request->jenis) {
                $filter['jenis'] = $request->jenis;
            }
            return $this->response(
                $this->service->getPaginate('*', $request, $filter)
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
            $data = $this->service->findBy($id);
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
            dd($e);
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

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listKlp(Request $request): JsonResponse
    {
        try {
            $wheres = [
                'jenis' => $request->jenis,
            ];
            if (strtolower($request->jenis) === 'all') {
                $wheres = [];
            }

            $response = $this->service->list(
                search: $request->search,
                wheres: $wheres
            );

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listKlpWithUser($klpId): JsonResponse
    {
        try {
            $klpWithUsers = $this->service->getAllWithUsers($klpId);

            return $this->response($klpWithUsers);
        } catch (Exception | ServiceErrorException $e) {
            return $this->response([]);
        }
    }
    public function listOPD(): JsonResponse
    {
        try {
            $auth = auth()->user();
            $data = $this->service->getList(
                where: ['parent_id' => $auth->user_id]
            );
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
