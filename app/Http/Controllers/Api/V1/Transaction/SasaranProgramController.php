<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\SasaranProgram\CreateRequest;
use App\Http\Requests\Transaction\SasaranProgram\UpdateRequest;
use App\Services\Transaction\SasaranProgramService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SasaranProgramController extends Controller
{
    protected SasaranProgramService $service;

    public function __construct(SasaranProgramService $service)
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
            $filter = [];
            $filter['trans_penilaian_id'] = $request->trans_penilaian_id;

            $relations = [
                'penilaian',
                'program',
                'sasaran',
                'sasaran_indikator',
            ];

            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: $filter,
                    relations: $relations
                )
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    // public function create()
    // {
    //     return view('sasaran_program.create');
    // }

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

    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $program = $this->service->create(data: $request->validated());
            return $this->response($program);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    // public function edit($id)
    // {
    //     $sasaranProgram = SasaranProgram::find($id);
    //     return view('sasaran_program.edit', compact('sasaranProgram'));
    // }

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
     * Get program by id.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function getProgramById($id): JsonResponse
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
     * Get list of programs by penilaian.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function listByPenilaian(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->service->getProgramsByPenilaian($id);
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
