<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180602041502 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `service` (`id`, `name`) VALUES (2, 'Virtual Assistant');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Contact', 'contact', 'profile_contacts', 'fa-user', NULL, 1, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Location', 'location', 'profile_locations', 'fa-map-marker', NULL, 2, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Experience', 'experience', 'profile_experience', 'fa-star', NULL, 3, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Legal', 'legal', 'profile_legals', 'fa-shield', NULL, 4, 'Optional');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Current Reference', 'current-reference', 'profile_current_reference', 'fa-circle', NULL, 5, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Past Reference', 'past-reference', 'profile_past_reference', 'fa-circle', NULL, 6, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Customers', 'customer', 'profile_customers', 'fa-users', NULL, 7, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Awards', 'award', 'profile_award', 'fa-shield', NULL, 8, 'Optional');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Technology', 'technology', 'profile_technology', 'fa-shield', NULL, 9, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Channel Support', 'channel-support', 'profile_channel_support', 'fa-shield', NULL, 10, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Commercials', 'commercial', 'profile_commercial', 'fa-shield', NULL, 11, 'Incomplete');");
        $this->addSql("INSERT INTO `profile` (`service_id`, `name`, `slug`, `route`, `icon`, `disabled_text`, `order`, `initial_status`) VALUES (2, 'Data Acquisition', 'data-acquisition', 'profile_data_acquisition', 'fa-shield', NULL, 12, 'Incomplete');");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
