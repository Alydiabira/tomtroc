<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251030092219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration nettoyée : suppression de l’index unique sur le champ slug de la table book';
    }

    public function up(Schema $schema): void
    {
        // ❌ Index unique supprimé pour éviter les conflits de doublons
        // $this->addSql('CREATE UNIQUE INDEX slug_unique ON book (slug)');
    }

    public function down(Schema $schema): void
    {
        // ❌ Ne pas tenter de supprimer un index qui n’a pas été créé ici
        // $this->addSql('DROP INDEX slug_unique ON book');
    }
}
