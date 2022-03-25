<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322045934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform ADD has_axonaut_link TINYINT(1) DEFAULT NULL, ADD axonaut_domain VARCHAR(255) DEFAULT NULL, ADD axonaut_key VARCHAR(255) DEFAULT NULL, ADD axonaut_email VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform DROP has_axonaut_link, DROP axonaut_domain, DROP axonaut_key, DROP axonaut_email');
    }
}
