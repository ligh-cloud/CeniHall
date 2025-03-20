<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array $userData
     * @return User
     */
    public function create(array $userData): User
    {
        $userData['password'] = Hash::make($userData['password']);
        return User::create($userData);
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Update user data.
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Authenticate user and return JWT token.
     *
     * @param array $credentials
     * @return string|null
     */
    public function authenticate(array $credentials): ?string
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return null;
            }
            return $token;
        } catch (JWTException $e) {
            throw $e;
        }
    }

    /**
     * Invalidate the current JWT token.
     *
     * @return bool
     */
    public function invalidateToken(): bool
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return true;
        } catch (JWTException $e) {
            return false;
        }
    }

    /**
     * Get authenticated user from token.
     *
     * @return User|null
     */
    public function getUserFromToken(): ?User
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return null;
        }
    }
}
