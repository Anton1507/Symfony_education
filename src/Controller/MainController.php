<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(private BookRepository $bookRepository, private EntityManagerInterface $en)
    {
    }

    #[Route('/newBook')]
    public function newBook(): Response
    {
        $book = new Book();
        $book->setTitle('Tom Soer');
        $this->en->persist($book);
        $this->en->flush();

        return $this->json($book);
    }

    #[Route('/')]
    public function books(): Response
    {
        $books = $this->bookRepository->findAll();

        return $this->json($books);
    }
}
