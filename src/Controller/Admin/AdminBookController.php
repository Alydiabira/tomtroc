<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\Type\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/book')]
class AdminBookController extends AbstractController
{
    #[Route('/{id}/edit', name: 'admin_book_edit')]
    public function edit(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('user_books');
        }

        return $this->render('admin/book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_book_delete', methods: ['POST'])]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('user_books');
    }
}
