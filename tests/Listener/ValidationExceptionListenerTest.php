<?php

namespace App\Tests\Listener;

use App\Exceptions\ValidationException;
use App\Listener\ValidationExceptionListener;
use App\Model\ErrorResponse;
use App\Model\ErrorValidationDetails;
use App\Tests\AbstractClassTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionListenerTest extends AbstractClassTest
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    public function testInvokeSkippedWhenValidationException(): void
    {
        $this->serializer->expects($this->never())
            ->method('serialize');

        $event = $this->createExceptionEvent(new \Exception());
        (new ValidationExceptionListener($this->serializer))($event);
    }

    public function testInvoke(): void
    {
        $serialized = json_encode([
            'message' => 'validation failed',
            'details' => [
                'violations' => [
                    ['field' => 'name', 'message' => 'error'],
                ],
            ],
        ]);
        $event = $this->createExceptionEvent(new ValidationException(
            new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'name', null),
            ])
        ));
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($this->callback(function (ErrorResponse $response) {
                $details = $response->getDetails();
                if (!($details instanceof ErrorValidationDetails)) {
                    return false;
                }
                $violations = $details->getViolations();
                if (1 !== count($violations) || 'validation failed' !== $response->getMessage()) {
                    return false;
                }

                return 'name' === $violations[0]->getField() && 'error' === $violations[0]->getMessage();
            }),
                JsonEncoder::FORMAT
            )
            ->willReturn($serialized);
        (new ValidationExceptionListener($this->serializer))($event);
        $this->assertResponse(Response::HTTP_BAD_REQUEST, $serialized, $event->getResponse());
    }
}
