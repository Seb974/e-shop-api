<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623094932 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE good ADD variation_id INT DEFAULT NULL, ADD size_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E925182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id)');
        $this->addSql('ALTER TABLE good ADD CONSTRAINT FK_6C844E92498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('CREATE INDEX IDX_6C844E925182BFD8 ON good (variation_id)');
        $this->addSql('CREATE INDEX IDX_6C844E92498DA827 ON good (size_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E925182BFD8');
        $this->addSql('ALTER TABLE good DROP FOREIGN KEY FK_6C844E92498DA827');
        $this->addSql('DROP INDEX IDX_6C844E925182BFD8 ON good');
        $this->addSql('DROP INDEX IDX_6C844E92498DA827 ON good');
        $this->addSql('ALTER TABLE good DROP variation_id, DROP size_id');
    }
}
