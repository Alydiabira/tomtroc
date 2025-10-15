<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015201543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE symfony_demo_user ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE symfony_demo_user ADD CONSTRAINT FK_8FB094A17E3C61F9 FOREIGN KEY (owner_id) REFERENCES symfony_demo_user (id)');
        $this->addSql('CREATE INDEX IDX_8FB094A17E3C61F9 ON symfony_demo_user (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE symfony_demo_user DROP FOREIGN KEY FK_8FB094A17E3C61F9');
        $this->addSql('DROP INDEX IDX_8FB094A17E3C61F9 ON symfony_demo_user');
        $this->addSql('ALTER TABLE symfony_demo_user DROP owner_id');
    }
}
