<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528132524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE touring (id INT AUTO_INCREMENT NOT NULL, start DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_entity ADD touring_id INT DEFAULT NULL, DROP touring');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BD36BD1931 FOREIGN KEY (touring_id) REFERENCES touring (id)');
        $this->addSql('CREATE INDEX IDX_CDA754BD36BD1931 ON order_entity (touring_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BD36BD1931');
        $this->addSql('DROP TABLE touring');
        $this->addSql('DROP INDEX IDX_CDA754BD36BD1931 ON order_entity');
        $this->addSql('ALTER TABLE order_entity ADD touring VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP touring_id');
    }
}
