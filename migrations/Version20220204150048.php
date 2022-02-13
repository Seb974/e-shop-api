<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204150048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seller ADD metas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FC23290B3B FOREIGN KEY (metas_id) REFERENCES meta (id)');
        $this->addSql('CREATE INDEX IDX_FB1AD3FC23290B3B ON seller (metas_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seller DROP FOREIGN KEY FK_FB1AD3FC23290B3B');
        $this->addSql('DROP INDEX IDX_FB1AD3FC23290B3B ON seller');
        $this->addSql('ALTER TABLE seller DROP metas_id');
    }
}
