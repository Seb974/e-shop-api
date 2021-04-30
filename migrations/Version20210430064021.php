<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430064021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `condition` (id INT AUTO_INCREMENT NOT NULL, tax_id INT DEFAULT NULL, days LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', price DOUBLE PRECISION DEFAULT NULL, min_for_free DOUBLE PRECISION DEFAULT NULL, INDEX IDX_BDD68843B2A824D8 (tax_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE condition_group (condition_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_211BA0CE887793B6 (condition_id), INDEX IDX_211BA0CEFE54D947 (group_id), PRIMARY KEY(condition_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `condition` ADD CONSTRAINT FK_BDD68843B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('ALTER TABLE condition_group ADD CONSTRAINT FK_211BA0CE887793B6 FOREIGN KEY (condition_id) REFERENCES `condition` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE condition_group ADD CONSTRAINT FK_211BA0CEFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condition_group DROP FOREIGN KEY FK_211BA0CE887793B6');
        $this->addSql('DROP TABLE `condition`');
        $this->addSql('DROP TABLE condition_group');
    }
}
