<?php

namespace App\Command;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(name: 'app:fix-book-slugs')]
class FixBookSlugsCommand extends Command
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $em,
        private SluggerInterface $slugger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
{
    $books = $this->bookRepository->findAll();

    foreach ($books as $book) {
        $owner = $book->getOwner();

        if ($owner) {
            $slug = $owner->getPseudo() ?? $owner->getUsername() ?? 'unknown';
            $book->setSlug($slug);
            $output->writeln("✅ Slug défini pour « {$book->getTitle()} » → {$slug}");
        } else {
            $book->setSlug('unknown');
            $output->writeln("⚠️ Aucun propriétaire pour « {$book->getTitle()} » → slug = unknown");
        }
    }

    $this->em->flush();
    $output->writeln('🎉 Tous les slugs des livres ont été alignés avec ceux des propriétaires.');
    return Command::SUCCESS;
}

}
