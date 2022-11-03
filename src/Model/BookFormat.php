<?php

namespace App\Model;

class BookFormat
{
    private int $id;
    private string $title;
    private ?string $description;
    private ?string $comment;
    private float $price;
    private ?int $discountPrice;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BookFormat
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): BookFormat
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): BookFormat
    {
        $this->description = $description;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): BookFormat
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): BookFormat
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscountPrice(): ?int
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(?int $discountPrice): BookFormat
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }
}
