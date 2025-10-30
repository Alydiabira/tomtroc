<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251024181434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout d’un index unique sur le champ slug de symfony_demo_user (déjà existant, donc retiré)';
    }

    public function up(Schema $schema): void
    {
        // ❌ Supprimé car l’index existe déjà en base
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_8FB094A1989D9B62 ON symfony_demo_user (slug)');
    }

    public function down(Schema $schema): void
    {
        // ❌ Supprimé car on ne doit pas tenter de supprimer un index qui n’a pas été créé ici
        // $this->addSql('DROP INDEX UNIQ_8FB094A1989D9B62 ON symfony_demo_user');
    }
}
