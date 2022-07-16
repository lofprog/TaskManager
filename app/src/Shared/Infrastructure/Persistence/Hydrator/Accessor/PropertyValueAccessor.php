<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Hydrator\Accessor;

use ReflectionException;
use ReflectionObject;

final class PropertyValueAccessor implements ValueAccessorInterface
{
    public function __construct(
        private readonly string $propertyName
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function getValue(?object $object = null): mixed
    {
        $reflection = new ReflectionObject($object);
        return $reflection->getProperty($this->propertyName)->getValue($object);
    }
}