<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFullName('Aly Diabira');
        $user->setUsername('aly');
        $user->setEmail('aly@example.com');
        $user->setRoles(['ROLE_ADMIN']); // ou ['ROLE_USER']

        // 🔐 Hash du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'motdepasse123');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}
