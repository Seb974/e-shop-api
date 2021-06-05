<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210605082525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deliverer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) DEFAULT NULL, is_intern TINYINT(1) DEFAULT NULL, cost DOUBLE PRECISION DEFAULT NULL, is_percent TINYINT(1) DEFAULT NULL, total_to_pay DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deliverer_user (deliverer_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DBF1A092B6A6A3F4 (deliverer_id), INDEX IDX_DBF1A092A76ED395 (user_id), PRIMARY KEY(deliverer_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deliverer_user ADD CONSTRAINT FK_DBF1A092B6A6A3F4 FOREIGN KEY (deliverer_id) REFERENCES deliverer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deliverer_user ADD CONSTRAINT FK_DBF1A092A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliverer_user DROP FOREIGN KEY FK_DBF1A092B6A6A3F4');
        $this->addSql('DROP TABLE deliverer');
        $this->addSql('DROP TABLE deliverer_user');
    }
}
