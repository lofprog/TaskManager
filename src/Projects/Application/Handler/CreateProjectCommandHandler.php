<?php
declare(strict_types=1);

namespace App\Projects\Application\Handler;

use App\Projects\Application\Command\CreateProjectCommand;
use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Domain\ValueObject\ProjectDescription;
use App\Projects\Domain\ValueObject\ProjectInformation;
use App\Projects\Domain\ValueObject\ProjectName;
use App\Projects\Domain\ValueObject\ProjectOwner;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\Service\AuthenticatorServiceInterface;
use App\Shared\Domain\UuidGeneratorInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\ProjectId;

final class CreateProjectCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly EventBusInterface $eventBus,
        private readonly AuthenticatorServiceInterface $authenticator
    ) {
    }

    public function __invoke(CreateProjectCommand $command): void
    {
        $project = Project::create(
            new ProjectId($this->uuidGenerator->generate()),
            new ProjectInformation(
                new ProjectName($command->name),
                new ProjectDescription($command->description),
                new DateTime($command->finishDate)
            ),
            new ProjectOwner($this->authenticator->getAuthUser()->userId)
        );

        $this->projectRepository->save($project);
        $this->eventBus->dispatch(...$project->releaseEvents());
    }
}