<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517135540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity ADD promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BD139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_CDA754BD139DF194 ON order_entity (promotion_id)');
        $this->addSql('ALTER TABLE relaypoint ADD promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE relaypoint ADD CONSTRAINT FK_F0ED53AD139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0ED53AD139DF194 ON relaypoint (promotion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BD139DF194');
        $this->addSql('DROP INDEX IDX_CDA754BD139DF194 ON order_entity');
        $this->addSql('ALTER TABLE order_entity DROP promotion_id');
        $this->addSql('ALTER TABLE relaypoint DROP FOREIGN KEY FK_F0ED53AD139DF194');
        $this->addSql('DROP INDEX UNIQ_F0ED53AD139DF194 ON relaypoint');
        $this->addSql('ALTER TABLE relaypoint DROP promotion_id');
    }
}
