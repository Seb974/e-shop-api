<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328124219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logo ADD platform_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE logo ADD CONSTRAINT FK_E48E9A13FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('CREATE INDEX IDX_E48E9A13FFE6496F ON logo (platform_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logo DROP FOREIGN KEY FK_E48E9A13FFE6496F');
        $this->addSql('DROP INDEX IDX_E48E9A13FFE6496F ON logo');
        $this->addSql('ALTER TABLE logo DROP platform_id');
    }
}
