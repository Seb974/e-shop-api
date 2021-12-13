<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924065802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BDF17005AA FOREIGN KEY (preparator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CDA754BDF17005AA ON order_entity (preparator_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BDF17005AA');
        $this->addSql('DROP INDEX IDX_CDA754BDF17005AA ON order_entity');
    }
}
