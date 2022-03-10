<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310054711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lost (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, variation_id INT DEFAULT NULL, size_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, lost_date DATETIME DEFAULT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_404584AA4584665A (product_id), INDEX IDX_404584AA5182BFD8 (variation_id), INDEX IDX_404584AA498DA827 (size_id), INDEX IDX_404584AADCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lost ADD CONSTRAINT FK_404584AA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE lost ADD CONSTRAINT FK_404584AA5182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id)');
        $this->addSql('ALTER TABLE lost ADD CONSTRAINT FK_404584AA498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('ALTER TABLE lost ADD CONSTRAINT FK_404584AADCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lost');
    }
}
