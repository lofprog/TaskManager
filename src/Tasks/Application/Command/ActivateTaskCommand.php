<?php
declare(strict_types=1);

namespace App\Tasks\Application\Command;

use App\Shared\Domain\Bus\Command\CommandInterface;

class ActivateTaskCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id
    ) {
    }
}