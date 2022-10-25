<?php

namespace App\Service\ExceptionHandler;

use InvalidArgumentException;

class ExceptionMappingResolver
{
    /**
     * @var ExceptionMapping[]
     */
    private array $mappings = [];

    public function __construct(array $mappings)
    {
        foreach ($mappings as $class => $mapping) {
            if (empty($mapping['code'])) {
                throw new InvalidArgumentException('code is mandatory for class'.$class);
            }
            $this->addMappings($class, $mapping['code'], $mapping['hidden'] ?? true, $mapping['loggable'] ?? false);
        }
    }

    public function resolve(string $throwableClass): ?ExceptionMapping
    {
        $foundMapping = null;
        var_dump($throwableClass);
        foreach ($this->mappings as $class => $mapping) {
            if ($throwableClass === $class || is_subclass_of($throwableClass, $class)) {
                $foundMapping = $mapping;
                break;
            }
        }

        return $foundMapping;
    }

    private function addMappings(string $class, int $code, bool $hidden, bool $loggable): void
    {
        $this->mappings[$class] = new ExceptionMapping($code, $hidden, $loggable);
    }
}
