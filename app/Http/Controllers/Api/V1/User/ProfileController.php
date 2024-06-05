<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Controller Constructor
     */
    public function __construct(
        protected UserService $serviceUser = new UserService(),
    ) {
    }

    /**
     * Login
     *
     * @return JsonResponse
     */
    public function me()
    {
        try {
            $response = $this->serviceUser->me();

            return $this->response($response);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
