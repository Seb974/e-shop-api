<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708134456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, container_id INT DEFAULT NULL, order_entity_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX IDX_DE686795BC21F742 (container_id), INDEX IDX_DE6867953DA206A5 (order_entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795BC21F742 FOREIGN KEY (container_id) REFERENCES container (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867953DA206A5 FOREIGN KEY (order_entity_id) REFERENCES order_entity (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE package');
    }
}
