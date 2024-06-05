<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\MyMenuResource;
use App\Http\Resources\User\MyMenu2Resource;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
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
            return $this->response(
                MyMenuResource::collection($this->serviceUser->menuMe())
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Login
     *
     * @return JsonResponse
     */
    public function me2()
    {
        try {
            return $this->response(
                MyMenu2Resource::collection($this->serviceUser->menuMe2())
            );
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
