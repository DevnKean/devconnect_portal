<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209114953 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B2A6C7E98943B3A ON supplier (abn_number)');
        $this->addSql('ALTER TABLE potential_supplier ADD supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE potential_supplier ADD CONSTRAINT FK_E06831BC2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E06831BC2ADD6D8C ON potential_supplier (supplier_id)');
        $this->addSql('ALTER TABLE contract CHANGE file file VARCHAR(255) DEFAULT NULL, CHANGE payment_term payment_term INT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contract CHANGE file file VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE payment_term payment_term INT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE potential_supplier DROP FOREIGN KEY FK_E06831BC2ADD6D8C');
        $this->addSql('DROP INDEX UNIQ_E06831BC2ADD6D8C ON potential_supplier');
        $this->addSql('ALTER TABLE potential_supplier DROP supplier_id');
        $this->addSql('DROP INDEX UNIQ_9B2A6C7E98943B3A ON supplier');
    }
}
