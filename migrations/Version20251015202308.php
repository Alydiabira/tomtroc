<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015202308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la colonne owner_id dans Book et création de la contrainte vers User';
    }

    public function up(Schema $schema): void
    {
        // Ajoute la colonne owner_id dans book
        $this->addSql('ALTER TABLE book ADD owner_id INT DEFAULT NULL');

        // Ajoute la contrainte et l’index
        $this->addSql('CREATE INDEX IDX_CBE5A3317E3C61F9 ON book (owner_id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3317E3C61F9 FOREIGN KEY (owner_id) REFERENCES symfony_demo_user (id)');
    }

    public function down(Schema $schema): void
    {
        // Supprime la contrainte et l’index
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3317E3C61F9');
        $this->addSql('DROP INDEX IDX_CBE5A3317E3C61F9 ON book');

        // Supprime la colonne owner_id
        $this->addSql('ALTER TABLE book DROP COLUMN owner_id');
    }
}
