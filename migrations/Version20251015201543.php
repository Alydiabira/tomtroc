<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015201543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Suppression du champ erroné owner_id dans User';
    }

    public function up(Schema $schema): void
    {
        // Le champ owner_id n’a plus lieu d’être dans symfony_demo_user
        // On ne fait rien ici car la correction est gérée dans une autre migration
    }

    public function down(Schema $schema): void
    {
        // Si rollback, on peut réintroduire le champ erroné (optionnel)
        $this->addSql('ALTER TABLE symfony_demo_user ADD owner_id INT DEFAULT NULL');
    }
}
