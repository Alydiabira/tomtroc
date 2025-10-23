<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/{_locale}', name: 'homepage')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBy([], ['createdAt' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'books' => $books,
        ]);
    }
}
