<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316190030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Usesrs (id CHAR(4) NOT NULL, nom CHAR(30) DEFAULT NULL, prenom CHAR(30) DEFAULT NULL, login CHAR(20) DEFAULT NULL, mdp CHAR(255) DEFAULT NULL, adresse CHAR(30) DEFAULT NULL, cp CHAR(5) DEFAULT NULL, ville CHAR(30) DEFAULT NULL, dateEmbauche DATE DEFAULT NULL, roles LONGTEXT DEFAULT \'[\'\'ROLE_USER\'\']\' NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE Fichefrais DROP FOREIGN KEY FK_DD88A8D81D06ADE3');
        $this->addSql('ALTER TABLE Fichefrais ADD CONSTRAINT FK_DD88A8D81D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP FOREIGN KEY FK_4C9509AB1D06ADE3');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait ADD CONSTRAINT FK_4C9509AB1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Users CHANGE id id CHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE ligne_frais_forfait DROP FOREIGN KEY FK_BD293ECF7F72333D');
        $this->addSql('ALTER TABLE ligne_frais_forfait ADD CONSTRAINT FK_BD293ECF7F72333D FOREIGN KEY (visiteur_id) REFERENCES Users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Usesrs');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Fichefrais DROP FOREIGN KEY FK_DD88A8D81D06ADE3');
        $this->addSql('ALTER TABLE Fichefrais ADD CONSTRAINT FK_DD88A8D81D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES Visiteur (id)');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP FOREIGN KEY FK_4C9509AB1D06ADE3');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait ADD CONSTRAINT FK_4C9509AB1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES Visiteur (id)');
        $this->addSql('ALTER TABLE Users CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ligne_frais_forfait DROP FOREIGN KEY FK_BD293ECF7F72333D');
        $this->addSql('ALTER TABLE ligne_frais_forfait ADD CONSTRAINT FK_BD293ECF7F72333D FOREIGN KEY (visiteur_id) REFERENCES Visiteur (id)');
    }
}
