<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livres/echanges')]
class BookExchangeController extends AbstractController
{
    #[Route('/', name: 'book_exchange_index')]
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        $searchTerm = $request->query->get('q');

        $books = $searchTerm
            ? $bookRepository->findAvailableByTitle($searchTerm)
            : $bookRepository->findBy(['available' => true], ['createdAt' => 'DESC']);

        return $this->render('book_exchange/index.html.twig', [
            'books' => $books,
            'searchTerm' => $searchTerm,
        ]);
    }
}
