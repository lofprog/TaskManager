<?php
declare(strict_types=1);

namespace App\Users\Application\Service;

use App\Shared\Domain\Exception\UserNotExistException;
use App\Shared\Domain\Security\AuthenticatorServiceInterface;
use App\Shared\Domain\Security\PasswordHasherInterface;
use App\Shared\Domain\ValueObject\UserEmail;
use App\Users\Domain\Repository\UserRepositoryInterface;

final class LoginService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $hasher,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function login(string $username, string $plainPassword): string
    {
        $user = $this->userRepository->findByEmail(new UserEmail($username));
        if ($user === null) {
            throw new UserNotExistException($username);
        }

        if (!$this->hasher->verifyPassword($user->getPassword()->value, $plainPassword)) {
            throw new UserNotExistException($username);
        }

        return $this->authenticator->getToken($user->getId()->value);
    }
}
