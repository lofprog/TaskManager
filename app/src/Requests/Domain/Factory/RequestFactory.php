<?php
declare(strict_types=1);

namespace App\Requests\Domain\Factory;

use App\Requests\Domain\DTO\RequestDTO;
use App\Requests\Domain\Entity\Request;
use App\Requests\Domain\ValueObject\RequestId;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\UserId;

final class RequestFactory
{
    public function create(RequestDTO $dto) : Request
    {
        return new Request(
            new RequestId($dto->id),
            new UserId($dto->userId),
            RequestStatusFactory::objectFromScalar($dto->status),
            new DateTime($dto->changeDate),
        );
    }
}