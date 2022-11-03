<?php

namespace App\Tests\ArgumentResolver;

use App\ArgumentResolver\RequestBodyArgumentResolver;
use App\Attribute\RequestBody;
use App\Exceptions\RequestBodyConvertException;
use App\Exceptions\ValidationException;
use App\Tests\AbstractClassTest;
use Exception;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestBodyArgumentResolverTest extends AbstractClassTest
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testSupports(): void
    {
        $meta = new ArgumentMetadata('Test', null, false, false, null, false, [
            new RequestBody(),
        ]);

        $this->assertTrue($this->createResolver()->supports(new Request(), $meta));
    }

    public function testNotSupports(): void
    {
        $meta = new ArgumentMetadata('Test', null, false, false, null);
        $this->assertFalse($this->createResolver()->supports(new Request(), $meta));
    }

    public function testResolveThrowsWhenDeserialize(): void
    {
        $this->expectException(RequestBodyConvertException::class);
        $request = new Request([], [], [], [], [], [], 'testing content');
        $meta = new ArgumentMetadata('Test', stdClass::class, false, false, null);
        $this->serializer->expects($this->once())
             ->method('deserialize')
            ->with('testing content', stdClass::class, JsonEncoder::FORMAT)
            ->willThrowException(new Exception());
        $this->createResolver()->resolve($request, $meta)->next();
    }

    public function testResolveThrowsWhenNotValidate(): void
    {
        $this->expectException(ValidationException::class);
        $body = ['test' => true];
        $encodedBody = json_encode($body);
        $request = new Request([], [], [], [], [], [], $encodedBody);
        $meta = new ArgumentMetadata('Test', stdClass::class, false, false, null);
        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedBody, stdClass::class, JsonEncoder::FORMAT)
            ->willReturn($body);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'something', null),
            ]));
        $this->createResolver()->resolve($request, $meta)->next();
    }

    public function testResolve(): void
    {
        $body = ['test' => true];
        $encodedBody = json_encode($body);
        $request = new Request([], [], [], [], [], [], $encodedBody);
        $meta = new ArgumentMetadata('Test', stdClass::class, false, false, null);
        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedBody, stdClass::class, JsonEncoder::FORMAT)
            ->willReturn($body);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn(new ConstraintViolationList([]));

        $actual = $this->createResolver()->resolve($request, $meta);

        $this->assertEquals($body, $actual->current());
    }

    private function createResolver(): RequestBodyArgumentResolver
    {
        return new RequestBodyArgumentResolver($this->serializer, $this->validator);
    }

}
