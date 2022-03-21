<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321081125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform ADD img_domain VARCHAR(255) DEFAULT NULL, ADD img_key VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seller ADD img_domain VARCHAR(255) DEFAULT NULL, ADD img_key VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform DROP img_domain, DROP img_key');
        $this->addSql('ALTER TABLE seller DROP img_domain, DROP img_key');
    }
}
