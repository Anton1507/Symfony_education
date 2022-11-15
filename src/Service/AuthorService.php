<?php

namespace App\Service;

use App\Entity\Book;
use App\Exceptions\BookAlreadyExistException;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $em,
        private BookRepository $bookRepository,
        private SluggerInterface $slugger,
        private Security $security
    ) {
    }

    public function getBooks(): BookListResponse
    {
        $user = $this->security->getUser();

        return new BookListResponse(
            array_map([$this, 'map'], $this->bookRepository->findUserBooks($user))
        );
    }

    public function deleteBooks(int $id): void
    {
        $user = $this->security->getUser();
        $book = $this->bookRepository->getUserBookById($id, $user);
        $this->em->remove($book);
        $this->em->flush();
    }

    public function createBook(CreateBookRequest $request): void
    {
        $slug = $this->slugger->slug($request->getTitle());
        if ($this->bookRepository->existBySlug($slug)) {
            throw new BookAlreadyExistException();
        }

        $book = (new Book())
            ->setTitle($request->getTitle())
            ->setSlug($slug)
            ->setUser($this->security->getUser());
        $this->em->persist($book);

    }

    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage());
    }
}
