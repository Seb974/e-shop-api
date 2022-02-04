<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128122132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity ADD platform_id INT DEFAULT NULL, ADD store_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BDFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BDB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE INDEX IDX_CDA754BDFFE6496F ON order_entity (platform_id)');
        $this->addSql('CREATE INDEX IDX_CDA754BDB092A811 ON order_entity (store_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BDFFE6496F');
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BDB092A811');
        $this->addSql('DROP INDEX IDX_CDA754BDFFE6496F ON order_entity');
        $this->addSql('DROP INDEX IDX_CDA754BDB092A811 ON order_entity');
        $this->addSql('ALTER TABLE order_entity DROP platform_id, DROP store_id');
    }
}
