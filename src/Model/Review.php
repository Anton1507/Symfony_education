<?php

namespace App\Model;

class Review
{
    private int $id;
    private string $content;
    private string $author;
    private int $rating;
    private int $creatAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Review
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Review
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): Review
    {
        $this->author = $author;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): Review
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatAt(): int
    {
        return $this->creatAt;
    }

    public function setCreatAt(int $creatAt): Review
    {
        $this->creatAt = $creatAt;

        return $this;
    }
}
