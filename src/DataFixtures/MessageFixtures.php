<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MessageFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Création de deux utilisateurs
        $user1 = new User();
        $user1->setFullName('Aly Diabira');
        $user1->setUsername('aly');
        $user1->setEmail('aly@example.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'motdepasse123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFullName('Léna Dubois');
        $user2->setUsername('lena');
        $user2->setEmail('lena@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'motdepasse456'));
        $manager->persist($user2);

        // Création d'une conversation
        $conversation = new Conversation();
        $conversation->setUser1($user1);
        $conversation->setUser2($user2);
        $conversation->setLastMessageAt(new \DateTimeImmutable('-1 hour'));
        $manager->persist($conversation);

        // Création de messages
        $message1 = new Message();
        $message1->setSender($user1);
        $message1->setRecipient($user2);
        $message1->setConversation($conversation);
        $message1->setContent("Salut Léna, tu es dispo pour échanger ?");
        $message1->setCreatedAt(new \DateTimeImmutable('-50 minutes'));
        $manager->persist($message1);

        $message2 = new Message();
        $message2->setSender($user2);
        $message2->setRecipient($user1);
        $message2->setConversation($conversation);
        $message2->setContent("Coucou Aly ! Oui, je suis dispo maintenant 😊");
        $message2->setCreatedAt(new \DateTimeImmutable('-45 minutes'));
        $manager->persist($message2);

        $manager->flush();
    }
}
