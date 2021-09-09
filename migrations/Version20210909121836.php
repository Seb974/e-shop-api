<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909121836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE about_us (id INT AUTO_INCREMENT NOT NULL, service_picture_id INT DEFAULT NULL, product_picture_id INT DEFAULT NULL, support_picture_id INT DEFAULT NULL, summary LONGTEXT DEFAULT NULL, vision LONGTEXT DEFAULT NULL, mission LONGTEXT DEFAULT NULL, goal LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_B52303C37814840B (service_picture_id), UNIQUE INDEX UNIQ_B52303C37D22E294 (product_picture_id), UNIQUE INDEX UNIQ_B52303C3CCF1799D (support_picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, command VARCHAR(1024) NOT NULL, schedule VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX un_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_report (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, run_at DATETIME NOT NULL, run_time DOUBLE PRECISION NOT NULL, exit_code INT NOT NULL, output LONGTEXT NOT NULL, INDEX IDX_B6C6A7F5BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C37814840B FOREIGN KEY (service_picture_id) REFERENCES picture (id)');
        // $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C37D22E294 FOREIGN KEY (product_picture_id) REFERENCES picture (id)');
        // $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C3CCF1799D FOREIGN KEY (support_picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE cron_report ADD CONSTRAINT FK_B6C6A7F5BE04EA9 FOREIGN KEY (job_id) REFERENCES cron_job (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cron_report DROP FOREIGN KEY FK_B6C6A7F5BE04EA9');
        // $this->addSql('DROP TABLE about_us');
        $this->addSql('DROP TABLE cron_job');
        $this->addSql('DROP TABLE cron_report');
    }
}
