<?php

namespace App\Modules\Auth\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * Find user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Create token for user
     *
     * @param User $user
     * @param string $tokenName
     * @param array $abilities
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(User $user, string $tokenName, array $abilities = ['*']);

    /**
     * Delete token
     *
     * @param User $user
     * @param string $token
     * @return bool
     */
    public function deleteToken(User $user, string $token): bool;

    /**
     * Get user tokens
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTokens(User $user);
}
