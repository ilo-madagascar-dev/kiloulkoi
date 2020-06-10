<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610063123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE titre titre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE categories CHANGE parent parent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8805AB2F');
        $this->addSql('DROP INDEX UNIQ_5E9E89CB8805AB2F ON location');
        $this->addSql('ALTER TABLE location DROP annonce_id');
        $this->addSql('ALTER TABLE statut_location CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule CHANGE marque marque VARCHAR(255) DEFAULT NULL, CHANGE modele modele VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categories CHANGE parent parent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonces (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB8805AB2F ON location (annonce_id)');
        $this->addSql('ALTER TABLE statut_location CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE vehicule CHANGE marque marque VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modele modele VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
