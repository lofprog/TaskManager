<?php
declare(strict_types=1);

namespace App\TaskManagers\Infrastructure\Persistence\Doctrine\Type;

use App\TaskManagers\Domain\Factory\TaskStatusFactory;
use App\TaskManagers\Domain\ValueObject\TaskStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class TaskStatusType extends IntegerType
{
    private const NAME = 'taskStatus';


    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): TaskStatus
    {
        $value = parent::convertToPHPValue($value, $platform);
        return TaskStatusFactory::objectFromScalar($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return TaskStatusFactory::scalarFromObject($value);
    }
}