<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517043031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_entity_id INT DEFAULT NULL, ordered_qty DOUBLE PRECISION DEFAULT NULL, prepared_qty DOUBLE PRECISION DEFAULT NULL, delivered_qty DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION DEFAULT NULL, is_adjourned TINYINT(1) DEFAULT NULL, INDEX IDX_1F1B251E4584665A (product_id), INDEX IDX_1F1B251E3DA206A5 (order_entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_entity (id INT AUTO_INCREMENT NOT NULL, metas_id INT DEFAULT NULL, name VARCHAR(120) DEFAULT NULL, email VARCHAR(120) DEFAULT NULL, delivery_date DATETIME DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, is_remains TINYINT(1) DEFAULT NULL, total_ht DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, total_ttc DOUBLE PRECISION DEFAULT NULL, INDEX IDX_CDA754BD23290B3B (metas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E3DA206A5 FOREIGN KEY (order_entity_id) REFERENCES order_entity (id)');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BD23290B3B FOREIGN KEY (metas_id) REFERENCES meta (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E3DA206A5');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE order_entity');
    }
}
