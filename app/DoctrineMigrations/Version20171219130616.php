<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171219130616 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location CHANGE is_monday_open_24_hours is_monday_open_24_hours TINYINT(1) DEFAULT NULL, CHANGE is_tuesday_open_24_hours is_tuesday_open_24_hours TINYINT(1) DEFAULT NULL, CHANGE is_wednesday_open_24_hours is_wednesday_open_24_hours TINYINT(1) DEFAULT NULL, CHANGE is_thursday_open_24_hours is_thursday_open_24_hours TINYINT(1) DEFAULT NULL, CHANGE is_friday_open_24_hours is_friday_open_24_hours TINYINT(1) DEFAULT NULL, CHANGE is_saturday_open_24_hours is_saturday_open_24_hours TINYINT(1) DEFAULT NULL, CHANGE is_sunday_open_24_hours is_sunday_open_24_hours TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location CHANGE is_monday_open_24_hours is_monday_open_24_hours TIME DEFAULT NULL, CHANGE is_tuesday_open_24_hours is_tuesday_open_24_hours TIME DEFAULT NULL, CHANGE is_wednesday_open_24_hours is_wednesday_open_24_hours TIME DEFAULT NULL, CHANGE is_thursday_open_24_hours is_thursday_open_24_hours TIME DEFAULT NULL, CHANGE is_friday_open_24_hours is_friday_open_24_hours TIME DEFAULT NULL, CHANGE is_saturday_open_24_hours is_saturday_open_24_hours TIME DEFAULT NULL, CHANGE is_sunday_open_24_hours is_sunday_open_24_hours TIME DEFAULT NULL');
    }
}
