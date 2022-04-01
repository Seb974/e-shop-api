<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325062348 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE container_group (container_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_104F2194BC21F742 (container_id), INDEX IDX_104F2194FE54D947 (group_id), PRIMARY KEY(container_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE container_group ADD CONSTRAINT FK_104F2194BC21F742 FOREIGN KEY (container_id) REFERENCES container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE container_group ADD CONSTRAINT FK_104F2194FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE container_group');
    }
}
