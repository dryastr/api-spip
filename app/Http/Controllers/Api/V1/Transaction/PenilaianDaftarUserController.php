<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Console\Commands\Mail\SendInvitationEmail;
use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\PenilaianDaftarUser\CreateRequest;
use App\Services\Transaction\PenilaianDaftarUserService;
use App\Jobs\SendEmail;
use App\Jobs\SendMailJob;
use App\Mail\InvitationEmail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

    class PenilaianDaftarUserController extends Controller
    {
        protected PenilaianDaftarUserService $service;

        /**
         * Controller Constructor
         */
        public function __construct(PenilaianDaftarUserService $service)
        {
            $this->service = $service;

            // $this->middleware('permission:transaction.penilaiandaftaruser.view')->only(['show']);
            // $this->middleware('permission:transaction.penilaiandaftaruser.create')->only(['store']);
            // $this->middleware('permission:transaction.penilaiandaftaruser.edit')->only(['update']);
            // $this->middleware('permission:transaction.penilaiandaftaruser.delete')->only(['destroy']);
        }

        public function index(Request $request): JsonResponse
        {
            try {
                return $this->response(
                    $this->service->getPaginate(
                        selects: '*',
                        request: $request,
                        filter: [],
                        relations: [
                            'user_detail:id,nip,fullname',
                            'attachment:table_id,name,extension'
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
            $klp = $request->header('X-Klp-Id');
            $validatedData = $request->validated();
            $validatedData['ref_klp_id'] = $klp;

            $data = [];

            foreach ($validatedData['users'] as $d) {
                $dt = [
                    'user_id' => $d['id'],
                    'trans_penilaian_id' => $validatedData['trans_penilaian_id'],
                    'ref_klp_id' => $validatedData['ref_klp_id'],
                    'no_surat' => $validatedData['no_surat'],
                    'surat_tugas' => $validatedData['surat_tugas'],
                    'tgl_surat' => $validatedData['tgl_surat'],
                    'tgl_mulai' => $validatedData['tgl_mulai'],
                    'tgl_selesai' => $validatedData['tgl_selesai'],
                ];


                $daftarUser = $this->service->create($dt);

                $this->service->uploadSuratTugas([
                    'id' => $daftarUser['id'],
                    'surat_tugas' => $request->file('surat_tugas'),
                ]);

                array_push($data, $daftarUser);
            }

            foreach ($validatedData['users'] as $d) {
                $this->dispatch(new SendMailJob($d['email'],$d['fullname'], $d['default_klp']['nama'], ''));
            }

            return $this->response($data);
        } catch (\InvalidArgumentException | Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
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
                return $this->response($data);
            }

            return $this->responseError(__('Not found'), 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function autocompleteNama(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $results = [];
            $results = $this->service->autocompleteNama(['search' => $search]);

            return $this->response($results);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function downloadSuratTugas($userId)
    {
        try {
            $dataDownload = $this->service->downloadSuratTugas($userId);

            // Jika berhasil, Anda dapat memberikan respons yang sesuai, misalnya:
            $fileContent = file_get_contents($dataDownload['filePath']);

            return response()->stream(
                function () use ($fileContent) {
                    echo $fileContent;
                },
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $dataDownload['attachmentData']['name'] . '"',
                ]
            );

        } catch (ServiceErrorException $e) {
            return $this->responseError($e->getMessage(), $e->getCode());
        }
    }

    }
