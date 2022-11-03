<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractClassTest;
use InvalidArgumentException;
use LogicException;

class ExceptionMappingResolverTest extends AbstractClassTest
{
    public function testThromsExceptionsOnEmptyCode()
    {
        $this->expectException(InvalidArgumentException::class);
        new ExceptionMappingResolver(['someClass' => ['hidden' => true, 'logger' => false]]);
    }

    public function testResolveReturnsNullWhenNotFound()
    {
        $resolver = new ExceptionMappingResolver([]);
        $this->assertNull($resolver->resolve(InvalidArgumentException::class));
    }

    public function testResolvesClassItself(): void
    {
        $resolver = new ExceptionMappingResolver([InvalidArgumentException::class => ['code' => 400]]);
        $mapping = $resolver->resolve(InvalidArgumentException::class);
        $this->assertEquals(400, $mapping->getCode());
        $this->assertFalse($mapping->isLoggable());
        $this->assertTrue($mapping->isHidden());
    }

    public function testResolvesSubClass(): void
    {
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 500]]);
        $mapping = $resolver->resolve(InvalidArgumentException::class);
        $this->assertEquals(500, $mapping->getCode());
    }

    public function testResolvesHidden(): void
    {
        $resolver = new ExceptionMappingResolver([LogicException::class => ['code' => 500, 'hidden' => false]]);
        $mapping = $resolver->resolve(LogicException::class);
        $this->assertFalse($mapping->isHidden());
    }

    public function testResolvesLoggable(): void
    {
        $resolver = new ExceptionMappingResolver([LogicException::class => ['code' => 500, 'loggable' => true]]);
        $mapping = $resolver->resolve(LogicException::class);
        $this->assertTrue($mapping->isLoggable());
    }
}
