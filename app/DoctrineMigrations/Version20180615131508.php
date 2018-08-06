<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180615131508 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer CHANGE total_seats total_seats INT DEFAULT NULL, CHANGE percentage_of_business percentage_of_business NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD is_monday_closed TINYINT(1) DEFAULT NULL, ADD is_tuesday_closed TINYINT(1) DEFAULT NULL, ADD is_wednesday_closed TINYINT(1) DEFAULT NULL, ADD is_thursday_closed TINYINT(1) DEFAULT NULL, ADD is_friday_closed TINYINT(1) DEFAULT NULL, ADD is_saturday_closed TINYINT(1) DEFAULT NULL, ADD is_sunday_closed TINYINT(1) DEFAULT NULL, DROP is_monday_close, DROP is_tuesday_close, DROP is_wednesday_close, DROP is_thursday_close, DROP is_friday_close, DROP is_saturday_close, DROP is_sunday_close');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer CHANGE total_seats total_seats INT NOT NULL, CHANGE percentage_of_business percentage_of_business NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE location ADD is_monday_close TINYINT(1) DEFAULT NULL, ADD is_tuesday_close TINYINT(1) DEFAULT NULL, ADD is_wednesday_close TINYINT(1) DEFAULT NULL, ADD is_thursday_close TINYINT(1) DEFAULT NULL, ADD is_friday_close TINYINT(1) DEFAULT NULL, ADD is_saturday_close TINYINT(1) DEFAULT NULL, ADD is_sunday_close TINYINT(1) DEFAULT NULL, DROP is_monday_closed, DROP is_tuesday_closed, DROP is_wednesday_closed, DROP is_thursday_closed, DROP is_friday_closed, DROP is_saturday_closed, DROP is_sunday_closed');
    }
}
