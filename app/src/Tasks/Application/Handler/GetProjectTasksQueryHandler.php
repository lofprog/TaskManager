<?php
declare(strict_types=1);

namespace App\Tasks\Application\Handler;

use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Shared\Application\DTO\PaginationDTO;
use App\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Shared\Application\Service\Pagination;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\ExpressionOperand;
use App\Tasks\Application\Query\GetProjectTasksQuery;
use App\Tasks\Application\Query\GetProjectTasksQueryResponse;
use App\Tasks\Domain\Repository\TaskQueryRepositoryInterface;

final class GetProjectTasksQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TaskQueryRepositoryInterface $taskRepository,
        private readonly AuthenticatorServiceInterface $authenticatorService
    ) {
    }

    /**
     * @param GetProjectTasksQuery $query
     * @return GetProjectTasksQueryResponse
     */
    public function __invoke(GetProjectTasksQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();

        $criteria = new Criteria([
            new ExpressionOperand('projectId', '=', $query->projectId),
            new ExpressionOperand('userId', '=', $userId->value)
        ]);
        $criteria->loadScalarFilters($query->criteria->filters);
        $count = $this->taskRepository->findCountByCriteria($criteria);

        $pagination = new Pagination(
            $count,
            $query->criteria->page
        );
        $criteria->loadScalarOrders($query->criteria->orders)
            ->loadOffsetAndLimit(...$pagination->getOffsetAndLimit());
        $tasks = $this->taskRepository->findAllByCriteria($criteria);

        return new GetProjectTasksQueryResponse(
            PaginationDTO::createFromPagination($pagination),
            ...$tasks
        );
    }
}
