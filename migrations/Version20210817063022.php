<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210817063022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banner ADD title_color VARCHAR(20) DEFAULT NULL, ADD text_shadow TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE countdown ADD text_shadow TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD title_color VARCHAR(20) DEFAULT NULL, ADD text_shadow TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banner DROP title_color, DROP text_shadow');
        $this->addSql('ALTER TABLE countdown DROP text_shadow');
        $this->addSql('ALTER TABLE hero DROP title_color, DROP text_shadow');
    }
}
