<?php
declare(strict_types=1);

namespace App\ProjectRequests\Domain\ValueObject;

final class RejectedRequestStatus extends RequestStatus
{
    protected function getNextStatuses(): array
    {
        return [];
    }
}