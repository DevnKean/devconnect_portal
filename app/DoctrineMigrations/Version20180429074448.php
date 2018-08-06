<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429074448 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_from_home (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, fte INT DEFAULT NULL, INDEX IDX_664879662ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_from_home ADD CONSTRAINT FK_664879662ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('INSERT INTO `profile` (`id`, `service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`)
VALUES
	(30, 1, \'Work From Home\', \'work-from-home\', \'profile_workfromhome\', \'fa-shield\', NULL);
');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_from_home');
    }
}
