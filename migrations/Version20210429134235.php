<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429134235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE day_off (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) DEFAULT NULL, date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day_off_group (day_off_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_94BBF0F27E81C1E1 (day_off_id), INDEX IDX_94BBF0F2FE54D947 (group_id), PRIMARY KEY(day_off_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE day_off_group ADD CONSTRAINT FK_94BBF0F27E81C1E1 FOREIGN KEY (day_off_id) REFERENCES day_off (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE day_off_group ADD CONSTRAINT FK_94BBF0F2FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day_off_group DROP FOREIGN KEY FK_94BBF0F27E81C1E1');
        $this->addSql('DROP TABLE day_off');
        $this->addSql('DROP TABLE day_off_group');
    }
}
