<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015202308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la relation entre Book et User via owner_id, suppression du champ erroné dans User';
    }

    public function up(Schema $schema): void
    {
        // Vérifie si la colonne owner_id n'existe pas déjà dans book
        // Supprime l'ajout si elle est déjà présente

        // Supprime le champ erroné dans symfony_demo_user
        $this->addSql('ALTER TABLE symfony_demo_user DROP COLUMN owner_id');

        // Ajoute la contrainte et l’index si non existants
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3317E3C61F9 FOREIGN KEY (owner_id) REFERENCES symfony_demo_user (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A3317E3C61F9 ON book (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // Supprime la contrainte et l’index
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3317E3C61F9');
        $this->addSql('DROP INDEX IDX_CBE5A3317E3C61F9 ON book');

       
        // Réintroduit le champ erroné dans User si rollback
        $this->addSql('ALTER TABLE symfony_demo_user ADD owner_id INT DEFAULT NULL');
    }
}
