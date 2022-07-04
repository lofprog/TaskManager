<?php
declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\Exception\UserIsAlreadyOwnerException;
use App\Shared\Domain\Exception\UserIsNotOwnerException;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\UserId;

final class ProjectOwner
{
    public function __construct(
        public readonly UserId $userId,
        public readonly Email $userEmail,
    ) {
    }

    public function ensureIsOwner(UserId $userId): void
    {
        if (!$this->isOwner($userId)) {
            throw new UserIsNotOwnerException($userId->value);
        }
    }

    public function ensureIsNotOwner(UserId $userId): void
    {
        if ($this->isOwner($userId)) {
            throw new UserIsAlreadyOwnerException($userId->value);
        }
    }

    public function isOwner(UserId $userId): bool
    {
        return $this->userId->isEqual($userId);
    }
}
