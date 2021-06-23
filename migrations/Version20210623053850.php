<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623053850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE good (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, provision_id INT DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(12) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_6C844E924584665A (product_id), INDEX IDX_6C844E923EC01A31 (provision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provision (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, provision_date DATETIME DEFAULT NULL, INDEX IDX_BA9B42902ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E924584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E923EC01A31 FOREIGN KEY (provision_id) REFERENCES provision (id)');
        $this->addSql('ALTER TABLE provision ADD CONSTRAINT FK_BA9B42902ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E923EC01A31');
        $this->addSql('DROP TABLE good');
        $this->addSql('DROP TABLE provision');
    }
}
