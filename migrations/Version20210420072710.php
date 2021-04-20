<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420072710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `group` ADD price_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C59CE2E250 FOREIGN KEY (price_group_id) REFERENCES price_group (id)');
        $this->addSql('CREATE INDEX IDX_6DC044C59CE2E250 ON `group` (price_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C59CE2E250');
        $this->addSql('DROP TABLE price_group');
        $this->addSql('DROP INDEX IDX_6DC044C59CE2E250 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP price_group_id');
    }
}
