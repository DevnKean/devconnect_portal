<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171227114352 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lead_status_log (id INT AUTO_INCREMENT NOT NULL, lead_id INT DEFAULT NULL, supplier_id INT DEFAULT NULL, status VARCHAR(100) NOT NULL, INDEX IDX_79CB23FB55458D (lead_id), INDEX IDX_79CB23FB2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lead_status_log ADD CONSTRAINT FK_79CB23FB55458D FOREIGN KEY (lead_id) REFERENCES lead (id)');
        $this->addSql('ALTER TABLE lead_status_log ADD CONSTRAINT FK_79CB23FB2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE lead_status_log');
    }
}
