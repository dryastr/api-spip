<?php

namespace App\Http\Controllers\Api\V1\Referensi;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\KategoriRisiko\CreateRequest;
use App\Http\Requests\Referensi\KategoriRisiko\UpdateRequest;
use App\Services\Referensi\KategoriRisikoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KategoriRisikoController extends Controller
{
    public function __construct(
        protected KategoriRisikoService $service = new KategoriRisikoService()
    )
    {
        $this->service = $service;

        $this->middleware('permission:referensi.kategori_risiko.view')->only(['index']);
        $this->middleware('permission:referensi.kategori_risiko.create')->only(['store']);
        $this->middleware('permission:referensi.kategori_risiko.edit')->only(['update']);
        $this->middleware('permission:referensi.kategori_risiko.delete')->only(['destroy']);
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return $this->response(
                $this->service->getPaginate(
                    selects: '*',
                    request: $request,
                    filter: [],
                    relations:  [
                        'klp:id,nama',
                    ]
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
            $validatedData = $request->validated();
            $kategoriRisiko = $this->service->create(data: $validatedData);
    
            return $this->response($kategoriRisiko);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }


    public function update(UpdateRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $id = security()->decrypt($id);
            $kategoriRisiko = $this->service->update($id, $validatedData);
    
            return $this->response($kategoriRisiko);
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
     * Get list of programs by KLP.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function listByKlp(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->service->getKlp($id);
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}