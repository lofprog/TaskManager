<?php
declare(strict_types=1);

namespace App\TaskManagers\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class TaskStatusWasChangedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $taskId,
        public readonly string $status,
        string $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'projectTask.taskStatusChanged';
    }

    public static function fromPrimitives(string $aggregateId, array $body, string $occurredOn): static
    {
        return new self($aggregateId, $body['taskId'], $body['status'], $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [
            'taskId' => $this->taskId,
            'status' => $this->status,
        ];
    }
}