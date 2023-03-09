<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307235243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiement_produits (paiement_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_748A27B62A4C4478 (paiement_id), INDEX IDX_748A27B6CD11A2CF (produits_id), PRIMARY KEY(paiement_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paiement_produits ADD CONSTRAINT FK_748A27B62A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paiement_produits ADD CONSTRAINT FK_748A27B6CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement_produits DROP FOREIGN KEY FK_748A27B62A4C4478');
        $this->addSql('ALTER TABLE paiement_produits DROP FOREIGN KEY FK_748A27B6CD11A2CF');
        $this->addSql('DROP TABLE paiement_produits');
    }
}
