<?php

namespace App\Modules\Auth\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\Auth\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService extends BaseService
{
    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->authRepository = $repository;
    }

    /**
     * Register a new user
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return $this->transaction(function () use ($data) {
            $user = $this->authRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? UserRole::CUSTOMER->value,
            ]);

            // Create service provider profile if role is service_provider
            if ($user->role === UserRole::SERVICE_PROVIDER) {
                $user->serviceProviderProfile()->create([
                    'bio' => $data['bio'] ?? null,
                    'city_id' => $data['city_id'] ?? null,
                    'country_code' => $data['country_code'] ?? null,
                ]);
            }

            return $user;
        });
    }

    /**
     * Authenticate user and create token
     *
     * @param string $email
     * @param string $password
     * @return array|null
     */
    public function login(string $email, string $password): ?array
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $this->authRepository->createToken($user, 'auth_token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }

    /**
     * Logout user (revoke token)
     *
     * @param User $user
     * @param string $token
     * @return bool
     */
    public function logout(User $user, string $token): bool
    {
        return $this->authRepository->deleteToken($user, $token);
    }

    /**
     * Get authenticated user's tokens
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTokens(User $user)
    {
        return $this->authRepository->getTokens($user);
    }

    /**
     * Validate registration data
     *
     * @param array $data
     * @return bool
     */
    public function validateRegistration(array $data): bool
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:' . implode(',', UserRole::values()),
        ]);

        return $validator->passes();
    }

    /**
     * Validate login data
     *
     * @param array $data
     * @return bool
     */
    public function validateLogin(array $data): bool
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        return $validator->passes();
    }
}
