<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[AsCommand(
    name: 'app:generate-user-slugs',
    description: 'Génère les slugs manquants pour les utilisateurs',
)]
class GenerateUserSlugsCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $slugger = new AsciiSlugger();
        $users = $this->userRepository->findAll();
        $count = 0;

        foreach ($users as $user) {
            if (!$user->getSlug()) {
                $slug = strtolower($slugger->slug($user->getPseudo() ?? $user->getUsername() ?? $user->getFullName() ?? 'utilisateur'));
                $user->setSlug($slug);
                $count++;
            }
        }

        $this->em->flush();

        $output->writeln("✅ Slugs générés pour {$count} utilisateur(s).");
        return Command::SUCCESS;
    }
}
