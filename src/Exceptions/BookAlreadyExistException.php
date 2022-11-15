<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use RuntimeException;


class BookAlreadyExistException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book  not found ');
    }
}
