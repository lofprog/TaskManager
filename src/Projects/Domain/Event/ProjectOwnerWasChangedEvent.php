<?php
declare(strict_types=1);

namespace App\Projects\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ProjectOwnerWasChangedEvent extends DomainEvent
{
    public function __construct(public readonly string $ownerId)
    {
    }
}