<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419145359 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE component (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, variation_id INT DEFAULT NULL, size_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_49FEA1574584665A (product_id), INDEX IDX_49FEA1575182BFD8 (variation_id), INDEX IDX_49FEA157498DA827 (size_id), INDEX IDX_49FEA1577E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA1574584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA1575182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id)');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA1577E3C61F9 FOREIGN KEY (owner_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE component');
    }
}
