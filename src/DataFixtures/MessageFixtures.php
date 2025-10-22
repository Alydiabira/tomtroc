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
            'aly' => $this->getReference('user_aly'),
            'tom' => $this->getReference('user_tom'),
            'nathalie' => $this->getReference('user_nathalie'),
            'sasa94' => $this->getReference('user_sasa94'),
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
            $conversation->setLastMessageAt(new \DateTimeImmutable('-' . random_int(1, 5) . ' hours'));

            $manager->persist($conversation);

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
                $message->setCreatedAt((new \DateTimeImmutable())->modify('-' . ($i * 10) . ' minutes'));

                $manager->persist($message);
            }
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
