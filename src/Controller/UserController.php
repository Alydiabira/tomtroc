<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller used to manage current user. The #[CurrentUser] attribute
 * tells Symfony to inject the currently logged user into the given argument.
 * It can only be used in controllers and it's an alternative to the
 * $this->getUser() method, which still works inside controllers.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
#[Route('/profile'), IsGranted(User::ROLE_USER)]
final class UserController extends AbstractController
{
    #[Route('/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', '✅ Profil mis à jour avec succès !');
            return $this->redirectToRoute('user_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/change-password', name: 'user_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
    ): Response {
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // The logout method applies an automatic protection against CSRF attacks;
            // it's explicitly disabled here because the form already has a CSRF token validated.
            return $security->logout(validateCsrfToken: false) ?? $this->redirectToRoute('homepage');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('', name: 'user_account', methods: ['GET'])]
    public function account(#[CurrentUser] User $user): Response
    {
        return $this->render('user/account.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/avatar', name: 'user_avatar', methods: ['POST'])]
    public function updateAvatar(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $file = $request->files->get('avatar');
        if ($file) {
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'), $filename);
            $user->setAvatar($filename);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_account');
    }

    #[Route('/books', name: 'user_books', methods: ['GET'])]
    public function books(#[CurrentUser] User $user): Response
    {
        return $this->render('user/books.html.twig', [
            'books' => $user->getBooks(),
        ]);
    }

    #[Route('/books/{id}/delete', name: 'user_book_delete', methods: ['POST'])]
    public function deleteBook(
        #[CurrentUser] User $user,
        Book $book,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($book->getOwner() !== $user) {
            throw $this->createAccessDeniedException('Tu ne peux supprimer que tes propres livres.');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
            $this->addFlash('danger', '📕 Livre supprimé.');
        }

        return $this->redirectToRoute('user_edit');
    }


}
