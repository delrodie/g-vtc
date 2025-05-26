<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522025436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE chauffeur (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, permis VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE conduire (id INT AUTO_INCREMENT NOT NULL, chauffeur_id INT DEFAULT NULL, vehicule_id INT DEFAULT NULL, date_debut_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', montant_recette INT DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, INDEX IDX_23C2D53885C0B3BE (chauffeur_id), INDEX IDX_23C2D5384A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, connexion INT DEFAULT NULL, last_connected_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, marque_id INT DEFAULT NULL, immatriculation VARCHAR(255) NOT NULL, couleur VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, occupe TINYINT(1) DEFAULT NULL, INDEX IDX_292FFF1D4827B9B2 (marque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conduire ADD CONSTRAINT FK_23C2D53885C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES chauffeur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conduire ADD CONSTRAINT FK_23C2D5384A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE conduire DROP FOREIGN KEY FK_23C2D53885C0B3BE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conduire DROP FOREIGN KEY FK_23C2D5384A4A3511
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D4827B9B2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chauffeur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE conduire
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE marque
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vehicule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
