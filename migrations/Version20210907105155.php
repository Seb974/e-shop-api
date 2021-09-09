<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907105155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_us ADD service_picture_id INT DEFAULT NULL, ADD product_picture_id INT DEFAULT NULL, ADD support_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C37814840B FOREIGN KEY (service_picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C37D22E294 FOREIGN KEY (product_picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C3CCF1799D FOREIGN KEY (support_picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B52303C37814840B ON about_us (service_picture_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B52303C37D22E294 ON about_us (product_picture_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B52303C3CCF1799D ON about_us (support_picture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_us DROP FOREIGN KEY FK_B52303C37814840B');
        $this->addSql('ALTER TABLE about_us DROP FOREIGN KEY FK_B52303C37D22E294');
        $this->addSql('ALTER TABLE about_us DROP FOREIGN KEY FK_B52303C3CCF1799D');
        $this->addSql('DROP INDEX UNIQ_B52303C37814840B ON about_us');
        $this->addSql('DROP INDEX UNIQ_B52303C37D22E294 ON about_us');
        $this->addSql('DROP INDEX UNIQ_B52303C3CCF1799D ON about_us');
        $this->addSql('ALTER TABLE about_us DROP service_picture_id, DROP product_picture_id, DROP support_picture_id');
    }
}
