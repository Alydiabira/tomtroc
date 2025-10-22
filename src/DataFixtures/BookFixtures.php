<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;


class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        $users = [
            'aly' => $this->getReference('aly', User::class),
            'tom' => $this->getReference('tom', User::class),
            'nathalie' => $this->getReference('nathalie', User::class),
            'sasa94' => $this->getReference('sasa94', User::class),
        ];


        $genres = ['Roman', 'BD', 'Essai', 'Thriller', 'Fantasy', 'Science-fiction', 'Développement personnel'];

        foreach ($users as $ref => $user) {
            for ($i = 0; $i < 3; $i++) {
                $book = new Book();
                $book->setTitle($faker->sentence(3));
                $book->setAuthor($faker->name);
                $book->setDescription($faker->realTextBetween(100, 200));
                $book->setGenre($faker->randomElement($genres));
                $book->setOwner($user);
                $book->setCreatedAt(\DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-1 week', 'now')
                ));


                $manager->persist($book);
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

