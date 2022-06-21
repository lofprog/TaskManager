<?php
declare(strict_types=1);

namespace App\Tasks\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class TaskStatusWasChangedEvent extends DomainEvent
{
    public function __construct(public readonly int $status)
    {
    }
}