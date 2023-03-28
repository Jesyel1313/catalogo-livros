<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;

use Illuminate\Http\JsonResponse;

use Exception;

/**
 * User controller.
 */
final class UserController extends Controller
{
    /**
     * Initializes properties.
     * 
     * @param UserService $service  The user service.
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service, ['except' => ['register']]);
    }

    /**
     * Registers a new user.
     * 
     * @param UserRequest $request  The request rules for user.
     * 
     * @return JsonResponse The request response.
     */
    public function register(UserRequest $request): JsonResponse
    {
        try {
            $user = $request->validated();

            $this->data['message'] = 'User successfully created!';

            $this->data['id'] = $this->service->insert($user);
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }
}
