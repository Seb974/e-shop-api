<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910131827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_us ADD header_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C34C92AD88 FOREIGN KEY (header_picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B52303C34C92AD88 ON about_us (header_picture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_us DROP FOREIGN KEY FK_B52303C34C92AD88');
        $this->addSql('DROP INDEX UNIQ_B52303C34C92AD88 ON about_us');
        $this->addSql('ALTER TABLE about_us DROP header_picture_id');
    }
}
