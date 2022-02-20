<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220219170726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` ADD has_store_access TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD store_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF575877C023A6BD FOREIGN KEY (store_group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_FF575877C023A6BD ON store (store_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP has_store_access');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF575877C023A6BD');
        $this->addSql('DROP INDEX IDX_FF575877C023A6BD ON store');
        $this->addSql('ALTER TABLE store DROP store_group_id');
    }
}
