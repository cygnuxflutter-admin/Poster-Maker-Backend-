<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MigrateDB extends CI_Controller {

    public function index() {
        // Create trending_festivals table
        $sql1 = "CREATE TABLE IF NOT EXISTS `trending_festivals` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `app_id` int(11) NOT NULL DEFAULT '1',
            `cat_id` int(11) NOT NULL,
            `banner_image` varchar(255) NOT NULL,
            `sequence_number` int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Create hero_banners table
        $sql2 = "CREATE TABLE IF NOT EXISTS `hero_banners` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `app_id` int(11) NOT NULL DEFAULT '1',
            `banner_image` varchar(255) NOT NULL,
            `action_url` varchar(255) DEFAULT NULL,
            `sequence_number` int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->db->query($sql1);
        echo "Table trending_festivals created successfully.<br>";
        
        $this->db->query($sql2);
        echo "Table hero_banners created successfully.<br>";
    }

    public function add_app_id() {
        $sql1 = "ALTER TABLE `trending_festivals` ADD COLUMN `app_id` INT(11) NOT NULL DEFAULT '1' AFTER `id`;";
        $sql2 = "ALTER TABLE `hero_banners` ADD COLUMN `app_id` INT(11) NOT NULL DEFAULT '1' AFTER `id`;";

        if ($this->db->query($sql1)) {
            echo "Added app_id to trending_festivals.<br>";
        }
        if ($this->db->query($sql2)) {
            echo "Added app_id to hero_banners.<br>";
        }
    }
}
