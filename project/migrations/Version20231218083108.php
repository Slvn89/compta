<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218083108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pharmacie ADD secteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacie ADD CONSTRAINT FK_5FC194349F7E4405 FOREIGN KEY (secteur_id) REFERENCES secteur (id)');
        $this->addSql('CREATE INDEX IDX_5FC194349F7E4405 ON pharmacie (secteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pharmacie DROP FOREIGN KEY FK_5FC194349F7E4405');
        $this->addSql('DROP INDEX IDX_5FC194349F7E4405 ON pharmacie');
        $this->addSql('ALTER TABLE pharmacie DROP secteur_id');
    }
}
