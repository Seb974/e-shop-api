<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928162949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(120) DEFAULT NULL, role VARCHAR(120) DEFAULT NULL, UNIQUE INDEX UNIQ_268B9C9D3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE banner_catalog (banner_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_4F711B84684EC833 (banner_id), INDEX IDX_4F711B84CC3C66FC (catalog_id), PRIMARY KEY(banner_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE countdown_catalog (countdown_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_34E1421BC4BEE45D (countdown_id), INDEX IDX_34E1421BCC3C66FC (catalog_id), PRIMARY KEY(countdown_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE hero_catalog (hero_id INT NOT NULL, catalog_id INT NOT NULL, INDEX IDX_F28F16045B0BCD (hero_id), INDEX IDX_F28F160CC3C66FC (catalog_id), PRIMARY KEY(hero_id, catalog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, message LONGTEXT DEFAULT NULL, sent_at DATETIME DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, response LONGTEXT DEFAULT NULL, replied TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D3DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        // $this->addSql('ALTER TABLE banner_catalog ADD CONSTRAINT FK_4F711B84684EC833 FOREIGN KEY (banner_id) REFERENCES banner (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE banner_catalog ADD CONSTRAINT FK_4F711B84CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE countdown_catalog ADD CONSTRAINT FK_34E1421BC4BEE45D FOREIGN KEY (countdown_id) REFERENCES countdown (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE countdown_catalog ADD CONSTRAINT FK_34E1421BCC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE hero_catalog ADD CONSTRAINT FK_F28F16045B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE hero_catalog ADD CONSTRAINT FK_F28F160CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalog (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE about_us ADD header_picture_id INT DEFAULT NULL, ADD vision_title VARCHAR(120) DEFAULT NULL, ADD mission_title VARCHAR(120) DEFAULT NULL, ADD goal_title VARCHAR(120) DEFAULT NULL, ADD service_title VARCHAR(120) DEFAULT NULL, ADD product_title VARCHAR(120) DEFAULT NULL, ADD support_title VARCHAR(120) DEFAULT NULL, ADD vision_color VARCHAR(20) DEFAULT NULL, ADD mission_color VARCHAR(20) DEFAULT NULL, ADD goal_color VARCHAR(20) DEFAULT NULL, ADD service_color VARCHAR(20) DEFAULT NULL, ADD product_color VARCHAR(20) DEFAULT NULL, ADD support_color VARCHAR(20) DEFAULT NULL, ADD header_title VARCHAR(255) DEFAULT NULL, ADD header_subtitle VARCHAR(255) DEFAULT NULL, ADD header_title_color VARCHAR(20) DEFAULT NULL, ADD header_subtitle_color VARCHAR(20) DEFAULT NULL');
        // $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C34C92AD88 FOREIGN KEY (header_picture_id) REFERENCES picture (id)');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_B52303C34C92AD88 ON about_us (header_picture_id)');
        // $this->addSql('ALTER TABLE catalog ADD is_active TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE banner_catalog');
        $this->addSql('DROP TABLE countdown_catalog');
        $this->addSql('DROP TABLE hero_catalog');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE about_us DROP FOREIGN KEY FK_B52303C34C92AD88');
        $this->addSql('DROP INDEX UNIQ_B52303C34C92AD88 ON about_us');
        $this->addSql('ALTER TABLE about_us DROP header_picture_id, DROP vision_title, DROP mission_title, DROP goal_title, DROP service_title, DROP product_title, DROP support_title, DROP vision_color, DROP mission_color, DROP goal_color, DROP service_color, DROP product_color, DROP support_color, DROP header_title, DROP header_subtitle, DROP header_title_color, DROP header_subtitle_color');
        $this->addSql('ALTER TABLE catalog DROP is_active');
    }
}
