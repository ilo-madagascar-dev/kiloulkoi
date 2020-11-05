<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201104083901 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE moyen_paiement ADD card_type VARCHAR(50) DEFAULT NULL, ADD currency VARCHAR(50) DEFAULT NULL, ADD owner_id INT DEFAULT NULL, ADD card_number VARCHAR(50) DEFAULT NULL, ADD cardexp_date VARCHAR(50) DEFAULT NULL, ADD card_cvx VARCHAR(50) DEFAULT NULL, ADD creat_date DATETIME DEFAULT NULL, ADD card_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE moyen_paiement DROP card_type, DROP currency, DROP owner_id, DROP card_number, DROP cardexp_date, DROP card_cvx, DROP creat_date, DROP card_id');
    }
}
