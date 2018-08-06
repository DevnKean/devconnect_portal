<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171219121650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commercial DROP INDEX IDX_7653F3AE2ADD6D8C, ADD UNIQUE INDEX UNIQ_7653F3AE2ADD6D8C (supplier_id)');
        $this->addSql('ALTER TABLE location ADD monday_open_time TIME DEFAULT NULL, ADD monday_close_time TIME DEFAULT NULL, ADD is_monday_open_24_hours TIME DEFAULT NULL, ADD tuesday_open_time TIME DEFAULT NULL, ADD tuesday_close_time TIME DEFAULT NULL, ADD is_tuesday_open_24_hours TIME DEFAULT NULL, ADD wednesday_open_time TIME DEFAULT NULL, ADD wednesday_close_time TIME DEFAULT NULL, ADD is_wednesday_open_24_hours TIME DEFAULT NULL, ADD thursday_open_time TIME DEFAULT NULL, ADD thursday_close_time TIME DEFAULT NULL, ADD is_thursday_open_24_hours TIME DEFAULT NULL, ADD friday_open_time TIME DEFAULT NULL, ADD friday_close_time TIME DEFAULT NULL, ADD is_friday_open_24_hours TIME DEFAULT NULL, ADD saturday_open_time TIME DEFAULT NULL, ADD saturday_close_time TIME DEFAULT NULL, ADD is_saturday_open_24_hours TIME DEFAULT NULL, ADD sunday_open_time TIME DEFAULT NULL, ADD sunday_close_time TIME DEFAULT NULL, ADD is_sunday_open_24_hours TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE award CHANGE date date DATE DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE award CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE commercial DROP INDEX UNIQ_7653F3AE2ADD6D8C, ADD INDEX IDX_7653F3AE2ADD6D8C (supplier_id)');
        $this->addSql('ALTER TABLE location DROP monday_open_time, DROP monday_close_time, DROP is_monday_open_24_hours, DROP tuesday_open_time, DROP tuesday_close_time, DROP is_tuesday_open_24_hours, DROP wednesday_open_time, DROP wednesday_close_time, DROP is_wednesday_open_24_hours, DROP thursday_open_time, DROP thursday_close_time, DROP is_thursday_open_24_hours, DROP friday_open_time, DROP friday_close_time, DROP is_friday_open_24_hours, DROP saturday_open_time, DROP saturday_close_time, DROP is_saturday_open_24_hours, DROP sunday_open_time, DROP sunday_close_time, DROP is_sunday_open_24_hours');
    }
}
