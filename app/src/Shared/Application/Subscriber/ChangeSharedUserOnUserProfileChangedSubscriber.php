<?php
declare(strict_types=1);

namespace App\Shared\Application\Subscriber;

use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use App\Shared\Domain\Event\UserProfileWasChangedEvent;
use App\Shared\Domain\Exception\UserNotExistException;
use App\Shared\Domain\Repository\SharedUserRepositoryInterface;
use App\Shared\Domain\ValueObject\UserFirstname;
use App\Shared\Domain\ValueObject\UserId;
use App\Shared\Domain\ValueObject\UserLastname;

final class ChangeSharedUserOnUserProfileChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SharedUserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function subscribeTo(): array
    {
        return [UserProfileWasChangedEvent::class];
    }

    public function __invoke(UserProfileWasChangedEvent $event): void
    {
        $user = $this->userRepository->findById(new UserId($event->aggregateId));
        if ($user === null) {
            throw new UserNotExistException($event->aggregateId);
        }

        $user->changeProfile(
            new UserFirstname($event->firstname),
            new UserLastname($event->lastname)
        );

        $this->userRepository->save($user);
        $this->eventBus->dispatch(...$user->releaseEvents());
    }
}