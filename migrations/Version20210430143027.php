<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430143027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE relaypoint (id INT AUTO_INCREMENT NOT NULL, metas_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, available TINYINT(1) DEFAULT NULL, private TINYINT(1) DEFAULT NULL, access_code VARCHAR(30) DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, informations LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_F0ED53AD23290B3B (metas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relaypoint_condition (relaypoint_id INT NOT NULL, condition_id INT NOT NULL, INDEX IDX_9B98DFE5F8CC9BA1 (relaypoint_id), INDEX IDX_9B98DFE5887793B6 (condition_id), PRIMARY KEY(relaypoint_id, condition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relaypoint ADD CONSTRAINT FK_F0ED53AD23290B3B FOREIGN KEY (metas_id) REFERENCES meta (id)');
        $this->addSql('ALTER TABLE relaypoint_condition ADD CONSTRAINT FK_9B98DFE5F8CC9BA1 FOREIGN KEY (relaypoint_id) REFERENCES relaypoint (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE relaypoint_condition ADD CONSTRAINT FK_9B98DFE5887793B6 FOREIGN KEY (condition_id) REFERENCES `condition` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relaypoint_condition DROP FOREIGN KEY FK_9B98DFE5F8CC9BA1');
        $this->addSql('DROP TABLE relaypoint');
        $this->addSql('DROP TABLE relaypoint_condition');
    }
}
