<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210618180730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F819E9AC5F');
        $this->addSql('DROP INDEX IDX_4D9192F819E9AC5F ON supervisor');
        $this->addSql('ALTER TABLE supervisor DROP supervisor_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supervisor ADD supervisor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F819E9AC5F FOREIGN KEY (supervisor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4D9192F819E9AC5F ON supervisor (supervisor_id)');
    }
}
