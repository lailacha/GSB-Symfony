<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210214162625 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE users ADD prenom CHAR(30) DEFAULT NULL, ADD adresse CHAR(30) DEFAULT NULL, ADD cp CHAR(5) DEFAULT NULL, ADD ville CHAR(30) DEFAULT NULL, ADD dateEmbauche DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `users` DROP prenom, DROP adresse, DROP cp, DROP ville, DROP dateEmbauche');
    }
}
