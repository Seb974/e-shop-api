<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607061632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seller ADD needs_recovery TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE touring RENAME INDEX fk_e343d31cb6a6a3f4 TO IDX_E343D31CB6A6A3F4');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seller DROP needs_recovery');
        $this->addSql('ALTER TABLE touring RENAME INDEX idx_e343d31cb6a6a3f4 TO FK_E343D31CB6A6A3F4');
    }
}
