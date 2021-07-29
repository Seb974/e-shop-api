<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728100035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banner (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, homepage_id INT DEFAULT NULL, title VARCHAR(120) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_6F9DB8E74584665A (product_id), UNIQUE INDEX UNIQ_6F9DB8E7EE45BDBF (picture_id), INDEX IDX_6F9DB8E7571EDDA (homepage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E7EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E7571EDDA FOREIGN KEY (homepage_id) REFERENCES homepage (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE banner');
    }
}
