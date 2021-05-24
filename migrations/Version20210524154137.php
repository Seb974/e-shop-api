<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210524154137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity ADD applied_condition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BDCF479569 FOREIGN KEY (applied_condition_id) REFERENCES `condition` (id)');
        $this->addSql('CREATE INDEX IDX_CDA754BDCF479569 ON order_entity (applied_condition_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BDCF479569');
        $this->addSql('DROP INDEX IDX_CDA754BDCF479569 ON order_entity');
        $this->addSql('ALTER TABLE order_entity DROP applied_condition_id');
    }
}
