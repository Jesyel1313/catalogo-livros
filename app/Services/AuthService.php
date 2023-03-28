<?php

namespace App\Services;

use App\Exceptions\ClientException;

/**
 * Service for user authentication.
 */
final class AuthService extends Service
{
    /**
     * Authenticates the user according to the provided credentials.
     * 
     * @param string[] $credentials The user's credentials (email and password).
     * 
     * @throws ClientException If credentials are invalid.
     * 
     * @return string[] The requested JWT data.
     */
    public function authenticate(array $credentials)
    {
        $token = auth()->attempt($credentials);
        if (!$token) {
            throw new ClientException('Invalid credentials.', 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Updates the token and its data.
     * 
     * @return string[] The requested JWT data.
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Formats token data.
     * 
     * @param string $token The generated JWT.
     * 
     * @return string[] The JWT data.
     */
    private function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}