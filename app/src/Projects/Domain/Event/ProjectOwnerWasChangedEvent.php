<?php
declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ProjectOwnerWasChangedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $ownerId,
        public readonly string $ownerEmail,
        string $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'project.ownerChanged';
    }

    public static function fromPrimitives(string $aggregateId, array $body, string $occurredOn): static
    {
        return new self($aggregateId, $body['ownerId'], $body['ownerEmail'], $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [
            'ownerId' => $this->ownerId,
            'ownerEmail' => $this->ownerEmail
        ];
    }
}