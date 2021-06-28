<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628103952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliverer ADD catalog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE deliverer ADD CONSTRAINT FK_97FE3348CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id)');
        $this->addSql('CREATE INDEX IDX_97FE3348CC3C66FC ON deliverer (catalog_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliverer DROP FOREIGN KEY FK_97FE3348CC3C66FC');
        $this->addSql('DROP INDEX IDX_97FE3348CC3C66FC ON deliverer');
        $this->addSql('ALTER TABLE deliverer DROP catalog_id');
    }
}
