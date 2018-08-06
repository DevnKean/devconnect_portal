<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180609044132 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE data_acquisition_provider (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, company VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, INDEX IDX_9C0667A02ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_acquisition_provider ADD CONSTRAINT FK_9C0667A02ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`)
VALUES
	(2, 'Data Acquisition Provider', 'data-acquisition-provider', 'profile_data_acquisition_provider', 'fa-shield', NULL, 13, 'Incomplete');
");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE data_acquisition_provider');
    }
}
