<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use RuntimeException;


class BookCategoryNotFountException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book category not found ');
    }
}
