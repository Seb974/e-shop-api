<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210614053402 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE platform (id INT AUTO_INCREMENT NOT NULL, metas_id INT DEFAULT NULL, name VARCHAR(120) DEFAULT NULL, UNIQUE INDEX UNIQ_3952D0CB23290B3B (metas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform_user (platform_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_99BB165FFE6496F (platform_id), INDEX IDX_99BB165A76ED395 (user_id), PRIMARY KEY(platform_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE platform ADD CONSTRAINT FK_3952D0CB23290B3B FOREIGN KEY (metas_id) REFERENCES meta (id)');
        $this->addSql('ALTER TABLE platform_user ADD CONSTRAINT FK_99BB165FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE platform_user ADD CONSTRAINT FK_99BB165A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform_user DROP FOREIGN KEY FK_99BB165FFE6496F');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE platform_user');
    }
}
