<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108090355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, contrat INT DEFAULT NULL, client INT DEFAULT NULL, INDEX IDX_FE866410670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, secteur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_369ECA329F7E4405 (secteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteur (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom_secteur VARCHAR(255) NOT NULL, INDEX IDX_8045251FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nom VARCHAR(45) NOT NULL, prenom VARCHAR(45) NOT NULL, adresse VARCHAR(255) NOT NULL, mobile INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA329F7E4405 FOREIGN KEY (secteur_id) REFERENCES secteur (id)');
        $this->addSql('ALTER TABLE secteur ADD CONSTRAINT FK_8045251FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410670C757F');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA329F7E4405');
        $this->addSql('ALTER TABLE secteur DROP FOREIGN KEY FK_8045251FA76ED395');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE secteur');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
