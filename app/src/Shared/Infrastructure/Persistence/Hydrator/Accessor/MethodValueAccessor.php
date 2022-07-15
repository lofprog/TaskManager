<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Hydrator\Accessor;

use ReflectionException;
use ReflectionObject;

final class MethodValueAccessor implements ValueAccessorInterface
{
    public function __construct(
        private readonly string $methodName
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function getValue(?object $object = null): mixed
    {
        $reflection = new ReflectionObject($object);
        return $reflection->getMethod($this->methodName)->invoke($object);
    }
}
