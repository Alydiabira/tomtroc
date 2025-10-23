<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/livres')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_index')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/{id}', name: 'book_show', requirements: ['id' => '\d+'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/ajouter', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book->setOwner($this->getUser());

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', '📘 Livre ajouté avec succès !');

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'book_edit')]
    public function edit(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        if ($book->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Tu ne peux modifier que tes propres livres.');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', '📘 Livre mis à jour !');

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'book_delete', methods: ['POST'])]
    public function delete(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        if ($book->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Tu ne peux supprimer que tes propres livres.');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $em->remove($book);
            $em->flush();

            $this->addFlash('danger', '📕 Livre supprimé.');
        }

        return $this->redirectToRoute('book_index');
    }

    #[Route('/{id}/confirmer-suppression', name: 'book_confirm_delete')]
    public function confirmDelete(Book $book): Response
    {
        if ($book->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('book/delete.html.twig', [
            'book' => $book,
        ]);
    }
}
