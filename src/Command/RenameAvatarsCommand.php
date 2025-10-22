<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:rename-avatars',
    description: 'Renomme les fichiers d’avatars selon le username des utilisateurs.',
)]
class RenameAvatarsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $avatarDir = __DIR__ . '/../../public/images/avatars/';
        if (!is_dir($avatarDir)) {
            $output->writeln("<error>Dossier d’avatars introuvable : $avatarDir</error>");
            return Command::FAILURE;
        }

        $files = scandir($avatarDir);
        $users = $this->em->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $username = $user->getUsername();
            $fullName = strtolower($user->getFullName());

            foreach ($files as $file) {
                if (str_contains($file, $username) || str_contains(strtolower($file), $fullName)) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $newName = $username . '.' . $ext;

                    if ($file === $newName) {
                        $output->writeln("🔹 $file est déjà bien nommé.");
                        break;
                    }

                    rename($avatarDir . $file, $avatarDir . $newName);
                    $output->writeln("✅ $file → $newName");
                    break;
                }
            }
        }

        $output->writeln("<info>✔️ Tous les avatars ont été renommés.</info>");
        return Command::SUCCESS;
    }
}
