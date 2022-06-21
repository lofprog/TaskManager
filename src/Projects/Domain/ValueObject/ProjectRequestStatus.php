<?php
declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Status;

abstract class ProjectRequestStatus extends Status
{
    public function allowsModification(): bool
    {
        return true;
    }
}