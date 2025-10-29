<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/profil')]
class PublicProfileController extends AbstractController
{
#[Route('/{slug}', name: 'public_profile')]
public function show(UserRepository $userRepository, string $slug): Response
{
    $user = $userRepository->findOneBy(['slug' => $slug]);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur introuvable');
    }

    // 🔒 Si l'utilisateur connecté accède à son propre profil, rediriger vers "Mon compte"
    if ($this->getUser() && $this->getUser()->getId() === $user->getId()) {
        return $this->redirectToRoute('user_account');
    }

    $otherUsers = $userRepository->createQueryBuilder('u')
        ->where('u.slug != :slug')
        ->setParameter('slug', $slug)
        ->setMaxResults(5)
        ->orderBy('u.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

    return $this->render('user/public_profile.html.twig', [
        'user' => $user,
        'otherUsers' => $otherUsers,
    ]);
}

}
