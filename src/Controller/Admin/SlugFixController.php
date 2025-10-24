<?php

namespace App\Controller\Admin;

// src/Controller/Admin/SlugFixController.php (temporaire)
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;

class SlugFixController extends AbstractController
{
    #[Route('/admin/fix-slugs', name: 'admin_fix_slugs')]
    public function fix(UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $users = $userRepository->findAll();
        $count = 0;

        foreach ($users as $user) {
            if (!$user->getSlug()) {
                $user->generateSlug();
                $count++;
            }
        }

        $em->flush();

        return new Response("Slugs générés pour {$count} utilisateurs.");
    }
}
