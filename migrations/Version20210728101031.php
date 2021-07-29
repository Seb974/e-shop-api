<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728101031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE countdown (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, homepage_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_376B83563DA5256D (image_id), INDEX IDX_376B8356571EDDA (homepage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE countdown ADD CONSTRAINT FK_376B83563DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE countdown ADD CONSTRAINT FK_376B8356571EDDA FOREIGN KEY (homepage_id) REFERENCES homepage (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE countdown');
    }
}
