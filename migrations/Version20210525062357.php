<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525062357 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD variation_id INT DEFAULT NULL, ADD size_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E5182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E5182BFD8 ON item (variation_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E498DA827 ON item (size_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E5182BFD8');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E498DA827');
        $this->addSql('DROP INDEX IDX_1F1B251E5182BFD8 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251E498DA827 ON item');
        $this->addSql('ALTER TABLE item DROP variation_id, DROP size_id');
    }
}
