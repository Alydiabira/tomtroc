<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015194028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des champs pseudo, phone_number, message, avatar et created_at à symfony_demo_user';
    }

    public function up(Schema $schema): void
    {
        // Ajout des colonnes avec valeur par défaut pour created_at
        $this->addSql("
            ALTER TABLE symfony_demo_user
            ADD pseudo VARCHAR(50) DEFAULT NULL,
            ADD phone_number VARCHAR(20) DEFAULT NULL,
            ADD message LONGTEXT DEFAULT NULL,
            ADD avatar VARCHAR(255) DEFAULT NULL,
            ADD created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE symfony_demo_user
            DROP pseudo,
            DROP phone_number,
            DROP message,
            DROP avatar,
            DROP created_at
        ");
    }
}
