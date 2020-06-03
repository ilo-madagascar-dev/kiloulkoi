<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603032927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces CHANGE vehicule_id vehicule_id INT DEFAULT NULL, CHANGE immobilier_id immobilier_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD parent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346683D8E604F FOREIGN KEY (parent) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_3AF346683D8E604F ON categories (parent)');
        $this->addSql('ALTER TABLE immobilier CHANGE titre titre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces CHANGE vehicule_id vehicule_id INT DEFAULT NULL, CHANGE immobilier_id immobilier_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346683D8E604F');
        $this->addSql('DROP INDEX IDX_3AF346683D8E604F ON categories');
        $this->addSql('ALTER TABLE categories DROP parent');
        $this->addSql('ALTER TABLE immobilier CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE vehicule CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
