<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108110740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD annee_facturation DATETIME DEFAULT NULL, ADD numero_facture VARCHAR(255) DEFAULT NULL, ADD nom_entreprise_vendeur LONGTEXT DEFAULT NULL, ADD adresse_entreprise_vendeur LONGTEXT DEFAULT NULL, ADD telephone_entreprise_vendeur VARCHAR(20) DEFAULT NULL, ADD nom_client_acheteur LONGTEXT DEFAULT NULL, ADD adresse_client_acheteur LONGTEXT DEFAULT NULL, ADD telephone_client_acheteur VARCHAR(20) DEFAULT NULL, ADD sous_total DOUBLE PRECISION DEFAULT NULL, ADD tva DOUBLE PRECISION DEFAULT NULL, ADD total DOUBLE PRECISION DEFAULT NULL, DROP contrat, DROP client');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD contrat INT DEFAULT NULL, ADD client INT DEFAULT NULL, DROP annee_facturation, DROP numero_facture, DROP nom_entreprise_vendeur, DROP adresse_entreprise_vendeur, DROP telephone_entreprise_vendeur, DROP nom_client_acheteur, DROP adresse_client_acheteur, DROP telephone_client_acheteur, DROP sous_total, DROP tva, DROP total');
    }
}
