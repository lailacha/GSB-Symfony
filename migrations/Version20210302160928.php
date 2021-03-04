<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302160928 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE Fichefrais CHANGE id id INT NOT NULL, CHANGE idVisiteur idVisiteur CHAR(4) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD88A8D81D06ADE3 ON Fichefrais (idVisiteur)');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP INDEX IDX_4C9509AB1D06ADE3, ADD UNIQUE INDEX UNIQ_4C9509AB1D06ADE3 (idVisiteur)');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait CHANGE mois mois VARCHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_DD88A8D81D06ADE3 ON Fichefrais');
        $this->addSql('ALTER TABLE Fichefrais CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE idVisiteur idVisiteur CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP INDEX UNIQ_4C9509AB1D06ADE3, ADD INDEX IDX_4C9509AB1D06ADE3 (idVisiteur)');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait CHANGE mois mois CHAR(6) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
