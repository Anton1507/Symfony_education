<?php

namespace App\Service;

class Rating
{
    public function __construct(private int $total, private int $rating)
    {
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
}
