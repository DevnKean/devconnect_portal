<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180603042402 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location ADD is_monday_close TINYINT(1) DEFAULT NULL, ADD is_tuesday_close TINYINT(1) DEFAULT NULL, ADD is_wednesday_close TINYINT(1) DEFAULT NULL, ADD is_thursday_close TINYINT(1) DEFAULT NULL, ADD is_friday_close TINYINT(1) DEFAULT NULL, ADD is_saturday_close TINYINT(1) DEFAULT NULL, ADD is_sunday_close TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location DROP is_monday_close, DROP is_tuesday_close, DROP is_wednesday_close, DROP is_thursday_close, DROP is_friday_close, DROP is_saturday_close, DROP is_sunday_close');
    }
}
