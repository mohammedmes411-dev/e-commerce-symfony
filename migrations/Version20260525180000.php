<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour créer le schéma de base de données en français.
 */
final class Version20260525180000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables en français pour MarocShop : categorie, produit, panier, ligne_panier, utilisateur.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, nombre_produits INT DEFAULT NULL, exemples_produits LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie_id INT NOT NULL, INDEX IDX_PRODUIT_CATEGORIE (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_panier (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, produit_id INT DEFAULT NULL, panier_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_LIGNE_PANIER_PRODUIT (produit_id), INDEX IDX_LIGNE_PANIER_PANIER (panier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom_complet VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_PRODUIT_CATEGORIE FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_LIGNE_PANIER_PRODUIT FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_LIGNE_PANIER_PANIER FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_PRODUIT_CATEGORIE');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_LIGNE_PANIER_PRODUIT');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_LIGNE_PANIER_PANIER');
        
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
