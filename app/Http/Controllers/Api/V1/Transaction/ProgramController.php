<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Program\CreateRequest;
use App\Http\Requests\Transaction\Program\UpdateRequest;
use App\Services\Transaction\ProgramService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    protected ProgramService $service;

    /**
     * Controller Constructor
     */
    public function __construct(ProgramService $service)
    {
        $this->service = $service;

        // $this->middleware('permission:transaction.program.view')->only(['show']);
        // $this->middleware('permission:transaction.program.create')->only(['store']);
        // $this->middleware('permission:transaction.program.edit')->only(['update']);
        // $this->middleware('permission:transaction.program.delete')->only(['destroy']);
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
                    filter: [
                        'trans_penilaian_id' => $request->trans_penilaian_id,
                    ],
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
                keys: [
                    'id' => $id,
                ]
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
            $datas = $request->validated();
            $program = $this->service->create($datas);
            return $this->response($program);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Update data
     *
     * @param  UpdateRequest $request
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->validated();

            return $this->response($this->service->update($id, $data));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }

        return response()->json(['message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
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
     * Get program by id.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function getProgramById($id): JsonResponse
    {
        try {
            $data = $this->service->findById($id);

            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Get list program
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            return $this->response(
                $this->service->getProgramsByPenilaian($request->trans_penilaian_id)
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
