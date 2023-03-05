<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303225745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE mode_paiement mode_paiement TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE panier ADD tot DOUBLE PRECISION NOT NULL, ADD qt INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE mode_paiement mode_paiement VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panier DROP tot, DROP qt');
    }
}
