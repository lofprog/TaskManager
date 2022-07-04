<?php
declare(strict_types=1);

namespace App\Projects\Domain\Factory;

use App\Projects\Domain\DTO\ProjectDTO;
use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\ValueObject\ProjectDescription;
use App\Projects\Domain\ValueObject\ProjectInformation;
use App\Projects\Domain\ValueObject\ProjectName;
use App\Projects\Domain\ValueObject\ProjectOwner;
use App\Projects\Domain\ValueObject\ProjectParticipants;
use App\Projects\Domain\ValueObject\ProjectTasks;
use App\Shared\Domain\Factory\ProjectStatusFactory;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\ProjectId;
use App\Shared\Domain\ValueObject\UserId;

final class ProjectFactory
{
    public function create(ProjectDTO $dto) : Project {
        return new Project(
            new ProjectId($dto->id),
            new ProjectInformation(
                new ProjectName($dto->name),
                new ProjectDescription($dto->description),
                new DateTime($dto->finishDate),
            ),
            ProjectStatusFactory::objectFromScalar($dto->status),
            new ProjectOwner(
                new UserId($dto->ownerId),
                new Email($dto->ownerEmail)
            ),
            new ProjectParticipants($dto->participantIds),
            new ProjectTasks($dto->tasks)
        );
    }
}
