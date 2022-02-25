<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222195947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size DROP FOREIGN KEY FK_F7C0246ADCD6110');
        $this->addSql('DROP INDEX UNIQ_F7C0246ADCD6110 ON size');
        $this->addSql('ALTER TABLE size DROP stock_id');
        $this->addSql('ALTER TABLE traceability ADD initial_qty DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size ADD stock_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246ADCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7C0246ADCD6110 ON size (stock_id)');
        $this->addSql('ALTER TABLE traceability DROP initial_qty');
    }
}
