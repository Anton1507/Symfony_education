<?php

namespace App\Exceptions;

use RuntimeException;

class UserAlreadyExistException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('user already exist');
    }
}
