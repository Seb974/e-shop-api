<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728130801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banner DROP FOREIGN KEY FK_6F9DB8E7EE45BDBF');
        $this->addSql('DROP INDEX UNIQ_6F9DB8E7EE45BDBF ON banner');
        $this->addSql('ALTER TABLE banner CHANGE picture_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E73DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F9DB8E73DA5256D ON banner (image_id)');
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E86EE45BDBF');
        $this->addSql('DROP INDEX UNIQ_51CE6E86EE45BDBF ON hero');
        $this->addSql('ALTER TABLE hero CHANGE picture_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E863DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E863DA5256D ON hero (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banner DROP FOREIGN KEY FK_6F9DB8E73DA5256D');
        $this->addSql('DROP INDEX UNIQ_6F9DB8E73DA5256D ON banner');
        $this->addSql('ALTER TABLE banner CHANGE image_id picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E7EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F9DB8E7EE45BDBF ON banner (picture_id)');
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E863DA5256D');
        $this->addSql('DROP INDEX UNIQ_51CE6E863DA5256D ON hero');
        $this->addSql('ALTER TABLE hero CHANGE image_id picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E86EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E86EE45BDBF ON hero (picture_id)');
    }
}
