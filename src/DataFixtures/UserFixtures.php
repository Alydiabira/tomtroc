<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['Aly Diabira', 'aly', 'aly@example.com', ['ROLE_ADMIN']],
            ['Tom Troc', 'tom', 'tom@example.com', ['ROLE_USER']],
            ['Nathalie Lecture', 'nathalie', 'nathalie@example.com', ['ROLE_USER']],
            ['Sasa94', 'sasa94', 'sasa@example.com', ['ROLE_USER']],
        ];

        foreach ($users as [$fullName, $username, $email, $roles]) {
            // Skip si déjà en base
            if ($this->entityManager->getRepository(User::class)->findOneBy(['username' => $username])) {
                continue;
            }

            $user = new User();
            $user->setFullName($fullName);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'motdepasse123'));

            $manager->persist($user);
            $this->addReference($username, $user); // utile pour les fixtures liées
        }

        $manager->flush();
    }
}
