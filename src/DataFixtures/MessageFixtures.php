<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        /** @var User[] $users */
        $users = [
            'aly' => $this->getReference('aly', User::class),
            'tom' => $this->getReference('tom', User::class),
            'nathalie' => $this->getReference('nathalie', User::class),
            'sasa94' => $this->getReference('sasa94', User::class),
        ];

        $pairs = [
            ['aly', 'tom'],
            ['aly', 'nathalie'],
            ['aly', 'sasa94'],
            ['tom', 'nathalie'],
        ];

        foreach ($pairs as [$userA, $userB]) {
            $conversation = new Conversation();
            $conversation->setUser1($users[$userA]);
            $conversation->setUser2($users[$userB]);

            $lastMessageTime = null;

            for ($i = 0; $i < 5; $i++) {
                $sender = $i % 2 === 0 ? $users[$userA] : $users[$userB];
                $recipient = $i % 2 === 0 ? $users[$userB] : $users[$userA];

                $message = new Message();
                $message->setSender($sender);
                $message->setRecipient($recipient);
                $message->setConversation($conversation);
                $message->setContent($faker->randomElement([
                    "Tu veux troquer ce livre 📚 ?",
                    "J’ai trouvé un super vinyle 🎶, ça t’intéresse ?",
                    "Dispo pour un échange demain ? 😊",
                    "Je peux passer à Rosny vers 18h 🕕",
                    "Top ton annonce ! Tu cherches quoi en échange ? 🔄",
                    $faker->realTextBetween(40, 120),
                ]));

                $createdAt = (new \DateTimeImmutable())->modify('-' . ($i * 10) . ' minutes');
                $message->setCreatedAt($createdAt);
                $lastMessageTime = $createdAt;

                $manager->persist($message);
            }

            // Mise à jour du champ lastMessageAt avec le dernier message
            $conversation->setLastMessageAt($lastMessageTime);
            $manager->persist($conversation);

            // Ajout d'une référence si tu veux la réutiliser ailleurs
            $this->addReference('conv_' . $userA . '_' . $userB, $conversation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
