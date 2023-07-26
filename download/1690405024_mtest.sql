-- MySQL dump 10.19  Distrib 10.3.31-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mtest
-- ------------------------------------------------------
-- Server version	10.3.31-MariaDB-1:10.3.31+maria~bionic-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `employers`
--

DROP TABLE IF EXISTS `employers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` tinyint(3) unsigned NOT NULL,
  `allow_job_editing` tinyint(4) NOT NULL DEFAULT 1,
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `has_dead_email_date` date DEFAULT NULL,
  `has_dead_email` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `reliability_status` tinyint(3) unsigned DEFAULT NULL,
  `suspended_date` date DEFAULT NULL,
  `suspended_app` tinyint(4) NOT NULL DEFAULT 0,
  `suspended_comment` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `email_alternative` varchar(255) NOT NULL DEFAULT '',
  `email_unconfirmed` varchar(255) NOT NULL DEFAULT '',
  `email_alternative_unconfirmed` varchar(255) NOT NULL DEFAULT '',
  `email_confirmation_id` int(11) NOT NULL DEFAULT 0,
  `email_alternative_confirmation_id` int(11) NOT NULL DEFAULT 0,
  `password` char(255) NOT NULL DEFAULT '',
  `password_upgraded` tinyint(1) NOT NULL DEFAULT 0,
  `reg_ip` varbinary(16) DEFAULT NULL,
  `registration_user_agent` varchar(255) DEFAULT NULL,
  `confirm_ip` varbinary(16) DEFAULT NULL,
  `confirmation_user_agent` varchar(255) DEFAULT NULL,
  `confirmation_time` datetime DEFAULT NULL,
  `confirmation_ip_time` datetime DEFAULT NULL,
  `login_ip` varbinary(16) DEFAULT NULL,
  `last_login_user_agent` varchar(255) DEFAULT NULL,
  `last_login_country_id` smallint(10) unsigned DEFAULT NULL,
  `last_login_country_geoip_id` int(10) unsigned DEFAULT NULL,
  `last_login_user_time` datetime DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `last_login_ip_time` datetime DEFAULT NULL,
  `country_id` smallint(10) unsigned NOT NULL DEFAULT 0,
  `state_id` mediumint(10) unsigned NOT NULL DEFAULT 0,
  `city_id` int(10) unsigned NOT NULL DEFAULT 0,
  `address` text DEFAULT NULL,
  `postal_code` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `show_website` tinyint(1) NOT NULL DEFAULT 0,
  `website_clicks` int(1) unsigned NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `hide_location` tinyint(1) NOT NULL DEFAULT 0,
  `cc_allowed` tinyint(1) DEFAULT 1,
  `logo_ext` varchar(5) NOT NULL DEFAULT '',
  `logo_time` char(14) DEFAULT '',
  `is_top` tinyint(1) NOT NULL DEFAULT 0,
  `top_set_time` datetime DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `registration_user_time` datetime DEFAULT NULL,
  `registration_ip_time` datetime DEFAULT NULL,
  `preferred_app_language_id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `is_advertised` tinyint(4) NOT NULL DEFAULT 0,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT 0,
  `first_job_creation_date` datetime DEFAULT NULL,
  `had_registration_cookie` tinyint(1) NOT NULL DEFAULT 0,
  `contact_views_ban_time` datetime DEFAULT NULL,
  `contact_view_limit_type` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `override_allow_xml_jobs` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `jobs_no_structured_data` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `xml_failure_notifications_disabled` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `is_renowned` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `suspicion_points` float NOT NULL DEFAULT 0,
  `suspicion_reported` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `second_job_id` int(10) unsigned NOT NULL DEFAULT 0,
  `suspicion_limit` float NOT NULL DEFAULT 0,
  `mass_upd_show_date` datetime DEFAULT NULL,
  `vat_nr` varchar(50) NOT NULL DEFAULT '0',
  `no_vat` tinyint(1) NOT NULL DEFAULT 0,
  `no_expiring_job_notification` tinyint(1) NOT NULL DEFAULT 0,
  `no_expired_job_notification` tinyint(1) NOT NULL DEFAULT 0,
  `no_login_time_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `upgradable_without_jobs` tinyint(1) NOT NULL DEFAULT 0,
  `hide_name` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `is_advertised` (`is_advertised`),
  KEY `email` (`email`),
  KEY `email_confirmation_id` (`email_confirmation_id`),
  KEY `is_suspended` (`is_suspended`),
  KEY `show_website` (`show_website`),
  KEY `email_unconfirmed` (`email_unconfirmed`),
  KEY `is_renowned` (`is_renowned`),
  KEY `is_top` (`is_top`,`top_set_time`),
  KEY `reg_ip` (`reg_ip`),
  KEY `confirm_ip` (`confirm_ip`),
  KEY `login_ip` (`login_ip`),
  KEY `email_alternative_confirmation_id` (`email_alternative_confirmation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=226245 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employers`
--

LOCK TABLES `employers` WRITE;
/*!40000 ALTER TABLE `employers` DISABLE KEYS */;
INSERT INTO `employers` VALUES (226244,1,1,0,NULL,0,3,NULL,0,'','/*[not(.//company[contains(.,\'Vertex Education\') or contains(.,\'Mcdonald\') or contains(.,\'McDonald\') or contains(.,\'MCDONALD\') or contains(.,\'Holcomb Behavioral Health Systems\') or contains(.,\'Chimes\') or contains(translate(., \'CAREBUILD\', \'Carebuild\'),\'Careerbuilder\')])]','MyJobHelper, USA 3 (320k)','myjobhelper3@learn4good.com','','','',0,0,'$2y$10$O0mzBDs8DuYJls/JAr0pKusDhH8k4IU5bj.W9sCA/HsbrFly34jJS',1,'Xv�|','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',NULL,NULL,'2021-01-09 13:46:19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,500,1,8803,'local','','1111111111111111','',0,0,'Information Services:  \r\nMeet the Fastest Growing Job Site in America. Learn how MyJobHelper helps job boards, agencies and employers attract top talent in over 15 different countries.',1,1,'','',0,NULL,'2021-01-09 13:45:54','2021-01-09 20:45:54','2021-01-09 20:45:54',1,0,0,'2021-09-01 13:55:34',0,NULL,0,0,0,1,0,0,0,200389067,20,NULL,'',0,1,1,1,0,1);
/*!40000 ALTER TABLE `employers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_selected_categories`
--

DROP TABLE IF EXISTS `job_selected_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_selected_categories` (
  `job_id` int(10) unsigned NOT NULL,
  `job_category_id` int(10) unsigned NOT NULL,
  `order_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`job_id`,`job_category_id`),
  KEY `category` (`job_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_selected_categories`
--

LOCK TABLES `job_selected_categories` WRITE;
/*!40000 ALTER TABLE `job_selected_categories` DISABLE KEYS */;
INSERT INTO `job_selected_categories` VALUES (256232721,58,1);
/*!40000 ALTER TABLE `job_selected_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `employer_id` int(10) unsigned NOT NULL DEFAULT 0,
  `is_suspended` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `display_status` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `english_only` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `list_website` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `apply_using_link` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `posting_app_language_id` smallint(3) unsigned NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `employer_contact_method_id` tinyint(5) unsigned NOT NULL DEFAULT 0,
  `contact_name` varchar(255) NOT NULL DEFAULT '',
  `contact_phone` varchar(255) NOT NULL DEFAULT '',
  `website` text NOT NULL,
  `title` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `unfiltered_title` text DEFAULT NULL,
  `description` mediumtext NOT NULL,
  `benefits` mediumtext NOT NULL,
  `skills` mediumtext NOT NULL,
  `reference_number` varchar(255) NOT NULL DEFAULT '',
  `country_id` smallint(8) unsigned NOT NULL DEFAULT 0,
  `state_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `county_id` int(10) unsigned NOT NULL DEFAULT 0,
  `city_id` int(8) unsigned NOT NULL DEFAULT 0,
  `is_online` tinyint(1) unsigned NOT NULL,
  `zip_code` char(32) NOT NULL DEFAULT '',
  `geocity_name` varchar(255) DEFAULT NULL,
  `geocity_region` varchar(255) DEFAULT NULL,
  `additional_locations` text NOT NULL,
  `education_level_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `experience_level_id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hide_contact_name` tinyint(1) NOT NULL DEFAULT 0,
  `salary_amount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Shows yearly salary. multiply given salary with period multiplier to get yearly salary',
  `salary_currency_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `salary_period_id` tinyint(5) unsigned NOT NULL DEFAULT 1,
  `unified_salary_amount` float NOT NULL DEFAULT 0,
  `modification_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `original_creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `top_until` date DEFAULT NULL,
  `xml_id` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `xml_url_id` int(10) unsigned NOT NULL DEFAULT 0,
  `xml_md5` char(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ssdeep_hash` varchar(255) NOT NULL DEFAULT '',
  `original_job_category_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Indicates category used for job URL and it was selected on initial job posting',
  `employer_name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hide_employer` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `logo_file` varchar(38) NOT NULL DEFAULT '',
  `total_views` int(11) NOT NULL DEFAULT 0,
  `applicant_filter_id` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `expiry_date` (`expiry_date`),
  KEY `display_status` (`display_status`),
  KEY `for_many_job_lists` (`domain_id`,`is_suspended`,`expiry_date`,`posting_app_language_id`,`display_status`,`country_id`),
  KEY `xml_url_id` (`xml_url_id`),
  KEY `is_online` (`is_online`),
  KEY `employer_id_xml_id` (`employer_id`,`xml_id`),
  KEY `for_emp_job_lists_online_count` (`employer_id`,`is_suspended`,`expiry_date`),
  KEY `logo_file` (`logo_file`),
  KEY `job_match` (`original_creation_date`,`expiry_date`,`is_suspended`,`domain_id`,`display_status`,`id`),
  KEY `applicant_filter_id` (`applicant_filter_id`),
  KEY `modification_timestamp` (`modification_timestamp`),
  KEY `employer_id_modification_timestamp` (`employer_id`,`modification_timestamp`),
  KEY `top_until_modification_timestamp` (`top_until`,`modification_timestamp`),
  KEY `city_id_modification_timestamp` (`city_id`,`modification_timestamp`),
  KEY `country_id_modification_timestamp` (`country_id`,`modification_timestamp`),
  KEY `county_id_modification_timestamp` (`county_id`,`modification_timestamp`),
  KEY `state_id_modification_timestamp` (`state_id`,`modification_timestamp`),
  KEY `employer_name` (`employer_name`),
  KEY `employer_name_2` (`employer_name`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=256232722 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (256232721,1,226244,0,0,1,1,1,1,'',7,'','','https://click.appcast.io/track/7dh5wkf?cs=jap&jg=2ze0&bid=rZ-ZBYiOhiPQiAeYFatKww==','GM and Food/General Merchandise, Closing, Fulfillment, Inbound, Food & Beverage','GM and Food (General Merchandise, Closing, Fulfillment, Inbound, Food & Beverage (T0616)\n\\([a-zA-Z]{1,4}\\d+\\) => \'GM and Food (General Merchandise, Closing, Fulfillment, Inbound, Food & Beverage \'','Position:  GM and Food (General Merchandise, Closing, Fulfillment, Inbound, Food & Beverage (T0616)\nALL ABOUT TARGET\n\nAs part of our collaborative and guest-obsessed team, you help us create an experience that makes guests say \"I love Target!\" When you work at Target, you\'re helping every family discover the joy in everyday life. You\'re working alongside a dedicated team that brings their passion and pride to all that they do. We delight our guests with area experts ready to assist with items that are in-stock and priced accurately ensuring guests have an enjoyable experience both in stores and online.\n\nALL ABOUT GENERAL MERCHANDISE AND FOOD & BEVERAGE\n\nExperts of operations, process and efficiency who enable a consistent experience for our guests by ensuring product is set, in-stock, accurately priced and signed on the sales floor. The General Merchandise and Food Sales team leads inbound, outbound, replenishment, inventory accuracy, presentation, pricing, and promotional signing processes for all GM areas of the store. This team leads Food & Beverage, and Food Service, providing a fresh and food safe experience. Experts enable efficient delivery to our guests by owning pick, pack, and ship fulfillment work.\n\nAt Target we believe in our team members having meaningful experiences that help them build and develop skills for a career. These roles can provide you with the:\n\n* Knowledge of guest service fundamentals and experience supporting a guest first culture across the store\n* Experience in retail business fundamentals: department sales trends, inventory management, and process efficiency and improvement\n* Experience executing daily/weekly workload to support business priorities and deliver on sales goals\n* Knowledge in food seasonality, freshness and quality, food safety standards and routines, and merchandising\n\nWHAT WE ARE LOOKING FOR\n\nWe might be a great match if:\n\n* Working in a fun and energetic environment makes you excitedâ€¦ We work efficiently and as a team to deliver for our guests\n* Providing service to our guests that makes them say I LOVE TARGET! excites youâ€¦ That\'s why we love working at Target\n* Stocking, Setting and Selling Target products sounds like your thingâ€¦ That\'s the core of what we do\n* You aren\'t looking for a Monday thru Friday job where you are at a computer all dayâ€¦ We are busy all day (especially on the weekends), making it easy for the guest to feel welcomed, inspired and rewarded\n\nThe good news is that we have some amazing training that will help teach you everything you need to know. But there are a few skills you should have from the get-go:\n\n* Welcoming and helpful attitude toward guests and other team members\n* Learn and adapt to current technology needs\n* Work both independently and with a team\n* Resolve guest questions quickly on the spot\n* Attention to detail and follow a multi-step process\n\nWe are an awesome place to work and care about our teams, so we want to make sure we are clear on a few more basics that we expect:\n\n* Accurately handle cash register operations\n* Climb up and down ladders\n* Scan, handle and move merchandise efficiently and safely, including frequently lifting or moving merchandise up to 40 pounds\n* Flexible work schedule (e.g., nights, weekends and holidays) and regular attendance necessary\n\nRoles Include:\n\n* General Merchandise Expert\n* Closing Expert\n* Presentation Expert\n* Fulfillment Expert\n* Inbound Expert\n* Reverse Logistics Expert\n* Food Service Expert\n* Starbucks Barista\n* Food & Beverage Expert\n* Food & Beverage Expert - Adult Bev (limited stores only)\n\nAmericans with Disabilities Act (ADA)\n\nTarget will provide reasonable accommodations (such as a qualified sign language interpreter or other personal assistance) with the application process upon your request as required to comply with applicable laws. If you have a disability and require assistance in this application process, please visit your nearest Target store or Distribution Center or reach out to Guest Services  for additional information.','','','',500,51,1256,10151,0,'48907',NULL,NULL,'',1,1,0,0.00,0,1,0,'2021-03-28 05:26:17','2021-03-28 01:26:17','2021-09-28','0000-00-00','9121_MTkxOToxODI0ZjVmZWQwZDg5MTZmYzUzZmFmMTU0MGY4ZGNjOA',287,'0eee38f38889552022af3d7c35f240c3','',58,'Target',1,'',0,20120341);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-27 22:17:50
