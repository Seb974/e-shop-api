<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222110841 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE batch ADD good_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE batch ADD CONSTRAINT FK_F80B52D41CF98C70 FOREIGN KEY (good_id) REFERENCES good (id)');
        $this->addSql('CREATE INDEX IDX_F80B52D41CF98C70 ON batch (good_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE batch DROP FOREIGN KEY FK_F80B52D41CF98C70');
        $this->addSql('DROP INDEX IDX_F80B52D41CF98C70 ON batch');
        $this->addSql('ALTER TABLE batch DROP good_id');
    }
}
