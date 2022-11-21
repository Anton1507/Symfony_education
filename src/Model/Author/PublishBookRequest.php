<?php

namespace App\Model\Author;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    private DateTimeInterface $date;


    #[NotBlank]
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): PublishBookRequest
    {
        $this->date = $date;

        return $this;
    }
}
