<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308142311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ugame DROP FOREIGN KEY FK_F57F7632F59E604A');
        $this->addSql('ALTER TABLE ugame DROP FOREIGN KEY FK_F57F7632E72BCFA4');
        $this->addSql('DROP TABLE ugame');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ugame (id INT AUTO_INCREMENT NOT NULL, team1_id INT DEFAULT NULL, team2_id INT DEFAULT NULL, datetime DATETIME NOT NULL, INDEX IDX_F57F7632E72BCFA4 (team1_id), INDEX IDX_F57F7632F59E604A (team2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ugame ADD CONSTRAINT FK_F57F7632F59E604A FOREIGN KEY (team2_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE ugame ADD CONSTRAINT FK_F57F7632E72BCFA4 FOREIGN KEY (team1_id) REFERENCES team (id)');
    }
}
