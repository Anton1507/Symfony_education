<?php

namespace App\Model;

class ErrorValidationDetails
{
    /**
     * @var ErrorValidationDetailsItem[]
     */
    private array $violations = [];

    public function addViolations(string $field, string $message): void
    {
        $this->violations[] = new ErrorValidationDetailsItem($field, $message);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

}