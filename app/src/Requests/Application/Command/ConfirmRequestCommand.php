<?php
declare(strict_types=1);

namespace App\Requests\Application\Command;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ConfirmRequestCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id
    ) {
    }
}