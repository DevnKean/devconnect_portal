<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208120116 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client_note (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1E21397619EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, firstName VARCHAR(50) DEFAULT NULL, lastName VARCHAR(50) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, mobile VARCHAR(50) DEFAULT NULL, company VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_note ADD CONSTRAINT FK_1E21397619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE payment ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE createdat paid_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD status VARCHAR(20) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP due_at');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client_note DROP FOREIGN KEY FK_1E21397619EB6921');
        $this->addSql('DROP TABLE client_note');
        $this->addSql('DROP TABLE client');
        $this->addSql('ALTER TABLE invoice ADD due_at DATE NOT NULL, DROP status, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE payment ADD createdAt DATETIME NOT NULL, DROP paid_at, DROP created_at, DROP updated_at');
    }
}
