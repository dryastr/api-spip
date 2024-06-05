<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Exceptions\ServiceErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Controller Constructor
     */

    public function __construct(
        protected UserService $service = new UserService()
    ) {
        $this->middleware('permission:users.view')->only(['index']);
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
                $this->service->getUserPaginate('*', $request, $request->filter)
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
            $data = $this->service->findUserBy($id);
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
     * @param  UserRequest $request
     *
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            return $this->response($this->service->createUser(
                data: $request->validated()
            ));
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    /**
     * Update data
     *
     * @param  UserRequest $request
     *
     * @return JsonResponse
     */
    public function update(UserRequest $request, $id): JsonResponse
    {
        try {
            $datas = $request->validated();

            return $this->response($this->service->updateUser($id, $datas));
        } catch (Exception | ServiceErrorException $e) {
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
            $data = $this->service->deleteUser($request->id);
            if ($data) {
                return $this->response(true);
            }

            return $this->responseError(__('Not found'), 404);
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }

    public function countActiveStatus(): JsonResponse
    {
        try {
            return $this->response($this->service->countAllActiveStatus());
        } catch (Exception | ServiceErrorException $e) {
            return $this->responseException($e);
        }
    }
}
