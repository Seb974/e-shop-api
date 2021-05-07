<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507060639 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE container (id INT AUTO_INCREMENT NOT NULL, tax_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, name VARCHAR(120) DEFAULT NULL, max DOUBLE PRECISION DEFAULT NULL, tare DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, available TINYINT(1) DEFAULT NULL, INDEX IDX_C7A2EC1BB2A824D8 (tax_id), UNIQUE INDEX UNIQ_C7A2EC1BDCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BB2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BDCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE container');
    }
}
