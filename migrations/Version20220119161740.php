<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119161740 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store ADD metas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF57587723290B3B FOREIGN KEY (metas_id) REFERENCES meta (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF57587723290B3B ON store (metas_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF57587723290B3B');
        $this->addSql('DROP INDEX UNIQ_FF57587723290B3B ON store');
        $this->addSql('ALTER TABLE store DROP metas_id');
    }
}
