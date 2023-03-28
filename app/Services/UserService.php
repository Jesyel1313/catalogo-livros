<?php

namespace App\Services;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

/**
 * User service.
 */
final class UserService extends Service
{
    /**
     * Initializes properties.
     * 
     * @param Model $model  The user entity.
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Insert the new user into the database.
     * 
     * @param string[] $data    The new user's data.
     * 
     * @return int The new user ID.
     */
    public function insert(array $data): int
    {
        // Encrypt the password.
        $data['password'] = Hash::make($data['password']);

        return parent::insert($data);
    }
}