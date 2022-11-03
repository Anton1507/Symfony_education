<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use RuntimeException;


class BookNotFountException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book  not found ');
    }
}
