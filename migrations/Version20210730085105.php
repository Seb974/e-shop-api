<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730085105 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE countdown ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE countdown ADD CONSTRAINT FK_376B83564584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_376B83564584665A ON countdown (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE countdown DROP FOREIGN KEY FK_376B83564584665A');
        $this->addSql('DROP INDEX IDX_376B83564584665A ON countdown');
        $this->addSql('ALTER TABLE countdown DROP product_id');
    }
}
