<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712101018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_catalog (category_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_DCF4723912469DE2 (category_id), INDEX IDX_DCF47239CC3C66FC (catalog_id), PRIMARY KEY(category_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_catalog ADD CONSTRAINT FK_DCF4723912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_catalog ADD CONSTRAINT FK_DCF47239CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restriction ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restriction ADD CONSTRAINT FK_7A999BCE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7A999BCE12469DE2 ON restriction (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category_catalog');
        $this->addSql('ALTER TABLE restriction DROP FOREIGN KEY FK_7A999BCE12469DE2');
        $this->addSql('DROP INDEX IDX_7A999BCE12469DE2 ON restriction');
        $this->addSql('ALTER TABLE restriction DROP category_id');
    }
}
