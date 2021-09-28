<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923102642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE seller ADD image_id INT DEFAULT NULL');
        // $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FC3DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_FB1AD3FC3DA5256D ON seller (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE seller DROP FOREIGN KEY FK_FB1AD3FC3DA5256D');
        // $this->addSql('DROP INDEX UNIQ_FB1AD3FC3DA5256D ON seller');
        // $this->addSql('ALTER TABLE seller DROP image_id');
    }
}
