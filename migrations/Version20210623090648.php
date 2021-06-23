<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623090648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE provision ADD seller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE provision ADD CONSTRAINT FK_BA9B42908DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('CREATE INDEX IDX_BA9B42908DE820D9 ON provision (seller_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE provision DROP FOREIGN KEY FK_BA9B42908DE820D9');
        $this->addSql('DROP INDEX IDX_BA9B42908DE820D9 ON provision');
        $this->addSql('ALTER TABLE provision DROP seller_id');
    }
}
