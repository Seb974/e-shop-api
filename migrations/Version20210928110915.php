<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928110915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banner ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_6F9DB8E712469DE2 ON banner (category_id)');
        $this->addSql('ALTER TABLE countdown ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE countdown ADD CONSTRAINT FK_376B835612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_376B835612469DE2 ON countdown (category_id)');
        $this->addSql('ALTER TABLE hero ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E8612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_51CE6E8612469DE2 ON hero (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banner DROP FOREIGN KEY FK_6F9DB8E712469DE2');
        $this->addSql('DROP INDEX IDX_6F9DB8E712469DE2 ON banner');
        $this->addSql('ALTER TABLE banner DROP category_id');
        $this->addSql('ALTER TABLE countdown DROP FOREIGN KEY FK_376B835612469DE2');
        $this->addSql('DROP INDEX IDX_376B835612469DE2 ON countdown');
        $this->addSql('ALTER TABLE countdown DROP category_id');
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E8612469DE2');
        $this->addSql('DROP INDEX IDX_51CE6E8612469DE2 ON hero');
        $this->addSql('ALTER TABLE hero DROP category_id');
    }
}
