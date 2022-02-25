<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222070517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE batch (id INT AUTO_INCREMENT NOT NULL, stock_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, end_date DATETIME DEFAULT NULL, initial_qty DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F80B52D4DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traceability (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, end_date DATETIME DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_91B4DA64126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE batch ADD CONSTRAINT FK_F80B52D4DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE traceability ADD CONSTRAINT FK_91B4DA64126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE product ADD needs_traceability TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE batch');
        $this->addSql('DROP TABLE traceability');
        $this->addSql('ALTER TABLE product DROP needs_traceability');
    }
}
