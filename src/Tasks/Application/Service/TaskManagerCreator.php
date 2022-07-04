<?php
declare(strict_types=1);

namespace App\Tasks\Application\Service;

use App\Shared\Domain\Collection\UserIdCollection;
use App\Shared\Domain\Service\UuidGeneratorInterface;
use App\Tasks\Domain\Collection\TaskCollection;
use App\Tasks\Domain\DTO\TaskManagerDTO;
use App\Tasks\Domain\Entity\TaskManager;
use App\Tasks\Domain\Factory\TaskManagerFactory;

final class TaskManagerCreator
{
    public function __construct(
        private readonly TaskManagerFactory $managerFactory,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function create(string $projectId, int $status, string $ownerId, string $finishDate): TaskManager
    {
        $dto = new TaskManagerDTO(
            $this->uuidGenerator->generate(),
            $projectId,
            $status,
            $ownerId,
            $finishDate,
            new UserIdCollection(),
            new TaskCollection()
        );
        return $this->managerFactory->create($dto);
    }
}
