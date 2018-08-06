<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171207072041 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE support_functions (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, function VARCHAR(30) NOT NULL, point NUMERIC(10, 2) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_F57EB6BA2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, function VARCHAR(50) NOT NULL, years_experience VARCHAR(30) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_590C1032ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE channel_support (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, channel VARCHAR(50) NOT NULL, experience_level VARCHAR(50) NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_3747D4A42ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commission_model (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_99D4604A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_entries (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, feedback_status VARCHAR(255) DEFAULT NULL, feedback LONGTEXT DEFAULT NULL, supplier_reply LONGTEXT DEFAULT NULL, is_read TINYINT(1) NOT NULL, parent_id VARCHAR(64) DEFAULT NULL, parent_class VARCHAR(255) DEFAULT NULL, old_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX IDX_15358B52A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_note (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_432E7DBE2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reference (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, company_name VARCHAR(20) NOT NULL, title VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, work_phone VARCHAR(20) NOT NULL, mobile_phone VARCHAR(20) NOT NULL, functions LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', cessation_reason LONGTEXT DEFAULT NULL, type VARCHAR(10) NOT NULL, campaign VARCHAR(20) NOT NULL, campaign_description LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_AEA349132ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, first_name VARCHAR(60) NOT NULL, last_name VARCHAR(60) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', is_active TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6492ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commission_tier (id INT AUTO_INCREMENT NOT NULL, commission_id INT DEFAULT NULL, tier_level VARCHAR(20) NOT NULL, lower_threshold INT NOT NULL, upper_threshold INT DEFAULT NULL, rate_year_one NUMERIC(10, 4) NOT NULL, rate_year_two NUMERIC(10, 4) NOT NULL, rate_year_three NUMERIC(10, 4) NOT NULL, INDEX IDX_610F5068202D1EB2 (commission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leads_suppliers (id INT AUTO_INCREMENT NOT NULL, lead_id INT NOT NULL, supplier_id INT NOT NULL, allocated_date DATE NOT NULL, result VARCHAR(255) DEFAULT NULL, lost_reason VARCHAR(255) DEFAULT NULL, lost_reason_notes LONGTEXT DEFAULT NULL, internal_notes LONGTEXT DEFAULT NULL, notes_to_outsourcer LONGTEXT DEFAULT NULL, lead_status VARCHAR(50) NOT NULL, commenced_at DATE DEFAULT NULL, agreement_expired_at DATE DEFAULT NULL, INDEX IDX_45A91ECD55458D (lead_id), INDEX IDX_45A91ECD2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, work_phone VARCHAR(255) NOT NULL, mobile_phone VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_4C62E6382ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, amount INT NOT NULL, createdAt DATETIME NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoices_payments (payment_id INT NOT NULL, invoice_id INT NOT NULL, INDEX IDX_7BDCCC904C3A3BB (payment_id), INDEX IDX_7BDCCC902989F1FD (invoice_id), PRIMARY KEY(payment_id, invoice_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suppliers_profiles (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, profile_id INT DEFAULT NULL, status VARCHAR(20) NOT NULL, message LONGTEXT DEFAULT NULL, INDEX IDX_6AA319D02ADD6D8C (supplier_id), INDEX IDX_6AA319D0CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, industry_vertical VARCHAR(50) NOT NULL, functions LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', total_seats INT NOT NULL, percentage_of_business NUMERIC(10, 2) NOT NULL, work_period VARCHAR(30) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_81398E092ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers_locations (customer_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_2B35F2359395C3F3 (customer_id), INDEX IDX_2B35F23564D218E (location_id), PRIMARY KEY(customer_id, location_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, technology VARCHAR(30) NOT NULL, vendor VARCHAR(30) DEFAULT NULL, experience_level VARCHAR(30) NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_F463524D2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, models LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', deleted_at DATETIME DEFAULT NULL, INDEX IDX_7653F3AE2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lead_notes (id INT AUTO_INCREMENT NOT NULL, lead_id INT DEFAULT NULL, supplier_id INT DEFAULT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_67FC6B0355458D (lead_id), INDEX IDX_67FC6B032ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, supplier_invoice_id INT DEFAULT NULL, commission_rate NUMERIC(10, 4) NOT NULL, commission NUMERIC(10, 2) NOT NULL, tier_level VARCHAR(10) NOT NULL, xero_id VARCHAR(255) NOT NULL, due_at DATE NOT NULL, next_invoice_issue_at DATE NOT NULL, UNIQUE INDEX UNIQ_9065174448783C14 (supplier_invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, business_name VARCHAR(255) NOT NULL, trading_name VARCHAR(255) NOT NULL, abn_number VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, youtube VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, snapchat VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, professional_indemnity NUMERIC(10, 2) DEFAULT NULL, public_liability NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE minimum_volumes (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, inboundContacts LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', headcount LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', campaignData LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', UNIQUE INDEX UNIQ_473CA3072ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_invoice (id INT AUTO_INCREMENT NOT NULL, campaign_id INT DEFAULT NULL, issuedAt DATE NOT NULL, receivedAt DATE NOT NULL, paymentDueAt DATE NOT NULL, total INT NOT NULL, referenceNumber VARCHAR(50) NOT NULL, file VARCHAR(100) NOT NULL, INDEX IDX_1100635BF639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, years_open VARCHAR(255) NOT NULL, total_seats INT NOT NULL, available_seats INT NOT NULL, deleted_at DATETIME DEFAULT NULL, address_name VARCHAR(255) NOT NULL, address_street_number VARCHAR(255) DEFAULT NULL, address_route VARCHAR(255) DEFAULT NULL, address_locality VARCHAR(255) DEFAULT NULL, address_postal_code VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, INDEX IDX_5E9E89CB2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, slug VARCHAR(20) NOT NULL, route VARCHAR(30) NOT NULL, icon VARCHAR(30) NOT NULL, INDEX IDX_8157AA0FED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_timetable (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, open_day VARCHAR(20) NOT NULL, open_time TIME DEFAULT NULL, close_time TIME DEFAULT NULL, is_open_whole_day TINYINT(1) DEFAULT NULL, INDEX IDX_4864200664D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lead (id INT AUTO_INCREMENT NOT NULL, form_id INT DEFAULT NULL, service_id INT DEFAULT NULL, status VARCHAR(20) DEFAULT NULL, lostReason TINYTEXT DEFAULT NULL, rawData LONGTEXT NOT NULL, function LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', business_name VARCHAR(20) DEFAULT NULL, contact_name VARCHAR(20) DEFAULT NULL, contact_email VARCHAR(50) DEFAULT NULL, contact_phone VARCHAR(20) DEFAULT NULL, campaign_length VARCHAR(20) NOT NULL, estimate_type VARCHAR(50) NOT NULL, estimate_type_option VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, entry_id INT NOT NULL, INDEX IDX_289161CB5FF69B7D (form_id), INDEX IDX_289161CBED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, gravity_form_id INT NOT NULL, raw_data LONGTEXT NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, source VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certifications (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, number VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_3B0D76D52ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contracts_services (id INT AUTO_INCREMENT NOT NULL, contract_id INT DEFAULT NULL, service_id INT DEFAULT NULL, commission_id INT DEFAULT NULL, INDEX IDX_25041FC12576E0FD (contract_id), INDEX IDX_25041FC1ED5CA9E6 (service_id), INDEX IDX_25041FC1202D1EB2 (commission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE award (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, organisation VARCHAR(30) NOT NULL, date DATE NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_8A5B2EE72ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, sent_at DATE NOT NULL, received_at DATE DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, file VARCHAR(255) NOT NULL, payment_due_days INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_E98F28592ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE support_functions ADD CONSTRAINT FK_F57EB6BA2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1032ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE channel_support ADD CONSTRAINT FK_3747D4A42ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE log_entries ADD CONSTRAINT FK_15358B52A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE account_note ADD CONSTRAINT FK_432E7DBE2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE reference ADD CONSTRAINT FK_AEA349132ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE commission_tier ADD CONSTRAINT FK_610F5068202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission_model (id)');
        $this->addSql('ALTER TABLE leads_suppliers ADD CONSTRAINT FK_45A91ECD55458D FOREIGN KEY (lead_id) REFERENCES lead (id)');
        $this->addSql('ALTER TABLE leads_suppliers ADD CONSTRAINT FK_45A91ECD2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6382ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE invoices_payments ADD CONSTRAINT FK_7BDCCC904C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoices_payments ADD CONSTRAINT FK_7BDCCC902989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suppliers_profiles ADD CONSTRAINT FK_6AA319D02ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE suppliers_profiles ADD CONSTRAINT FK_6AA319D0CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E092ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE customers_locations ADD CONSTRAINT FK_2B35F2359395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customers_locations ADD CONSTRAINT FK_2B35F23564D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524D2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE commercial ADD CONSTRAINT FK_7653F3AE2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE lead_notes ADD CONSTRAINT FK_67FC6B0355458D FOREIGN KEY (lead_id) REFERENCES lead (id)');
        $this->addSql('ALTER TABLE lead_notes ADD CONSTRAINT FK_67FC6B032ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174448783C14 FOREIGN KEY (supplier_invoice_id) REFERENCES supplier_invoice (id)');
        $this->addSql('ALTER TABLE minimum_volumes ADD CONSTRAINT FK_473CA3072ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE supplier_invoice ADD CONSTRAINT FK_1100635BF639F774 FOREIGN KEY (campaign_id) REFERENCES leads_suppliers (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE location_timetable ADD CONSTRAINT FK_4864200664D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CB5FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE certifications ADD CONSTRAINT FK_3B0D76D52ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE contracts_services ADD CONSTRAINT FK_25041FC12576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE contracts_services ADD CONSTRAINT FK_25041FC1ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE contracts_services ADD CONSTRAINT FK_25041FC1202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission_model (id)');
        $this->addSql('ALTER TABLE award ADD CONSTRAINT FK_8A5B2EE72ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F28592ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commission_tier DROP FOREIGN KEY FK_610F5068202D1EB2');
        $this->addSql('ALTER TABLE contracts_services DROP FOREIGN KEY FK_25041FC1202D1EB2');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FED5CA9E6');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBED5CA9E6');
        $this->addSql('ALTER TABLE contracts_services DROP FOREIGN KEY FK_25041FC1ED5CA9E6');
        $this->addSql('ALTER TABLE log_entries DROP FOREIGN KEY FK_15358B52A76ED395');
        $this->addSql('ALTER TABLE supplier_invoice DROP FOREIGN KEY FK_1100635BF639F774');
        $this->addSql('ALTER TABLE invoices_payments DROP FOREIGN KEY FK_7BDCCC904C3A3BB');
        $this->addSql('ALTER TABLE customers_locations DROP FOREIGN KEY FK_2B35F2359395C3F3');
        $this->addSql('ALTER TABLE invoices_payments DROP FOREIGN KEY FK_7BDCCC902989F1FD');
        $this->addSql('ALTER TABLE support_functions DROP FOREIGN KEY FK_F57EB6BA2ADD6D8C');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1032ADD6D8C');
        $this->addSql('ALTER TABLE channel_support DROP FOREIGN KEY FK_3747D4A42ADD6D8C');
        $this->addSql('ALTER TABLE account_note DROP FOREIGN KEY FK_432E7DBE2ADD6D8C');
        $this->addSql('ALTER TABLE reference DROP FOREIGN KEY FK_AEA349132ADD6D8C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492ADD6D8C');
        $this->addSql('ALTER TABLE leads_suppliers DROP FOREIGN KEY FK_45A91ECD2ADD6D8C');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6382ADD6D8C');
        $this->addSql('ALTER TABLE suppliers_profiles DROP FOREIGN KEY FK_6AA319D02ADD6D8C');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E092ADD6D8C');
        $this->addSql('ALTER TABLE technology DROP FOREIGN KEY FK_F463524D2ADD6D8C');
        $this->addSql('ALTER TABLE commercial DROP FOREIGN KEY FK_7653F3AE2ADD6D8C');
        $this->addSql('ALTER TABLE lead_notes DROP FOREIGN KEY FK_67FC6B032ADD6D8C');
        $this->addSql('ALTER TABLE minimum_volumes DROP FOREIGN KEY FK_473CA3072ADD6D8C');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB2ADD6D8C');
        $this->addSql('ALTER TABLE certifications DROP FOREIGN KEY FK_3B0D76D52ADD6D8C');
        $this->addSql('ALTER TABLE award DROP FOREIGN KEY FK_8A5B2EE72ADD6D8C');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F28592ADD6D8C');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174448783C14');
        $this->addSql('ALTER TABLE customers_locations DROP FOREIGN KEY FK_2B35F23564D218E');
        $this->addSql('ALTER TABLE location_timetable DROP FOREIGN KEY FK_4864200664D218E');
        $this->addSql('ALTER TABLE suppliers_profiles DROP FOREIGN KEY FK_6AA319D0CCFA12B8');
        $this->addSql('ALTER TABLE leads_suppliers DROP FOREIGN KEY FK_45A91ECD55458D');
        $this->addSql('ALTER TABLE lead_notes DROP FOREIGN KEY FK_67FC6B0355458D');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CB5FF69B7D');
        $this->addSql('ALTER TABLE contracts_services DROP FOREIGN KEY FK_25041FC12576E0FD');
        $this->addSql('DROP TABLE support_functions');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE channel_support');
        $this->addSql('DROP TABLE commission_model');
        $this->addSql('DROP TABLE log_entries');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE account_note');
        $this->addSql('DROP TABLE reference');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE commission_tier');
        $this->addSql('DROP TABLE leads_suppliers');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE invoices_payments');
        $this->addSql('DROP TABLE suppliers_profiles');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customers_locations');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP TABLE commercial');
        $this->addSql('DROP TABLE lead_notes');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE minimum_volumes');
        $this->addSql('DROP TABLE supplier_invoice');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE location_timetable');
        $this->addSql('DROP TABLE lead');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE certifications');
        $this->addSql('DROP TABLE contracts_services');
        $this->addSql('DROP TABLE award');
        $this->addSql('DROP TABLE contract');
    }
}
