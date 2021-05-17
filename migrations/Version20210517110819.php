<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517110819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity ADD catalog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BDCC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id)');
        $this->addSql('CREATE INDEX IDX_CDA754BDCC3C66FC ON order_entity (catalog_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BDCC3C66FC');
        $this->addSql('DROP INDEX IDX_CDA754BDCC3C66FC ON order_entity');
        $this->addSql('ALTER TABLE order_entity DROP catalog_id');
    }
}
