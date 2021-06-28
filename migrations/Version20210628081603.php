<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628081603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliverer ADD tax_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE deliverer ADD CONSTRAINT FK_97FE3348B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('CREATE INDEX IDX_97FE3348B2A824D8 ON deliverer (tax_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliverer DROP FOREIGN KEY FK_97FE3348B2A824D8');
        $this->addSql('DROP INDEX IDX_97FE3348B2A824D8 ON deliverer');
        $this->addSql('ALTER TABLE deliverer DROP tax_id');
    }
}
