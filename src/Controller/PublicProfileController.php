<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class PublicProfileController extends AbstractController
{
  #[Route('/profil/{slug}', name: 'public_profile')]
public function show(UserRepository $userRepository, string $slug): Response
{
    $user = $userRepository->findOneBy(['slug' => $slug]);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur introuvable');
    }

    return $this->render('user/public_profile.html.twig', [
        'user' => $user,
    ]);
}


}
