<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917042215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE banner_catalog (banner_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_4F711B84684EC833 (banner_id), INDEX IDX_4F711B84CC3C66FC (catalog_id), PRIMARY KEY(banner_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE countdown_catalog (countdown_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_34E1421BC4BEE45D (countdown_id), INDEX IDX_34E1421BCC3C66FC (catalog_id), PRIMARY KEY(countdown_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE hero_catalog (hero_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_F28F16045B0BCD (hero_id), INDEX IDX_F28F160CC3C66FC (catalog_id), PRIMARY KEY(hero_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE banner_catalog ADD CONSTRAINT FK_4F711B84684EC833 FOREIGN KEY (banner_id) REFERENCES banner (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE banner_catalog ADD CONSTRAINT FK_4F711B84CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE countdown_catalog ADD CONSTRAINT FK_34E1421BC4BEE45D FOREIGN KEY (countdown_id) REFERENCES countdown (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE countdown_catalog ADD CONSTRAINT FK_34E1421BCC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE hero_catalog ADD CONSTRAINT FK_F28F16045B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE hero_catalog ADD CONSTRAINT FK_F28F160CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('DROP TABLE banner_catalog');
        // $this->addSql('DROP TABLE countdown_catalog');
        // $this->addSql('DROP TABLE hero_catalog');
    }
}
