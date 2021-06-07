<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210606054305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE touring DROP FOREIGN KEY FK_E343D31CB6A6A3F4');
        $this->addSql('ALTER TABLE touring ADD deliverer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE touring ADD CONSTRAINT FK_E343D31CB6A6A3F4 FOREIGN KEY (deliverer_id) REFERENCES deliverer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE touring DROP FOREIGN KEY FK_E343D31CB6A6A3F4');
        $this->addSql('ALTER TABLE touring ADD CONSTRAINT FK_E343D31CB6A6A3F4 FOREIGN KEY (deliverer_id) REFERENCES user (id)');
    }
}
