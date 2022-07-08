<?php
declare(strict_types=1);

namespace App\Projects\Application\Subscriber;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use App\Shared\Domain\Event\TaskInformationWasChangedEvent;

final class ChangeTaskDatesOnTaskInformationChangedSubscriber implements EventSubscriberInterface
{
    use ProjectSubscriberTrait;

    public function subscribeTo(): array
    {
        return [TaskInformationWasChangedEvent::class];
    }

    public function __invoke(TaskInformationWasChangedEvent $event): void
    {
        $this->doInvoke($event->projectId, $event);
    }
}
