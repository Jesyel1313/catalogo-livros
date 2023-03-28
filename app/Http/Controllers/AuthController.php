<?php

namespace App\Http\Controllers;

use App\Services\AuthService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Exception;

/**
 * Controller for authentication.
 */
final class AuthController extends Controller
{
    /**
     * Initializes properties and settings.
     * 
     * @param AuthService $service  The authentication service.
     */
    public function __construct(AuthService $service)
    {
        // Defines the service and withdraw authentication for the token
        // generation route.
        parent::__construct($service, ['except' => ['register']]);
    }

    /**
     * Performs the client login.
     * 
     * @param Request $request  The request object.
     * 
     * @return JsonResponse The request response.
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            $this->data = $this->service->authenticate($credentials);
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }

    /**
     * Logs out the client.
     * 
     * @param Request $request  The request object.
     * 
     * @return JsonResponse The request response.
     */
    public function remove(Request $request): JsonResponse
    {
        auth()->logout();

        $this->data['message'] = 'Token removed successfully!';

        return $this->send();
    }

    /**
     * Generates a new token.
     * 
     * @return Response The request response.
     */
    public function update()
    {
        $this->data = $this->service->refresh();

        return $this->send();
    }

    /**
     * Gets the currently authenticated user.
     * 
     * @param Request $request  The request object.
     * 
     * @return JsonResponse The request response.
     */
    public function retrieve(Request $request): JsonResponse
    {
        $this->data = auth()->user();

        return $this->send();
    }
}
