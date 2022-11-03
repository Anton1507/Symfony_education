<?php

namespace App\Exceptions;
use RuntimeException;

class SubscriberAlreadyExistEmail extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Subscribe already exists ');
    }
}