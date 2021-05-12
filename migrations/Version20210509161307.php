<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509161307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalog_price (id INT AUTO_INCREMENT NOT NULL, catalog_id INT DEFAULT NULL, container_id INT DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_73DB4360CC3C66FC (catalog_id), INDEX IDX_73DB4360BC21F742 (container_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalog_price ADD CONSTRAINT FK_73DB4360CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id)');
        $this->addSql('ALTER TABLE catalog_price ADD CONSTRAINT FK_73DB4360BC21F742 FOREIGN KEY (container_id) REFERENCES container (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE catalog_price');
    }
}
