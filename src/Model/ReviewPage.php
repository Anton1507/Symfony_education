<?php

namespace App\Model;

class ReviewPage
{
    /**
     * @var Review[]
     */
    private array $items;

    private float $rating;

    private int $page;

    private int $pages;

    private int $perPage;

    private int $total;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): ReviewPage
    {
        $this->items = $items;

        return $this;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating(float $rating): ReviewPage
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): ReviewPage
    {
        $this->page = $page;

        return $this;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): ReviewPage
    {
        $this->pages = $pages;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): ReviewPage
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): ReviewPage
    {
        $this->total = $total;

        return $this;
    }
}
