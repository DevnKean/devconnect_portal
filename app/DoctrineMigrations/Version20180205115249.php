<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180205115249 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE potential_supplier (id INT AUTO_INCREMENT NOT NULL, prefix VARCHAR(50) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, job_title VARCHAR(255) NOT NULL, contact_number VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, business_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, abn_number VARCHAR(100) NOT NULL, website VARCHAR(255) NOT NULL, total_seats VARCHAR(50) NOT NULL, locations LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', years_of_operations VARCHAR(100) NOT NULL, business_directory VARCHAR(100) NOT NULL, status VARCHAR(50) NOT NULL, username VARCHAR(50) NOT NULL, initial_password VARCHAR(50) NOT NULL, unique_id VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE potential_supplier');
    }
}
