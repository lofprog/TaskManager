<?php
declare(strict_types=1);

namespace App\Projects\Application\Factory;

use App\Projects\Application\DTO\ProjectDTO;
use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\ValueObject\ProjectDescription;
use App\Projects\Domain\ValueObject\ProjectInformation;
use App\Projects\Domain\ValueObject\ProjectName;
use App\Projects\Domain\ValueObject\ProjectTasks;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Owner;
use App\Shared\Domain\ValueObject\Participants;
use App\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Shared\Domain\ValueObject\Projects\ProjectStatus;
use App\Shared\Domain\ValueObject\Users\UserId;

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
            ProjectStatus::createFromScalar($dto->status),
            new Owner(
                new UserId($dto->ownerId)
            ),
            new Participants($dto->participantIds),
            new ProjectTasks($dto->tasks)
        );
    }
}