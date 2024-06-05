<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\VisiMisi\CreateRequest;
use App\Services\Transaction\VisiMisiService;
use Exception;
use Illuminate\Http\JsonResponse;

class VisiMisiController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected VisiMisiService $service = new VisiMisiService()
    ) {
        $this->service = $service;
        $this->middleware('permission:transaction.penilaian.visi')->only(['index', 'store']);
        $this->middleware('permission:transaction.penilaian.misi')->only(['index', 'store']);
    }

    /**
     * Store new data
     *
     * @param  CreateRequest $request
     *
     * @return JsonResponse
     */

    public function store(CreateRequest $request, $id): JsonResponse
    {
        try {
            $datas = $request->validated();
            $datas['trans_penilaian_id'] = $id;
            $program = $this->service->create(data: $datas);
            return $this->response($program);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = $this->service->findBy(
                keys: [
                    'trans_penilaian_id' => $id,
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
}
