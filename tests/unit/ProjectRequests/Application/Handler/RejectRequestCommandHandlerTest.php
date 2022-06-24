<?php
declare(strict_types=1);

namespace App\Tests\unit\ProjectRequests\Application\Handler;

use App\ProjectRequests\Application\CQ\RejectRequestCommand;
use App\ProjectRequests\Application\Handler\RejectRequestCommandHandler;
use App\ProjectRequests\Domain\ValueObject\RejectedRequestStatus;
use DG\BypassFinals;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class RejectRequestCommandHandlerTest extends TestCase
{
    use ChangeStatusSetUpTrait;

    private Generator $faker;

    protected function setUp(): void
    {
        BypassFinals::setWhitelist(['*/src/*']);
        BypassFinals::enable();
        $this->faker = Factory::create();
    }

    public function testInvoke()
    {
        $requestId = $this->faker->uuid();
        $userId = $this->faker->uuid();
        $command = new RejectRequestCommand($requestId, $userId);

        $handler = new RejectRequestCommandHandler(...$this->setUpHandlerParams(
            RejectedRequestStatus::class,
            $requestId,
            $userId
        ));
        $handler->__invoke($command);
    }
}
