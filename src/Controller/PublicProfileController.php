<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class PublicProfileController extends AbstractController
{
    #[Route('/{id}', name: 'public_profile')]
    public function show(User $user): Response
    {
        return $this->render('user/public_profile.html.twig', [
            'user' => $user,
        ]);
    }
}
