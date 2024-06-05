<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\RegistrationEmailVerificationRequest;
use App\Http\Requests\Registration\RegistrationRequest;
use App\Services\User\RegistrationService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Controller Constructor
     */
    public function __construct(
        protected RegistrationService $service = new RegistrationService(),
        protected UserService $serviceUser = new UserService(),
    ) {
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
                $this->serviceUser->getListUserApproval('*', $request, $request->filter)
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Registration
     *
     * @return JsonResponse
     */
    public function registration(RegistrationRequest $request)
    {
        try {
            $response = $this->service->registration($request->validated());

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Registration Email Verificatio
     *
     * @return JsonResponse
     */
    public function registrationEmailVerification(RegistrationEmailVerificationRequest $request)
    {
        try {
            $response = $this->service->registrationEmailVerification($request->validated());

            return $this->response($response);
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
            $data = $this->serviceUser->findById($id);
            if ($data) {
                return $this->response($data);
            }

            return $this->responseError('Data not found', 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function approve($action, $id): JsonResponse
    {
        try {
            if ($action === 'approve') {
                $status = 'disetujui';
            } elseif ($action === 'reject') {
                $status = 'ditolak';
            } else {
                // Jika action tidak valid
                return $this->responseError(__('Invalid'), 400);
            }

            $data = ['status_approval' => $status];

            return $this->response($this->serviceUser->approveUser($id, $data));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function countApprovalStatus(): JsonResponse
    {
        try {
            return $this->response($this->serviceUser->countAllApprovalStatus());
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
