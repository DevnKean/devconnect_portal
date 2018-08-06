<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180730110429 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE data_acquisition_provider (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, company VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, INDEX IDX_9C0667A02ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_from_home (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, country VARCHAR(255) NOT NULL, fte INT NOT NULL, INDEX IDX_664879662ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, type VARCHAR(100) NOT NULL, subject VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F9AFB5EBA76ED395 (user_id), INDEX IDX_F9AFB5EB642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE potential_supplier (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, prefix VARCHAR(50) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, job_title VARCHAR(255) NOT NULL, contact_number VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, business_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, abn_number VARCHAR(100) NOT NULL, website VARCHAR(255) NOT NULL, total_seats VARCHAR(50) NOT NULL, locations LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', years_of_operations VARCHAR(100) NOT NULL, business_directory VARCHAR(100) NOT NULL, status VARCHAR(50) NOT NULL, username VARCHAR(50) NOT NULL, initial_password VARCHAR(50) NOT NULL, unique_id VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_E06831BC2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_acquisition_provider ADD CONSTRAINT FK_9C0667A02ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE work_from_home ADD CONSTRAINT FK_664879662ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE potential_supplier ADD CONSTRAINT FK_E06831BC2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE commission_model ADD flat_rate DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD job_title VARCHAR(100) DEFAULT NULL, ADD contact_phone VARCHAR(100) DEFAULT NULL, ADD login_sent_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE customer CHANGE total_seats total_seats INT DEFAULT NULL, CHANGE percentage_of_business percentage_of_business NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B2A6C7E98943B3A ON supplier (abn_number)');
        $this->addSql('ALTER TABLE location ADD operate_from VARCHAR(255) DEFAULT NULL, ADD conduct_in VARCHAR(255) DEFAULT NULL, ADD noise_cancelling TINYINT(1) DEFAULT NULL, ADD is_monday_closed TINYINT(1) DEFAULT NULL, ADD is_tuesday_closed TINYINT(1) DEFAULT NULL, ADD is_wednesday_closed TINYINT(1) DEFAULT NULL, ADD is_thursday_closed TINYINT(1) DEFAULT NULL, ADD is_friday_closed TINYINT(1) DEFAULT NULL, ADD is_saturday_closed TINYINT(1) DEFAULT NULL, ADD is_sunday_closed TINYINT(1) DEFAULT NULL, CHANGE total_seats total_seats INT DEFAULT NULL, CHANGE available_seats available_seats INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile ADD initial_status VARCHAR(100) NOT NULL, ADD `order` SMALLINT NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL, CHANGE route route VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE lead ADD client_id INT DEFAULT NULL, ADD type VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_289161CB19EB6921 ON lead (client_id)');
        $this->addSql('ALTER TABLE contract ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP createdAt, DROP updatedAt, CHANGE start_date start_date DATE DEFAULT NULL, CHANGE end_date end_date DATE DEFAULT NULL, CHANGE sent_at sent_at DATE DEFAULT NULL, CHANGE file file VARCHAR(255) DEFAULT NULL, CHANGE payment_term payment_term INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE data_acquisition_provider');
        $this->addSql('DROP TABLE work_from_home');
        $this->addSql('DROP TABLE communication');
        $this->addSql('DROP TABLE potential_supplier');
        $this->addSql('ALTER TABLE commission_model DROP flat_rate');
        $this->addSql('ALTER TABLE contract ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, DROP created_at, DROP updated_at, CHANGE start_date start_date DATE NOT NULL, CHANGE end_date end_date DATE NOT NULL, CHANGE sent_at sent_at DATE NOT NULL, CHANGE file file VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE payment_term payment_term INT NOT NULL');
        $this->addSql('ALTER TABLE customer CHANGE total_seats total_seats INT NOT NULL, CHANGE percentage_of_business percentage_of_business NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CB19EB6921');
        $this->addSql('DROP INDEX IDX_289161CB19EB6921 ON lead');
        $this->addSql('ALTER TABLE lead DROP client_id, DROP type');
        $this->addSql('ALTER TABLE location DROP operate_from, DROP conduct_in, DROP noise_cancelling, DROP is_monday_closed, DROP is_tuesday_closed, DROP is_wednesday_closed, DROP is_thursday_closed, DROP is_friday_closed, DROP is_saturday_closed, DROP is_sunday_closed, CHANGE total_seats total_seats INT NOT NULL, CHANGE available_seats available_seats INT NOT NULL');
        $this->addSql('ALTER TABLE profile DROP initial_status, DROP `order`, CHANGE name name VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, CHANGE slug slug VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, CHANGE route route VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_9B2A6C7E98943B3A ON supplier');
        $this->addSql('ALTER TABLE user DROP job_title, DROP contact_phone, DROP login_sent_at');
    }
}
