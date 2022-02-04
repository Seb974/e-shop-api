<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129071452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store ADD platform_id INT DEFAULT NULL, ADD seller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF575877FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758778DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('CREATE INDEX IDX_FF575877FFE6496F ON store (platform_id)');
        $this->addSql('CREATE INDEX IDX_FF5758778DE820D9 ON store (seller_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF575877FFE6496F');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758778DE820D9');
        $this->addSql('DROP INDEX IDX_FF575877FFE6496F ON store');
        $this->addSql('DROP INDEX IDX_FF5758778DE820D9 ON store');
        $this->addSql('ALTER TABLE store DROP platform_id, DROP seller_id');
    }
}
