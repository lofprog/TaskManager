<?php
declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Projects\Domain\Exception\ProjectParticipantNotExistException;
use App\Shared\Domain\Collection\UserIdCollection;
use App\Shared\Domain\Exception\UserIsAlreadyParticipantException;
use App\Shared\Domain\ValueObject\UserId;

final class ProjectParticipants
{
    private readonly UserIdCollection $participants;

    public function __construct(?UserIdCollection $items = null)
    {
        if ($items === null) {
            $this->participants = new UserIdCollection();
        } else {
            $this->participants = $items;
        }
    }

    public function ensureIsParticipant(UserId $userId): void
    {
        if (!$this->isParticipant($userId)) {
            throw new ProjectParticipantNotExistException();
        }
    }

    public function ensureIsNotParticipant(UserId $userId): void
    {
        if ($this->isParticipant($userId)) {
            throw new UserIsAlreadyParticipantException();
        }
    }

    public function isParticipant(UserId $userId): bool
    {
        return $this->participants->exists($userId);
    }

    public function remove(UserId $userId): self
    {
        $result = new self();
        $result->participants = $this->participants->remove($userId);
        return $result;
    }

    public function getInnerItems(): UserIdCollection
    {
        return $this->participants;
    }
}
