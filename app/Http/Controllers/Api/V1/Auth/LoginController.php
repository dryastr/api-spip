<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * Controller Constructor
     */
    public function __construct(
        protected AuthService $serviceAuth = new AuthService(),
    ) {
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $response = $this->serviceAuth->verifyCredential($request->validated());

            return $this->response(
                data: [
                    'token_type' => 'bearer',
                    ...$response,
                ],
                message: 'Login berhasil'
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            return $this->response($this->serviceAuth->logout(), message: 'auth.logout_success');
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
