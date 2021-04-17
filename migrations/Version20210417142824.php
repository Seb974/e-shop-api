<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210417142824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, stock_id INT DEFAULT NULL, variation_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F7C0246ADCD6110 (stock_id), INDEX IDX_F7C0246A5182BFD8 (variation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variation (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, color VARCHAR(60) DEFAULT NULL, UNIQUE INDEX UNIQ_629B33EA3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246ADCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246A5182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id)');
        $this->addSql('ALTER TABLE variation ADD CONSTRAINT FK_629B33EA3DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size DROP FOREIGN KEY FK_F7C0246A5182BFD8');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP TABLE variation');
    }
}
