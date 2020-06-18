<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618112044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_CB988C6FBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_3AF346683D8E604F (parent), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE energie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode (id INT NOT NULL, pointure_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_97CA47ABD5CAF962 (pointure_id), INDEX IDX_97CA47ABFF25611A (taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enfant_mode (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE homme_femme_mode (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE immobilier (id INT NOT NULL, surface INT NOT NULL, nbr_chambre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, statut_location_id INT NOT NULL, annonces_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_5E9E89CB7FE45227 (statut_location_id), INDEX IDX_5E9E89CB4C2885D7 (annonces_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maternite (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointure (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, classe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_location (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, classe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_location (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule (id INT NOT NULL, energie_id INT NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, INDEX IDX_292FFF1DB732A364 (energie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vetement_maternite (id INT NOT NULL, pointure_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_2AC3CB7BD5CAF962 (pointure_id), INDEX IDX_2AC3CB7BFF25611A (taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346683D8E604F FOREIGN KEY (parent) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE mode ADD CONSTRAINT FK_97CA47ABD5CAF962 FOREIGN KEY (pointure_id) REFERENCES pointure (id)');
        $this->addSql('ALTER TABLE mode ADD CONSTRAINT FK_97CA47ABFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE mode ADD CONSTRAINT FK_97CA47ABBF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enfant_mode ADD CONSTRAINT FK_F1E55F9CBF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE homme_femme_mode ADD CONSTRAINT FK_A666DE9CBF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE immobilier ADD CONSTRAINT FK_142D24D2BF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB7FE45227 FOREIGN KEY (statut_location_id) REFERENCES statut_location (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB4C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE maternite ADD CONSTRAINT FK_7E1C52BCBF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DB732A364 FOREIGN KEY (energie_id) REFERENCES energie (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DBF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vetement_maternite ADD CONSTRAINT FK_2AC3CB7BD5CAF962 FOREIGN KEY (pointure_id) REFERENCES pointure (id)');
        $this->addSql('ALTER TABLE vetement_maternite ADD CONSTRAINT FK_2AC3CB7BFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE vetement_maternite ADD CONSTRAINT FK_2AC3CB7BBF396750 FOREIGN KEY (id) REFERENCES annonces (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mode DROP FOREIGN KEY FK_97CA47ABBF396750');
        $this->addSql('ALTER TABLE enfant_mode DROP FOREIGN KEY FK_F1E55F9CBF396750');
        $this->addSql('ALTER TABLE homme_femme_mode DROP FOREIGN KEY FK_A666DE9CBF396750');
        $this->addSql('ALTER TABLE immobilier DROP FOREIGN KEY FK_142D24D2BF396750');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB4C2885D7');
        $this->addSql('ALTER TABLE maternite DROP FOREIGN KEY FK_7E1C52BCBF396750');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BF396750');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DBF396750');
        $this->addSql('ALTER TABLE vetement_maternite DROP FOREIGN KEY FK_2AC3CB7BBF396750');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FBCF5E72D');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346683D8E604F');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DB732A364');
        $this->addSql('ALTER TABLE mode DROP FOREIGN KEY FK_97CA47ABD5CAF962');
        $this->addSql('ALTER TABLE vetement_maternite DROP FOREIGN KEY FK_2AC3CB7BD5CAF962');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB7FE45227');
        $this->addSql('ALTER TABLE mode DROP FOREIGN KEY FK_97CA47ABFF25611A');
        $this->addSql('ALTER TABLE vetement_maternite DROP FOREIGN KEY FK_2AC3CB7BFF25611A');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE energie');
        $this->addSql('DROP TABLE mode');
        $this->addSql('DROP TABLE enfant_mode');
        $this->addSql('DROP TABLE homme_femme_mode');
        $this->addSql('DROP TABLE immobilier');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE maternite');
        $this->addSql('DROP TABLE pointure');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE statut_location');
        $this->addSql('DROP TABLE taille');
        $this->addSql('DROP TABLE type_location');
        $this->addSql('DROP TABLE vehicule');
        $this->addSql('DROP TABLE vetement_maternite');
    }
}
