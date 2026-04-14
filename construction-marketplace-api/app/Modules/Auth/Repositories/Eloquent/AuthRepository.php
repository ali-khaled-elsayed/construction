<?php

namespace App\Modules\Auth\Repositories\Eloquent;

use App\Models\User;
use App\Modules\Auth\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findBy('email', $email);
    }

    /**
     * @inheritDoc
     */
    public function createToken(User $user, string $tokenName, array $abilities = ['*'])
    {
        return $user->createToken($tokenName, $abilities);
    }

    /**
     * @inheritDoc
     */
    public function deleteToken(User $user, string $token): bool
    {
        $tokens = $this->getTokens($user);

        foreach ($tokens as $userToken) {
            if ($userToken->id === $token) {
                return $userToken->delete();
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTokens(User $user)
    {
        return $user->tokens;
    }
}
