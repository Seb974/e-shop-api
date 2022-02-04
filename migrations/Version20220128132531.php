<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128132531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE provision ADD platform_id INT DEFAULT NULL, ADD store_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE provision ADD CONSTRAINT FK_BA9B4290FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('ALTER TABLE provision ADD CONSTRAINT FK_BA9B4290B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE INDEX IDX_BA9B4290FFE6496F ON provision (platform_id)');
        $this->addSql('CREATE INDEX IDX_BA9B4290B092A811 ON provision (store_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE provision DROP FOREIGN KEY FK_BA9B4290FFE6496F');
        $this->addSql('ALTER TABLE provision DROP FOREIGN KEY FK_BA9B4290B092A811');
        $this->addSql('DROP INDEX IDX_BA9B4290FFE6496F ON provision');
        $this->addSql('DROP INDEX IDX_BA9B4290B092A811 ON provision');
        $this->addSql('ALTER TABLE provision DROP platform_id, DROP store_id');
    }
}
