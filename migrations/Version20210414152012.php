<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414152012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(120) DEFAULT NULL, sku VARCHAR(20) DEFAULT NULL, prices LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', discount DOUBLE PRECISION DEFAULT NULL, offer_end DATETIME DEFAULT NULL, full_description LONGTEXT DEFAULT NULL, sale_count INT DEFAULT NULL, new TINYINT(1) DEFAULT NULL, available TINYINT(1) DEFAULT NULL, stock_managed TINYINT(1) DEFAULT NULL, require_legal_age TINYINT(1) DEFAULT NULL, user_groups LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', unit VARCHAR(12) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, product_group VARCHAR(60) DEFAULT NULL, is_mixed TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04AD3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
    }
}
