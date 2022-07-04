<?php
declare(strict_types=1);

namespace App\Requests\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class RequestManagerNotExistsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Request manager doesn\'t exist', self::CODE_NOT_FOUND);
    }
}