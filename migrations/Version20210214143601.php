<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210214143601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Etat (id CHAR(2) NOT NULL, libelle VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Fichefrais (mois CHAR(6) NOT NULL, nbJustificatifs INT DEFAULT NULL, montantValide NUMERIC(10, 2) DEFAULT NULL, dateModif DATE DEFAULT NULL, idEtat CHAR(2) DEFAULT NULL, idVisiteur CHAR(4) NOT NULL, INDEX idEtat (idEtat), INDEX IDX_DD88A8D81D06ADE3 (idVisiteur), PRIMARY KEY(mois, idVisiteur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FraisForfait (id CHAR(3) NOT NULL, libelle CHAR(20) DEFAULT NULL, montant NUMERIC(5, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE LigneFraisHorsForfait (id INT AUTO_INCREMENT NOT NULL, mois CHAR(6) DEFAULT NULL, libelle VARCHAR(100) DEFAULT NULL, date DATE DEFAULT NULL, montant NUMERIC(10, 2) DEFAULT NULL, idVisiteur CHAR(4) DEFAULT NULL, INDEX idVisiteur (idVisiteur, mois), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Visiteur (id CHAR(4) NOT NULL, nom CHAR(30) DEFAULT NULL, prenom CHAR(30) DEFAULT NULL, login CHAR(20) DEFAULT NULL, mdp CHAR(20) DEFAULT NULL, adresse CHAR(30) DEFAULT NULL, cp CHAR(5) DEFAULT NULL, ville CHAR(30) DEFAULT NULL, dateEmbauche DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Fichefrais ADD CONSTRAINT FK_DD88A8D82637A9FC FOREIGN KEY (idEtat) REFERENCES Etat (id)');
        $this->addSql('ALTER TABLE Fichefrais ADD CONSTRAINT FK_DD88A8D81D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES Visiteur (id)');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait ADD CONSTRAINT FK_4C9509AB1D06ADE3D6B08CB7 FOREIGN KEY (idVisiteur, mois) REFERENCES Fichefrais (idVisiteur, mois)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Fichefrais DROP FOREIGN KEY FK_DD88A8D82637A9FC');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP FOREIGN KEY FK_4C9509AB1D06ADE3D6B08CB7');
        $this->addSql('ALTER TABLE Fichefrais DROP FOREIGN KEY FK_DD88A8D81D06ADE3');
        $this->addSql('DROP TABLE Etat');
        $this->addSql('DROP TABLE Fichefrais');
        $this->addSql('DROP TABLE FraisForfait');
        $this->addSql('DROP TABLE LigneFraisHorsForfait');
        $this->addSql('DROP TABLE Visiteur');
        $this->addSql('DROP TABLE `users`');
    }
}
