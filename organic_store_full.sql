WARNING: option --ssl-verify-server-cert is disabled, because of an insecure passwordless login.
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.20-12.3.3-MariaDB, for osx10.20 (arm64)
--
-- Host: 127.0.0.1    Database: organic_store
-- ------------------------------------------------------
-- Server version	12.3.3-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `label` enum('home','office','other') NOT NULL DEFAULT 'home',
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `house_no` varchar(255) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(120) NOT NULL,
  `state` varchar(120) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_is_default_index` (`user_id`,`is_default`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES
(1,7,'home','Ankita Mohanty','9437011111','House 11','Sahid Nagar Main Road','Saheed Nagar','Near Community Centre','Bhubaneswar','Odisha','751007',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(2,8,'home','Rahul Panda','9437022222','House 12','Sahid Nagar Main Road','Saheed Nagar','Near Community Centre','Bhubaneswar','Odisha','751007',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(3,9,'home','Sneha Rath','9437033333','House 13','Sahid Nagar Main Road','Saheed Nagar','Near Community Centre','Bhubaneswar','Odisha','751007',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(4,10,'home','Debashish Nayak','9437044444','House 14','Sahid Nagar Main Road','Saheed Nagar','Near Community Centre','Bhubaneswar','Odisha','751007',1,'2026-08-26 14:41:25','2026-08-26 14:41:25'),
(5,11,'home','Priyanka Sahoo','9437055555','House 15','Sahid Nagar Main Road','Saheed Nagar','Near Community Centre','Bhubaneswar','Odisha','751007',1,'2026-08-26 14:41:25','2026-08-26 14:41:25'),
(6,1,'home','subrat Kumar sahoo','9348225868','dsfsd','at-budhimari, Po -sartha, Dist -balasore','sds','sfsdf','Baleswar','Odisha','756027',1,'2026-08-27 02:23:25','2026-08-27 02:23:25'),
(7,13,'home','subrat Kumar sahoo','9348225868','hj','at-budhimari, Po -sartha, Dist -balasore',NULL,NULL,'Baleswar','Odisha','756027',1,'2026-09-01 05:27:19','2026-09-01 05:27:19');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(40) NOT NULL DEFAULT 'order',
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `icon` varchar(30) NOT NULL DEFAULT 'shopping-bag',
  `color` varchar(30) NOT NULL DEFAULT 'forest',
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_notifications_read_at_created_at_index` (`read_at`,`created_at`),
  KEY `admin_notifications_order_id_index` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES
(2,'order','New order ORD-2026-000006','Subrat Admin placed an order of ?3,022 (5 items)','shopping-bag','forest',6,'{\"grand_total\":\"3022.00\",\"customer\":\"Subrat Admin\",\"order_number\":\"ORD-2026-000006\",\"items\":5,\"phone\":\"9348225868\",\"city\":\"Baleswar\",\"placed_at\":\"2026-08-27T07:53:28+00:00\"}',NULL,'2026-08-27 02:23:28','2026-08-27 02:23:28'),
(3,'status','Order ORD-2026-000006 ? Confirmed','Order moved from Pending to Confirmed.','refresh-cw','sky',6,'{\"order_number\":\"ORD-2026-000006\",\"from\":\"pending\",\"to\":\"confirmed\",\"status\":\"confirmed\"}',NULL,'2026-08-27 02:24:33','2026-08-27 02:24:33'),
(4,'order','New order ORD-2026-000007','Subrat Admin placed an order of ₹689 (1 item)','shopping-bag','forest',7,'{\"grand_total\":\"689.00\",\"customer\":\"Subrat Admin\",\"order_number\":\"ORD-2026-000007\",\"items\":1,\"phone\":\"9348225868\",\"city\":\"Baleswar\",\"placed_at\":\"2026-09-01T09:43:50+00:00\"}',NULL,'2026-09-01 04:13:50','2026-09-01 04:13:50'),
(5,'order','New order ORD-2026-000008','subrat Kumar sahoo placed an order of ₹1,643 (2 items)','shopping-bag','forest',8,'{\"grand_total\":\"1643.00\",\"customer\":\"subrat Kumar sahoo\",\"order_number\":\"ORD-2026-000008\",\"items\":2,\"phone\":\"9348225868\",\"city\":\"Baleswar\",\"placed_at\":\"2026-09-01T10:57:21+00:00\"}',NULL,'2026-09-01 05:27:21','2026-09-01 05:27:21'),
(6,'order','New order ORD-2026-000009','Subrat Admin placed an order of ₹681 (2 items)','shopping-bag','forest',9,'{\"grand_total\":\"680.50\",\"customer\":\"Subrat Admin\",\"order_number\":\"ORD-2026-000009\",\"items\":2,\"phone\":\"9348225868\",\"city\":\"Baleswar\",\"placed_at\":\"2026-09-01T17:09:16+00:00\"}',NULL,'2026-09-01 11:39:16','2026-09-01 11:39:16'),
(7,'order','New order ORD-2026-000010','Subrat Admin placed an order of ₹916 (1 item)','shopping-bag','forest',10,'{\"grand_total\":\"916.00\",\"customer\":\"Subrat Admin\",\"order_number\":\"ORD-2026-000010\",\"items\":1,\"phone\":\"9348225868\",\"city\":\"Baleswar\",\"placed_at\":\"2026-09-01T17:44:03+00:00\"}',NULL,'2026-09-01 12:14:03','2026-09-01 12:14:03'),
(8,'status','Order ORD-2026-000010 → Confirmed','Order moved from Pending to Confirmed.','refresh-cw','sky',10,'{\"order_number\":\"ORD-2026-000010\",\"from\":\"pending\",\"to\":\"confirmed\",\"status\":\"confirmed\"}',NULL,'2026-09-01 12:15:12','2026-09-01 12:15:12'),
(9,'order','New order ORD-2026-000001','Subrat Admin placed an order of ₹7,470 (1 item)','shopping-bag','forest',11,'{\"grand_total\":\"7470.00\",\"customer\":\"Subrat Admin\",\"order_number\":\"ORD-2026-000001\",\"items\":1,\"phone\":\"9348225868\",\"city\":\"Baleswar\",\"placed_at\":\"2026-09-04T03:13:56+00:00\"}',NULL,'2026-09-03 21:43:56','2026-09-03 21:43:56'),
(10,'status','Order ORD-2026-000001 → Confirmed','Order moved from Pending to Confirmed.','refresh-cw','sky',11,'{\"order_number\":\"ORD-2026-000001\",\"from\":\"pending\",\"to\":\"confirmed\",\"status\":\"confirmed\"}',NULL,'2026-09-03 23:54:15','2026-09-03 23:54:15'),
(11,'status','Order ORD-2026-000001 → Preparing','Order moved from Confirmed to Preparing.','refresh-cw','sky',11,'{\"order_number\":\"ORD-2026-000001\",\"from\":\"confirmed\",\"to\":\"preparing\",\"status\":\"preparing\"}',NULL,'2026-09-03 23:54:47','2026-09-03 23:54:47'),
(12,'status','Order ORD-2026-000001 → Packed','Order moved from Preparing to Packed.','refresh-cw','sky',11,'{\"order_number\":\"ORD-2026-000001\",\"from\":\"preparing\",\"to\":\"packed\",\"status\":\"packed\"}',NULL,'2026-09-03 23:55:21','2026-09-03 23:55:21'),
(13,'status','Order ORD-2026-000001 → Out for Delivery','Order moved from Assigned to Out for Delivery.','refresh-cw','sky',11,'{\"order_number\":\"ORD-2026-000001\",\"from\":\"assigned\",\"to\":\"out_for_delivery\",\"status\":\"out_for_delivery\"}',NULL,'2026-09-04 00:04:45','2026-09-04 00:04:45'),
(14,'status','Order ORD-2026-000001 → Out for Delivery','Order moved from Assigned to Out for Delivery.','refresh-cw','sky',11,'{\"order_number\":\"ORD-2026-000001\",\"from\":\"assigned\",\"to\":\"out_for_delivery\",\"status\":\"out_for_delivery\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17'),
(15,'status','Order ORD-2026-000001 → Delivered','Order moved from Out for Delivery to Delivered.','refresh-cw','sky',11,'{\"order_number\":\"ORD-2026-000001\",\"from\":\"out_for_delivery\",\"to\":\"delivered\",\"status\":\"delivered\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17');
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `role_label` varchar(64) DEFAULT NULL,
  `action` varchar(64) NOT NULL,
  `subject_type` varchar(128) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `subject_label` varchar(255) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `audit_logs_created_at_index` (`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `placement` enum('hero','strip','category_top','promotional') NOT NULL DEFAULT 'hero',
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `desktop_image` varchar(255) DEFAULT NULL,
  `mobile_image` varchar(255) DEFAULT NULL,
  `button_text` varchar(64) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_text` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES
(22,'hero','sdad',NULL,'banners/6a9ad0476d0997.24077906.png',NULL,NULL,NULL,1600,500,NULL,NULL,1,1,0,'2026-09-04 07:48:41','2026-09-04 08:35:59'),
(23,'hero','DSDA',NULL,'banners/6a9ad01784b5d9.74697946.png',NULL,NULL,NULL,1600,500,NULL,NULL,0,1,0,'2026-09-04 08:34:38','2026-09-04 08:35:11');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES
(1,'AB Organic Farm Own','ab-organic-farm','brands/ab-organic-farm.jpg',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(2,'Sattva Organics','sattva-organics','brands/sattva-organics.jpg',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(3,'GreenRoot Co-op','greenroot-coop','brands/greenroot.jpg',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(4,'Nature Basket Farms','nature-basket-farms','brands/gNcIyFGtyj3ET4wglQUG39Uy27ksirl5LKZqdczj.jpg',1,'2026-08-26 14:41:24','2026-08-27 01:47:13');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('ab-organic-farm-cache-nav.categories','O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:0;O:19:\"App\\Models\\Category\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:26:{s:2:\"id\";i:22;s:9:\"parent_id\";N;s:4:\"name\";s:5:\"Combo\";s:4:\"slug\";s:5:\"combo\";s:11:\"description\";N;s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";N;s:17:\"banner_subheading\";N;s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";N;s:10:\"brand_logo\";N;s:10:\"brand_name\";N;s:8:\"sections\";N;s:10:\"created_at\";N;s:10:\"updated_at\";N;s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:26:{s:2:\"id\";i:22;s:9:\"parent_id\";N;s:4:\"name\";s:5:\"Combo\";s:4:\"slug\";s:5:\"combo\";s:11:\"description\";N;s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";N;s:17:\"banner_subheading\";N;s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";N;s:10:\"brand_logo\";N;s:10:\"brand_name\";N;s:8:\"sections\";N;s:10:\"created_at\";N;s:10:\"updated_at\";N;s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:10:\"deleted_at\";s:8:\"datetime\";s:9:\"is_active\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:8:\"sections\";s:5:\"array\";s:13:\"banner_images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:22:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:11:\"description\";i:4;s:10:\"image_path\";i:5;s:10:\"card_image\";i:6;s:4:\"icon\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";i:9;s:11:\"is_featured\";i:10;s:9:\"seo_title\";i:11;s:16:\"meta_description\";i:12;s:14:\"banner_heading\";i:13;s:17:\"banner_subheading\";i:14;s:12:\"banner_image\";i:15;s:13:\"banner_images\";i:16;s:15:\"banner_cta_text\";i:17;s:14:\"banner_cta_url\";i:18;s:15:\"banner_bg_color\";i:19;s:10:\"brand_logo\";i:20;s:10:\"brand_name\";i:21;s:8:\"sections\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:1;O:19:\"App\\Models\\Category\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:26:{s:2:\"id\";i:16;s:9:\"parent_id\";N;s:4:\"name\";s:4:\"Ghee\";s:4:\"slug\";s:4:\"ghee\";s:11:\"description\";s:77:\"Certified organic desi ghee — traditional bilona method, rich and aromatic.\";s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:1;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:1;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";s:14:\"Pure Desi Ghee\";s:17:\"banner_subheading\";s:55:\"Traditional bilona-churned, certified organic cow ghee.\";s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";s:7:\"#00584b\";s:10:\"brand_logo\";N;s:10:\"brand_name\";s:12:\"Organic Ghee\";s:8:\"sections\";N;s:10:\"created_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"updated_at\";s:19:\"2026-09-02 16:38:08\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:26:{s:2:\"id\";i:16;s:9:\"parent_id\";N;s:4:\"name\";s:4:\"Ghee\";s:4:\"slug\";s:4:\"ghee\";s:11:\"description\";s:77:\"Certified organic desi ghee — traditional bilona method, rich and aromatic.\";s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:1;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:1;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";s:14:\"Pure Desi Ghee\";s:17:\"banner_subheading\";s:55:\"Traditional bilona-churned, certified organic cow ghee.\";s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";s:7:\"#00584b\";s:10:\"brand_logo\";N;s:10:\"brand_name\";s:12:\"Organic Ghee\";s:8:\"sections\";N;s:10:\"created_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"updated_at\";s:19:\"2026-09-02 16:38:08\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:10:\"deleted_at\";s:8:\"datetime\";s:9:\"is_active\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:8:\"sections\";s:5:\"array\";s:13:\"banner_images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:22:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:11:\"description\";i:4;s:10:\"image_path\";i:5;s:10:\"card_image\";i:6;s:4:\"icon\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";i:9;s:11:\"is_featured\";i:10;s:9:\"seo_title\";i:11;s:16:\"meta_description\";i:12;s:14:\"banner_heading\";i:13;s:17:\"banner_subheading\";i:14;s:12:\"banner_image\";i:15;s:13:\"banner_images\";i:16;s:15:\"banner_cta_text\";i:17;s:14:\"banner_cta_url\";i:18;s:15:\"banner_bg_color\";i:19;s:10:\"brand_logo\";i:20;s:10:\"brand_name\";i:21;s:8:\"sections\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:2;O:19:\"App\\Models\\Category\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:26:{s:2:\"id\";i:20;s:9:\"parent_id\";N;s:4:\"name\";s:3:\"Oil\";s:4:\"slug\";s:3:\"oil\";s:11:\"description\";s:62:\"Cold-pressed and kachi ghani organic oils, pure and unrefined.\";s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:2;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:1;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";s:17:\"Cold-Pressed Oils\";s:17:\"banner_subheading\";s:47:\"Wood-pressed, naturally extracted organic oils.\";s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";s:7:\"#00584b\";s:10:\"brand_logo\";N;s:10:\"brand_name\";s:12:\"Organic Oils\";s:8:\"sections\";N;s:10:\"created_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"updated_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:26:{s:2:\"id\";i:20;s:9:\"parent_id\";N;s:4:\"name\";s:3:\"Oil\";s:4:\"slug\";s:3:\"oil\";s:11:\"description\";s:62:\"Cold-pressed and kachi ghani organic oils, pure and unrefined.\";s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:2;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:1;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";s:17:\"Cold-Pressed Oils\";s:17:\"banner_subheading\";s:47:\"Wood-pressed, naturally extracted organic oils.\";s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";s:7:\"#00584b\";s:10:\"brand_logo\";N;s:10:\"brand_name\";s:12:\"Organic Oils\";s:8:\"sections\";N;s:10:\"created_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"updated_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:10:\"deleted_at\";s:8:\"datetime\";s:9:\"is_active\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:8:\"sections\";s:5:\"array\";s:13:\"banner_images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:22:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:11:\"description\";i:4;s:10:\"image_path\";i:5;s:10:\"card_image\";i:6;s:4:\"icon\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";i:9;s:11:\"is_featured\";i:10;s:9:\"seo_title\";i:11;s:16:\"meta_description\";i:12;s:14:\"banner_heading\";i:13;s:17:\"banner_subheading\";i:14;s:12:\"banner_image\";i:15;s:13:\"banner_images\";i:16;s:15:\"banner_cta_text\";i:17;s:14:\"banner_cta_url\";i:18;s:15:\"banner_bg_color\";i:19;s:10:\"brand_logo\";i:20;s:10:\"brand_name\";i:21;s:8:\"sections\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:3;O:19:\"App\\Models\\Category\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:26:{s:2:\"id\";i:21;s:9:\"parent_id\";N;s:4:\"name\";s:4:\"Atta\";s:4:\"slug\";s:4:\"atta\";s:11:\"description\";s:73:\"Stone-ground organic flours and atta — fresh, wholesome, chemical-free.\";s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:3;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:1;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";s:17:\"Stone-Ground Atta\";s:17:\"banner_subheading\";s:46:\"Certified organic flours milled fresh for you.\";s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";s:7:\"#00584b\";s:10:\"brand_logo\";N;s:10:\"brand_name\";s:12:\"Organic Atta\";s:8:\"sections\";N;s:10:\"created_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"updated_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"deleted_at\";N;}s:11:\"\0*\0original\";a:26:{s:2:\"id\";i:21;s:9:\"parent_id\";N;s:4:\"name\";s:4:\"Atta\";s:4:\"slug\";s:4:\"atta\";s:11:\"description\";s:73:\"Stone-ground organic flours and atta — fresh, wholesome, chemical-free.\";s:10:\"image_path\";N;s:10:\"card_image\";N;s:4:\"icon\";N;s:10:\"sort_order\";i:3;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:1;s:9:\"seo_title\";N;s:16:\"meta_description\";N;s:14:\"banner_heading\";s:17:\"Stone-Ground Atta\";s:17:\"banner_subheading\";s:46:\"Certified organic flours milled fresh for you.\";s:12:\"banner_image\";N;s:13:\"banner_images\";N;s:15:\"banner_cta_text\";N;s:14:\"banner_cta_url\";N;s:15:\"banner_bg_color\";s:7:\"#00584b\";s:10:\"brand_logo\";N;s:10:\"brand_name\";s:12:\"Organic Atta\";s:8:\"sections\";N;s:10:\"created_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"updated_at\";s:19:\"2026-09-02 16:19:49\";s:10:\"deleted_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:10:\"deleted_at\";s:8:\"datetime\";s:9:\"is_active\";s:7:\"boolean\";s:11:\"is_featured\";s:7:\"boolean\";s:8:\"sections\";s:5:\"array\";s:13:\"banner_images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:22:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:11:\"description\";i:4;s:10:\"image_path\";i:5;s:10:\"card_image\";i:6;s:4:\"icon\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";i:9;s:11:\"is_featured\";i:10;s:9:\"seo_title\";i:11;s:16:\"meta_description\";i:12;s:14:\"banner_heading\";i:13;s:17:\"banner_subheading\";i:14;s:12:\"banner_image\";i:15;s:13:\"banner_images\";i:16;s:15:\"banner_cta_text\";i:17;s:14:\"banner_cta_url\";i:18;s:15:\"banner_bg_color\";i:19;s:10:\"brand_logo\";i:20;s:10:\"brand_name\";i:21;s:8:\"sections\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1788533307),
('ab-organic-farm-cache-page.cancellation-policy','O:15:\"App\\Models\\Page\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"pages\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:5;s:4:\"slug\";s:19:\"cancellation-policy\";s:5:\"title\";s:19:\"Cancellation Policy\";s:5:\"short\";s:40:\"Change your mind? Here is how to cancel.\";s:4:\"hero\";s:27:\"Plans change. We understand\";s:4:\"icon\";s:8:\"x-circle\";s:4:\"lede\";s:126:\"Pressed the button too soon, or your plans changed? We make cancelling an order simple — and free of charge whenever we can.\";s:8:\"sections\";s:916:\"[{\"heading\":\"Cancelling before dispatch\",\"icon\":\"package-open\",\"body\":\"Cancel an order free of charge any time before it is dispatched. Simply go to My Orders and tap Cancel, or message support with your order ID.\"},{\"heading\":\"Cancellation after dispatch\",\"icon\":\"truck\",\"body\":\"Once an order has left our facility we cannot stop it in transit, but you can refuse delivery or request a return within 48 hours \\u2014 we issue a full refund once the item returns to us.\"},{\"heading\":\"Pre-order & custom items\",\"icon\":\"calendar-x\",\"body\":\"Pre-orders and custom or wholesale orders already in production cannot be cancelled. These are always flagged clearly at checkout before you commit.\"},{\"heading\":\"Refunds on cancellation\",\"icon\":\"banknote\",\"body\":\"Refunds for pre-dispatch cancellations are processed to your original payment method within 3\\u20135 working days. Store credit is issued instantly if you prefer.\"}]\";s:4:\"faqs\";s:335:\"[{\"q\":\"Can I cancel and reorder immediately?\",\"a\":\"Yes. Cancel in My Orders, then place a new order right away. Cancellation before dispatch is instant.\"},{\"q\":\"Is there a fee to cancel?\",\"a\":\"No cancellation fee applies before dispatch. After dispatch a return may incur a standard reverse-logistics fee unless the item was faulty.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:5;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:5;s:4:\"slug\";s:19:\"cancellation-policy\";s:5:\"title\";s:19:\"Cancellation Policy\";s:5:\"short\";s:40:\"Change your mind? Here is how to cancel.\";s:4:\"hero\";s:27:\"Plans change. We understand\";s:4:\"icon\";s:8:\"x-circle\";s:4:\"lede\";s:126:\"Pressed the button too soon, or your plans changed? We make cancelling an order simple — and free of charge whenever we can.\";s:8:\"sections\";s:916:\"[{\"heading\":\"Cancelling before dispatch\",\"icon\":\"package-open\",\"body\":\"Cancel an order free of charge any time before it is dispatched. Simply go to My Orders and tap Cancel, or message support with your order ID.\"},{\"heading\":\"Cancellation after dispatch\",\"icon\":\"truck\",\"body\":\"Once an order has left our facility we cannot stop it in transit, but you can refuse delivery or request a return within 48 hours \\u2014 we issue a full refund once the item returns to us.\"},{\"heading\":\"Pre-order & custom items\",\"icon\":\"calendar-x\",\"body\":\"Pre-orders and custom or wholesale orders already in production cannot be cancelled. These are always flagged clearly at checkout before you commit.\"},{\"heading\":\"Refunds on cancellation\",\"icon\":\"banknote\",\"body\":\"Refunds for pre-dispatch cancellations are processed to your original payment method within 3\\u20135 working days. Store credit is issued instantly if you prefer.\"}]\";s:4:\"faqs\";s:335:\"[{\"q\":\"Can I cancel and reorder immediately?\",\"a\":\"Yes. Cancel in My Orders, then place a new order right away. Cancellation before dispatch is instant.\"},{\"q\":\"Is there a fee to cancel?\",\"a\":\"No cancellation fee applies before dispatch. After dispatch a return may incur a standard reverse-logistics fee unless the item was faulty.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:5;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"sections\";s:5:\"array\";s:4:\"faqs\";s:5:\"array\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:4:\"slug\";i:1;s:5:\"title\";i:2;s:5:\"short\";i:3;s:4:\"hero\";i:4;s:4:\"icon\";i:5;s:4:\"lede\";i:6;s:8:\"sections\";i:7;s:4:\"faqs\";i:8;s:9:\"is_active\";i:9;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',1788496274),
('ab-organic-farm-cache-page.privacy-policy','O:15:\"App\\Models\\Page\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"pages\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:1;s:4:\"slug\";s:14:\"privacy-policy\";s:5:\"title\";s:14:\"Privacy Policy\";s:5:\"short\";s:58:\"How we collect, use and protect your personal information.\";s:4:\"hero\";s:28:\"Your data, handled with care\";s:4:\"icon\";s:12:\"shield-check\";s:4:\"lede\";s:213:\"At AB Organic Farm, we treat your trust as seriously as we treat our soil. This policy explains exactly what we collect, why, and the measures we take to keep your personal information safe, private and protected.\";s:8:\"sections\";s:1282:\"[{\"heading\":\"Information we collect\",\"icon\":\"database\",\"body\":\"We collect only what is needed to serve you: your name, delivery address, phone number, email, order history and payment details. When you create an account or place an order we also store preferences so we can personalise your farm favourites.\"},{\"heading\":\"How we use your information\",\"icon\":\"sparkles\",\"body\":\"Your details power your orders \\u2014 processing payments, arranging delivery, sending updates and resolving queries. With your permission we also send seasonal offers and harvest updates. We never sell your data.\"},{\"heading\":\"How we protect it\",\"icon\":\"lock\",\"body\":\"All information travels over secure, encrypted connections and is stored on protected servers. Payment data is handled by trusted gateways \\u2014 we never see or store your full card number.\"},{\"heading\":\"Cookies and analytics\",\"icon\":\"cookie\",\"body\":\"We use cookies to keep your cart intact, remember preferences and improve the store. You can disable cookies in your browser at any time.\"},{\"heading\":\"Your rights\",\"icon\":\"user-check\",\"body\":\"You may request a copy of the data we hold, ask us to correct or delete it, withdraw marketing consent, or export your data. Email our support team and we respond within five working days.\"}]\";s:4:\"faqs\";s:364:\"[{\"q\":\"Do you share my information?\",\"a\":\"Only with delivery partners who need your address and payment processors who handle transactions. We never sell or rent your data.\"},{\"q\":\"How long do you keep my data?\",\"a\":\"Order records are kept as long as required for tax and warranty purposes (usually six years). Marketing data is kept until you withdraw consent.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:1;s:4:\"slug\";s:14:\"privacy-policy\";s:5:\"title\";s:14:\"Privacy Policy\";s:5:\"short\";s:58:\"How we collect, use and protect your personal information.\";s:4:\"hero\";s:28:\"Your data, handled with care\";s:4:\"icon\";s:12:\"shield-check\";s:4:\"lede\";s:213:\"At AB Organic Farm, we treat your trust as seriously as we treat our soil. This policy explains exactly what we collect, why, and the measures we take to keep your personal information safe, private and protected.\";s:8:\"sections\";s:1282:\"[{\"heading\":\"Information we collect\",\"icon\":\"database\",\"body\":\"We collect only what is needed to serve you: your name, delivery address, phone number, email, order history and payment details. When you create an account or place an order we also store preferences so we can personalise your farm favourites.\"},{\"heading\":\"How we use your information\",\"icon\":\"sparkles\",\"body\":\"Your details power your orders \\u2014 processing payments, arranging delivery, sending updates and resolving queries. With your permission we also send seasonal offers and harvest updates. We never sell your data.\"},{\"heading\":\"How we protect it\",\"icon\":\"lock\",\"body\":\"All information travels over secure, encrypted connections and is stored on protected servers. Payment data is handled by trusted gateways \\u2014 we never see or store your full card number.\"},{\"heading\":\"Cookies and analytics\",\"icon\":\"cookie\",\"body\":\"We use cookies to keep your cart intact, remember preferences and improve the store. You can disable cookies in your browser at any time.\"},{\"heading\":\"Your rights\",\"icon\":\"user-check\",\"body\":\"You may request a copy of the data we hold, ask us to correct or delete it, withdraw marketing consent, or export your data. Email our support team and we respond within five working days.\"}]\";s:4:\"faqs\";s:364:\"[{\"q\":\"Do you share my information?\",\"a\":\"Only with delivery partners who need your address and payment processors who handle transactions. We never sell or rent your data.\"},{\"q\":\"How long do you keep my data?\",\"a\":\"Order records are kept as long as required for tax and warranty purposes (usually six years). Marketing data is kept until you withdraw consent.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:1;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"sections\";s:5:\"array\";s:4:\"faqs\";s:5:\"array\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:4:\"slug\";i:1;s:5:\"title\";i:2;s:5:\"short\";i:3;s:4:\"hero\";i:4;s:4:\"icon\";i:5;s:4:\"lede\";i:6;s:8:\"sections\";i:7;s:4:\"faqs\";i:8;s:9:\"is_active\";i:9;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',1788496274),
('ab-organic-farm-cache-page.refund-policy','O:15:\"App\\Models\\Page\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"pages\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:3;s:4:\"slug\";s:13:\"refund-policy\";s:5:\"title\";s:22:\"Refund & Return Policy\";s:5:\"short\";s:40:\"Our promise if something is not perfect.\";s:4:\"hero\";s:27:\"Not happy? We make it right\";s:4:\"icon\";s:10:\"rotate-ccw\";s:4:\"lede\";s:152:\"Fresh, organic food should arrive perfect — and when it does not, we do not argue. Our no-fuss refund and return policy puts your peace of mind first.\";s:8:\"sections\";s:1193:\"[{\"heading\":\"When you can return\",\"icon\":\"package-x\",\"body\":\"Request a return or refund within 48 hours of delivery if an item arrives damaged, spoiled, incorrect or does not match its description. Photographic evidence speeds up resolution.\"},{\"heading\":\"How refunds work\",\"icon\":\"refresh-ccw\",\"body\":\"Approved refunds go to your original payment method or store credit, whichever you prefer. Most are processed within 3\\u20135 working days after approval.\"},{\"heading\":\"Perishable goods\",\"icon\":\"leaf\",\"body\":\"Because our ghee, oils and flours are fresh, opened or consumed products cannot be returned for hygiene reasons unless faulty at delivery. Quality checks guarantee freshness before dispatch.\"},{\"heading\":\"Non-returnable items\",\"icon\":\"shield-x\",\"body\":\"Sealed and used personal-care and consumable items, discounted bulk packs and any opened product cannot be returned, except when they arrive damaged or defective.\"},{\"heading\":\"How to request a return\",\"icon\":\"headset\",\"body\":\"Contact our support team from the Contact page or your order details with your order ID and a short note. We guide you through the rest \\u2014 usually a replacement or refund within one working day.\"}]\";s:4:\"faqs\";s:362:\"[{\"q\":\"How long does a refund take?\",\"a\":\"Once approved, refunds settle within 3\\u20135 working days depending on your payment provider. You are emailed the moment it is processed.\"},{\"q\":\"I received a damaged item. What now?\",\"a\":\"Photograph it within 48 hours, raise a ticket from your order, and we will replace it or refund it \\u2014 usually the same day.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:3;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:3;s:4:\"slug\";s:13:\"refund-policy\";s:5:\"title\";s:22:\"Refund & Return Policy\";s:5:\"short\";s:40:\"Our promise if something is not perfect.\";s:4:\"hero\";s:27:\"Not happy? We make it right\";s:4:\"icon\";s:10:\"rotate-ccw\";s:4:\"lede\";s:152:\"Fresh, organic food should arrive perfect — and when it does not, we do not argue. Our no-fuss refund and return policy puts your peace of mind first.\";s:8:\"sections\";s:1193:\"[{\"heading\":\"When you can return\",\"icon\":\"package-x\",\"body\":\"Request a return or refund within 48 hours of delivery if an item arrives damaged, spoiled, incorrect or does not match its description. Photographic evidence speeds up resolution.\"},{\"heading\":\"How refunds work\",\"icon\":\"refresh-ccw\",\"body\":\"Approved refunds go to your original payment method or store credit, whichever you prefer. Most are processed within 3\\u20135 working days after approval.\"},{\"heading\":\"Perishable goods\",\"icon\":\"leaf\",\"body\":\"Because our ghee, oils and flours are fresh, opened or consumed products cannot be returned for hygiene reasons unless faulty at delivery. Quality checks guarantee freshness before dispatch.\"},{\"heading\":\"Non-returnable items\",\"icon\":\"shield-x\",\"body\":\"Sealed and used personal-care and consumable items, discounted bulk packs and any opened product cannot be returned, except when they arrive damaged or defective.\"},{\"heading\":\"How to request a return\",\"icon\":\"headset\",\"body\":\"Contact our support team from the Contact page or your order details with your order ID and a short note. We guide you through the rest \\u2014 usually a replacement or refund within one working day.\"}]\";s:4:\"faqs\";s:362:\"[{\"q\":\"How long does a refund take?\",\"a\":\"Once approved, refunds settle within 3\\u20135 working days depending on your payment provider. You are emailed the moment it is processed.\"},{\"q\":\"I received a damaged item. What now?\",\"a\":\"Photograph it within 48 hours, raise a ticket from your order, and we will replace it or refund it \\u2014 usually the same day.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:3;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"sections\";s:5:\"array\";s:4:\"faqs\";s:5:\"array\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:4:\"slug\";i:1;s:5:\"title\";i:2;s:5:\"short\";i:3;s:4:\"hero\";i:4;s:4:\"icon\";i:5;s:4:\"lede\";i:6;s:8:\"sections\";i:7;s:4:\"faqs\";i:8;s:9:\"is_active\";i:9;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',1788496274),
('ab-organic-farm-cache-page.returns-exchanges','O:15:\"App\\Models\\Page\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"pages\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:6;s:4:\"slug\";s:17:\"returns-exchanges\";s:5:\"title\";s:19:\"Returns & Exchanges\";s:5:\"short\";s:39:\"Swap or send back an item, hassle-free.\";s:4:\"hero\";s:29:\"Swaps and returns made simple\";s:4:\"icon\";s:10:\"refresh-cw\";s:4:\"lede\";s:131:\"Sometimes a swap is better than a refund. Our returns and exchange programme is designed to be painless, fast and genuinely useful.\";s:8:\"sections\";s:967:\"[{\"heading\":\"Exchange window\",\"icon\":\"calendar-clock\",\"body\":\"You have 7 days from delivery to request an exchange for a different variant or size of the same product, as long as the item is unopened and in its original packaging.\"},{\"heading\":\"Eligible items\",\"icon\":\"package\",\"body\":\"Sealed pantry staples (ghee, oils, flour, dry goods) can be exchanged for any other variant of equal or greater value. Opened or perishable items cannot be exchanged for hygiene reasons.\"},{\"heading\":\"How exchanges work\",\"icon\":\"arrow-right-left\",\"body\":\"Raise an exchange request from your order. We arrange reverse pickup if eligible, you hand over the sealed item, and we dispatch the replacement once it reaches us \\u2014 usually within 2\\u20133 days.\"},{\"heading\":\"Returning an item\",\"icon\":\"package-search\",\"body\":\"Follow the same path as exchanges for returns. Where a return fee applies it is shown upfront. Damaged or incorrect items are swapped or refunded at no cost.\"}]\";s:4:\"faqs\";s:378:\"[{\"q\":\"Do I pay for reverse pickup?\",\"a\":\"Exchanges and returns for damaged, expired or incorrect items are free. Voluntary returns may attract a small reverse-logistics fee, shown before you confirm.\"},{\"q\":\"Can I exchange for a different product?\",\"a\":\"Within the eligible window you can exchange for any product of equal value; a higher-value product costs the difference.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:6;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:6;s:4:\"slug\";s:17:\"returns-exchanges\";s:5:\"title\";s:19:\"Returns & Exchanges\";s:5:\"short\";s:39:\"Swap or send back an item, hassle-free.\";s:4:\"hero\";s:29:\"Swaps and returns made simple\";s:4:\"icon\";s:10:\"refresh-cw\";s:4:\"lede\";s:131:\"Sometimes a swap is better than a refund. Our returns and exchange programme is designed to be painless, fast and genuinely useful.\";s:8:\"sections\";s:967:\"[{\"heading\":\"Exchange window\",\"icon\":\"calendar-clock\",\"body\":\"You have 7 days from delivery to request an exchange for a different variant or size of the same product, as long as the item is unopened and in its original packaging.\"},{\"heading\":\"Eligible items\",\"icon\":\"package\",\"body\":\"Sealed pantry staples (ghee, oils, flour, dry goods) can be exchanged for any other variant of equal or greater value. Opened or perishable items cannot be exchanged for hygiene reasons.\"},{\"heading\":\"How exchanges work\",\"icon\":\"arrow-right-left\",\"body\":\"Raise an exchange request from your order. We arrange reverse pickup if eligible, you hand over the sealed item, and we dispatch the replacement once it reaches us \\u2014 usually within 2\\u20133 days.\"},{\"heading\":\"Returning an item\",\"icon\":\"package-search\",\"body\":\"Follow the same path as exchanges for returns. Where a return fee applies it is shown upfront. Damaged or incorrect items are swapped or refunded at no cost.\"}]\";s:4:\"faqs\";s:378:\"[{\"q\":\"Do I pay for reverse pickup?\",\"a\":\"Exchanges and returns for damaged, expired or incorrect items are free. Voluntary returns may attract a small reverse-logistics fee, shown before you confirm.\"},{\"q\":\"Can I exchange for a different product?\",\"a\":\"Within the eligible window you can exchange for any product of equal value; a higher-value product costs the difference.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:6;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"sections\";s:5:\"array\";s:4:\"faqs\";s:5:\"array\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:4:\"slug\";i:1;s:5:\"title\";i:2;s:5:\"short\";i:3;s:4:\"hero\";i:4;s:4:\"icon\";i:5;s:4:\"lede\";i:6;s:8:\"sections\";i:7;s:4:\"faqs\";i:8;s:9:\"is_active\";i:9;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',1788496274),
('ab-organic-farm-cache-page.shipping-policy','O:15:\"App\\Models\\Page\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"pages\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:2;s:4:\"slug\";s:15:\"shipping-policy\";s:5:\"title\";s:15:\"Shipping Policy\";s:5:\"short\";s:54:\"Delivery areas, timelines, charges and what to expect.\";s:4:\"hero\";s:30:\"From our farm to your doorstep\";s:4:\"icon\";s:5:\"truck\";s:4:\"lede\";s:201:\"We know fresh food should arrive fast and arrive right. This page spells out where we deliver, how long it takes, what it costs — and what makes our packaging kinder to both your food and the planet.\";s:8:\"sections\";s:1065:\"[{\"heading\":\"Where we deliver\",\"icon\":\"map-pin\",\"body\":\"We deliver across most of the region including urban centres and surrounding districts. Enter your pincode at checkout and our system instantly confirms whether we service your area.\"},{\"heading\":\"Order processing\",\"icon\":\"clock\",\"body\":\"Orders placed before 4pm on a working day are picked, packed and dispatched the same day. Later or weekend orders leave our facility the next working morning.\"},{\"heading\":\"Delivery timelines\",\"icon\":\"timer\",\"body\":\"Standard delivery takes 2\\u20134 working days; metro areas often arrive next-day. Express delivery is available at checkout where services permit (24\\u201348 hours).\"},{\"heading\":\"Charges & free delivery\",\"icon\":\"package-check\",\"body\":\"Delivery is FREE above a cart value of 499. Below that a small, flat fee is shown before you pay \\u2014 no surprise charges at the door.\"},{\"heading\":\"Tracking your order\",\"icon\":\"scan-line\",\"body\":\"As soon as your order ships you receive tracking by SMS and email. Follow it any time from My Orders in your account.\"}]\";s:4:\"faqs\";s:356:\"[{\"q\":\"Can I track my delivery live?\",\"a\":\"Yes \\u2014 you get a tracking link by SMS\\/email once dispatched, and can monitor progress in My Orders until it reaches you.\"},{\"q\":\"What if I live outside your zone?\",\"a\":\"Your pincode is checked at checkout. If we do not yet serve you, we add your area to our roadmap \\u2014 new localities launch regularly.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:2;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:2;s:4:\"slug\";s:15:\"shipping-policy\";s:5:\"title\";s:15:\"Shipping Policy\";s:5:\"short\";s:54:\"Delivery areas, timelines, charges and what to expect.\";s:4:\"hero\";s:30:\"From our farm to your doorstep\";s:4:\"icon\";s:5:\"truck\";s:4:\"lede\";s:201:\"We know fresh food should arrive fast and arrive right. This page spells out where we deliver, how long it takes, what it costs — and what makes our packaging kinder to both your food and the planet.\";s:8:\"sections\";s:1065:\"[{\"heading\":\"Where we deliver\",\"icon\":\"map-pin\",\"body\":\"We deliver across most of the region including urban centres and surrounding districts. Enter your pincode at checkout and our system instantly confirms whether we service your area.\"},{\"heading\":\"Order processing\",\"icon\":\"clock\",\"body\":\"Orders placed before 4pm on a working day are picked, packed and dispatched the same day. Later or weekend orders leave our facility the next working morning.\"},{\"heading\":\"Delivery timelines\",\"icon\":\"timer\",\"body\":\"Standard delivery takes 2\\u20134 working days; metro areas often arrive next-day. Express delivery is available at checkout where services permit (24\\u201348 hours).\"},{\"heading\":\"Charges & free delivery\",\"icon\":\"package-check\",\"body\":\"Delivery is FREE above a cart value of 499. Below that a small, flat fee is shown before you pay \\u2014 no surprise charges at the door.\"},{\"heading\":\"Tracking your order\",\"icon\":\"scan-line\",\"body\":\"As soon as your order ships you receive tracking by SMS and email. Follow it any time from My Orders in your account.\"}]\";s:4:\"faqs\";s:356:\"[{\"q\":\"Can I track my delivery live?\",\"a\":\"Yes \\u2014 you get a tracking link by SMS\\/email once dispatched, and can monitor progress in My Orders until it reaches you.\"},{\"q\":\"What if I live outside your zone?\",\"a\":\"Your pincode is checked at checkout. If we do not yet serve you, we add your area to our roadmap \\u2014 new localities launch regularly.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:2;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"sections\";s:5:\"array\";s:4:\"faqs\";s:5:\"array\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:4:\"slug\";i:1;s:5:\"title\";i:2;s:5:\"short\";i:3;s:4:\"hero\";i:4;s:4:\"icon\";i:5;s:4:\"lede\";i:6;s:8:\"sections\";i:7;s:4:\"faqs\";i:8;s:9:\"is_active\";i:9;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',1788496274),
('ab-organic-farm-cache-page.terms-of-service','O:15:\"App\\Models\\Page\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"pages\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:4;s:4:\"slug\";s:16:\"terms-of-service\";s:5:\"title\";s:16:\"Terms of Service\";s:5:\"short\";s:64:\"The friendly rules that keep everything fair for you and for us.\";s:4:\"hero\";s:41:\"Clear, fair terms for a better experience\";s:4:\"icon\";s:10:\"file-check\";s:4:\"lede\";s:167:\"These terms make sure we have a shared understanding — so shopping on AB Organic Farm is effortless and transparent. By using our store you agree to the terms below.\";s:8:\"sections\";s:1254:\"[{\"heading\":\"Using the store\",\"icon\":\"store\",\"body\":\"You agree to use the store lawfully, keep your account details accurate, and not misuse the service, our content or other customers\' data. Accounts are for personal use unless you have an approved wholesale arrangement.\"},{\"heading\":\"Orders & pricing\",\"icon\":\"badge-cent\",\"body\":\"All prices are in rupees and include prevailing taxes where stated. We may correct pricing errors before dispatch. An order is confirmed only after we send confirmation and accept payment.\"},{\"heading\":\"Product information\",\"icon\":\"info\",\"body\":\"We describe products and nutritional details in good faith. Because organic and seasonal produce varies naturally, minor differences in colour, size or texture are expected and not grounds for dispute.\"},{\"heading\":\"Intellectual property\",\"icon\":\"copyright\",\"body\":\"All content on this store \\u2014 text, images, logos and branding \\u2014 belongs to AB Organic Farm and may not be reused for commercial purposes without written permission.\"},{\"heading\":\"Limitation of liability\",\"icon\":\"scale\",\"body\":\"To the extent permitted by law our liability for any claim is limited to the value of the goods in the affected order. Nothing here limits your statutory consumer rights.\"}]\";s:4:\"faqs\";s:324:\"[{\"q\":\"Can I order in bulk for events?\",\"a\":\"Absolutely. Contact our wholesale team for pricing on ghee, oils, flour and pantry staples for events, restaurants and stores.\"},{\"q\":\"What law governs these terms?\",\"a\":\"These terms are governed by the laws of India, subject to the exclusive jurisdiction of the local courts.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:4;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:4;s:4:\"slug\";s:16:\"terms-of-service\";s:5:\"title\";s:16:\"Terms of Service\";s:5:\"short\";s:64:\"The friendly rules that keep everything fair for you and for us.\";s:4:\"hero\";s:41:\"Clear, fair terms for a better experience\";s:4:\"icon\";s:10:\"file-check\";s:4:\"lede\";s:167:\"These terms make sure we have a shared understanding — so shopping on AB Organic Farm is effortless and transparent. By using our store you agree to the terms below.\";s:8:\"sections\";s:1254:\"[{\"heading\":\"Using the store\",\"icon\":\"store\",\"body\":\"You agree to use the store lawfully, keep your account details accurate, and not misuse the service, our content or other customers\' data. Accounts are for personal use unless you have an approved wholesale arrangement.\"},{\"heading\":\"Orders & pricing\",\"icon\":\"badge-cent\",\"body\":\"All prices are in rupees and include prevailing taxes where stated. We may correct pricing errors before dispatch. An order is confirmed only after we send confirmation and accept payment.\"},{\"heading\":\"Product information\",\"icon\":\"info\",\"body\":\"We describe products and nutritional details in good faith. Because organic and seasonal produce varies naturally, minor differences in colour, size or texture are expected and not grounds for dispute.\"},{\"heading\":\"Intellectual property\",\"icon\":\"copyright\",\"body\":\"All content on this store \\u2014 text, images, logos and branding \\u2014 belongs to AB Organic Farm and may not be reused for commercial purposes without written permission.\"},{\"heading\":\"Limitation of liability\",\"icon\":\"scale\",\"body\":\"To the extent permitted by law our liability for any claim is limited to the value of the goods in the affected order. Nothing here limits your statutory consumer rights.\"}]\";s:4:\"faqs\";s:324:\"[{\"q\":\"Can I order in bulk for events?\",\"a\":\"Absolutely. Contact our wholesale team for pricing on ghee, oils, flour and pantry staples for events, restaurants and stores.\"},{\"q\":\"What law governs these terms?\",\"a\":\"These terms are governed by the laws of India, subject to the exclusive jurisdiction of the local courts.\"}]\";s:9:\"is_active\";i:1;s:10:\"sort_order\";i:4;s:10:\"created_at\";s:19:\"2026-09-04 04:23:51\";s:10:\"updated_at\";s:19:\"2026-09-04 04:23:51\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"sections\";s:5:\"array\";s:4:\"faqs\";s:5:\"array\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"sort_order\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:4:\"slug\";i:1;s:5:\"title\";i:2;s:5:\"short\";i:3;s:4:\"hero\";i:4;s:4:\"icon\";i:5;s:4:\"lede\";i:6;s:8:\"sections\";i:7;s:4:\"faqs\";i:8;s:9:\"is_active\";i:9;s:10:\"sort_order\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',1788496274),
('ab-organic-farm-cache-settings.all','a:84:{s:10:\"store.name\";s:15:\"AB Organic Farm\";s:13:\"store.tagline\";s:28:\"Good Food. Naturally Better.\";s:11:\"store.phone\";s:15:\"+91 94370 00000\";s:13:\"store.address\";s:54:\"Plot 12, Green Valley Road, Bhubaneswar, Odisha 751001\";s:24:\"delivery.standard_charge\";s:2:\"49\";s:19:\"delivery.free_above\";s:3:\"499\";s:18:\"delivery.min_order\";s:3:\"199\";s:18:\"order.auto_confirm\";s:1:\"0\";s:31:\"order.cancellation_window_hours\";s:2:\"24\";s:11:\"cod.enabled\";s:1:\"1\";s:19:\"cod.max_order_value\";s:5:\"10000\";s:16:\"cod.instructions\";s:92:\"Please keep exact change ready. Our delivery partner will collect the cash at your doorstep.\";s:9:\"seo.title\";s:61:\"AB Organic Farm — Organic Products Delivered | Farm to Home\";s:19:\"cod.min_order_value\";s:1:\"0\";s:15:\"seo.description\";s:155:\"Shop certified organic fruits, vegetables, grains, pulses, spices and natural personal care. Fresh from farms to your doorstep. Cash on Delivery available.\";s:8:\"og.title\";s:61:\"AB Organic Farm — Organic Products Delivered | Farm to Home\";s:14:\"og.description\";s:127:\"Shop certified organic fruits, vegetables, grains, pulses, spices and natural personal care. Fresh from farms to your doorstep.\";s:15:\"social.facebook\";s:33:\"https://facebook.com/verdurafarms\";s:16:\"social.instagram\";s:34:\"https://instagram.com/verdurafarms\";s:14:\"social.youtube\";s:0:\"\";s:15:\"social.whatsapp\";s:15:\"+91 94370 00000\";s:12:\"display.logo\";s:26:\"logos/ab-organic-label.svg\";s:18:\"display.logo_white\";s:32:\"logos/ab-organic-label-white.svg\";s:11:\"store.email\";s:21:\"hello@verdurafarms.in\";s:12:\"og.image_url\";s:0:\"\";s:16:\"display.nav_menu\";s:960:\"[{\"label\":\"All Products\",\"icon\":\"nav-all\",\"url\":\"/categories/all\",\"highlight\":false,\"children\":[]},{\"label\":\"Ghee\",\"icon\":\"nav-ghee\",\"url\":\"/categories/ghee\",\"highlight\":false,\"children\":[{\"label\":\"Jar Type\",\"url\":\"/categories/ghee-jar-type\"},{\"label\":\"Packed Type\",\"url\":\"/categories/ghee-packed-type\"},{\"label\":\"Multitype Ghee\",\"url\":\"/categories/ghee-multitype\"}]},{\"label\":\"Oil\",\"icon\":\"nav-oils\",\"url\":\"/categories/oil\",\"highlight\":false,\"children\":[]},{\"label\":\"Atta\",\"icon\":\"nav-atta\",\"url\":\"/categories/atta\",\"highlight\":false,\"children\":[]},{\"label\":\"Hot Deals\",\"icon\":\"nav-deal\",\"url\":\"/search?q=deal\",\"highlight\":true,\"children\":[]},{\"label\":\"Shop\",\"icon\":\"nav-category\",\"url\":\"/categories\",\"highlight\":false,\"children\":[{\"label\":\"Ghee\",\"url\":\"/categories/ghee\"},{\"label\":\"Oil\",\"url\":\"/categories/oil\"},{\"label\":\"Atta\",\"url\":\"/categories/atta\"}]},{\"label\":\"Healthy Combo\",\"icon\":\"nav-combos\",\"url\":\"/search?q=combo\",\"highlight\":false,\"children\":[]}]\";s:14:\"home.cta_title\";s:30:\"Go Organic. Go Fresh. Go Fast.\";s:17:\"home.cta_subtitle\";s:79:\"Join thousands of families who trust AB Organic Farm for their daily groceries.\";s:15:\"home.cta_button\";s:14:\"Start Shopping\";s:13:\"home.cta_link\";s:15:\"/categories/all\";s:19:\"cod.advance_percent\";s:1:\"0\";s:29:\"inventory.low_stock_threshold\";s:1:\"5\";s:18:\"notify.admin_email\";s:21:\"hello@verdurafarms.in\";s:16:\"footer.copyright\";s:25:\"AB Organic Farm Pvt. Ltd.\";s:21:\"display.whatsapp_name\";s:15:\"AB Organic Farm\";s:19:\"display.trust_pills\";s:142:\"[{\"text\":\"100% Certified Organic\",\"icon\":\"shield-check\"},{\"text\":\"Lab Tested\",\"icon\":\"flask-conical\"},{\"text\":\"Farm to Table\",\"icon\":\"truck\"}]\";s:28:\"display.app_download_enabled\";s:1:\"0\";s:28:\"display.app_download_heading\";s:37:\"Unlock 17% OFF exclusively on the App\";s:19:\"footer.company_name\";s:15:\"AB Organic Farm\";s:25:\"footer.newsletter_heading\";s:16:\"Stay in the loop\";s:21:\"footer.newsletter_sub\";s:37:\"Fresh offers & farm stories. No spam.\";s:25:\"display.whatsapp_greeting\";s:36:\"Hi there! How can we help you today?\";s:16:\"display.app_icon\";s:0:\"\";s:18:\"display.bottom_nav\";s:2:\"[]\";s:20:\"cod.delivery_charges\";s:2:\"49\";s:23:\"cod.free_delivery_above\";s:3:\"499\";s:22:\"inventory.email_alerts\";s:1:\"1\";s:12:\"seo.keywords\";s:50:\"organic food, atta, ghee, natural oils, AB Organic\";s:10:\"notify.sms\";s:1:\"0\";s:15:\"notify.whatsapp\";s:1:\"1\";s:25:\"home.delivery_charge_text\";s:26:\"Free delivery above ₹499\";s:9:\"home.tags\";s:2:\"[]\";s:16:\"home.promo_cards\";s:2:\"[]\";s:16:\"home.brand_title\";s:13:\"Shop by Brand\";s:19:\"home.brand_subtitle\";s:44:\"Explore a curated range from trusted brands.\";s:19:\"home.featured_title\";s:17:\"Featured Products\";s:22:\"home.featured_subtitle\";s:50:\"Hand-picked organic favourites our customers love.\";s:15:\"home.best_title\";s:12:\"Best Sellers\";s:18:\"home.best_subtitle\";s:44:\"The products everyone keeps coming back for.\";s:14:\"home.new_title\";s:12:\"New Arrivals\";s:17:\"home.new_subtitle\";s:45:\"Fresh from the farm and just landed in store.\";s:14:\"home.why_title\";s:13:\"Why Choose Us\";s:14:\"home.why_items\";s:2:\"[]\";s:22:\"home.testimonial_title\";s:22:\"What Our Customers Say\";s:14:\"footer.tagline\";s:28:\"Good Food. Naturally Better.\";s:14:\"footer.address\";s:54:\"Plot 12, Green Valley Road, Bhubaneswar, Odisha 751001\";s:21:\"footer.links_services\";s:2:\"[]\";s:21:\"footer.links_policies\";s:2:\"[]\";s:14:\"footer.socials\";s:2:\"[]\";s:18:\"store.contact_link\";s:1:\"#\";s:26:\"display.announcement_items\";s:91:\"[\"Free delivery on orders above ₹499\",\"100% certified organic · straight from the farm\"]\";s:24:\"display.app_download_sub\";s:34:\"Get the AB Organic Farm app today.\";s:25:\"display.app_download_url2\";s:1:\"#\";s:21:\"display.app_store_url\";s:1:\"#\";s:24:\"display.app_download_url\";s:1:\"#\";s:23:\"display.rewards_enabled\";s:1:\"1\";s:24:\"display.rewards_mainline\";s:28:\"Earn rewards on every order!\";s:21:\"display.rewards_coins\";s:1:\"0\";s:23:\"display.rewards_subline\";s:18:\"Your rewards await\";s:21:\"display.rewards_items\";s:2:\"[]\";s:24:\"display.whatsapp_enabled\";s:1:\"0\";s:23:\"display.whatsapp_number\";s:12:\"919999999999\";s:24:\"display.whatsapp_message\";s:42:\"Hi! I have a question about your products.\";s:23:\"home.search_placeholder\";s:26:\"Search products, e.g. ghee\";}',2103855974);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `product_variant_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `price_at_add` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_id_product_variant_id_unique` (`cart_id`,`product_variant_id`),
  KEY `cart_items_product_variant_id_foreign` (`product_variant_id`),
  KEY `cart_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES
(48,677,159,159,3,1199.00,'2026-09-03 23:05:57','2026-09-03 23:06:00'),
(51,677,153,149,3,549.00,'2026-09-03 23:29:43','2026-09-03 23:29:47'),
(45,1039,173,173,1,1199.00,'2026-09-03 22:23:46','2026-09-03 22:23:46'),
(49,677,170,170,2,1599.00,'2026-09-03 23:07:58','2026-09-03 23:08:00'),
(55,677,152,148,2,479.00,'2026-09-03 23:55:02','2026-09-03 23:55:04'),
(52,1117,156,152,1,359.00,'2026-09-03 23:34:44','2026-09-03 23:34:44'),
(53,1118,156,152,1,359.00,'2026-09-03 23:35:30','2026-09-03 23:35:30'),
(54,1123,156,152,1,359.00,'2026-09-03 23:37:08','2026-09-03 23:37:08');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(64) DEFAULT NULL,
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_id_unique` (`user_id`),
  KEY `carts_coupon_id_foreign` (`coupon_id`),
  KEY `carts_session_id_index` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1198 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES
(677,1,NULL,NULL,'2026-09-02 10:50:23','2026-09-02 10:50:23'),
(678,NULL,'7ae2e187bbf0373e0cf269e4dfdde2cb',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(679,NULL,'9191bb350ef8c579840682c17f8e099f',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(680,NULL,'b00a770e1aef24cf5a79fe391652bf92',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(681,NULL,'8b0f871faf8b6e73180eb2df6fd919f4',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(682,NULL,'640b5ceaf85a3e35a61802ef0aa39056',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(683,NULL,'8c639f6a34a744868cdcc270d8e999ad',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(684,NULL,'6a47eeacbd88a1b59ea9d0ed2362c8b6',NULL,'2026-09-02 10:50:41','2026-09-02 10:50:41'),
(685,NULL,'296e93e8196aa13888d178747ac9de5b',NULL,'2026-09-02 10:50:49','2026-09-02 10:50:49'),
(686,NULL,'1c9d4432540a94f23456a2300440a4a3',NULL,'2026-09-02 10:50:49','2026-09-02 10:50:49'),
(687,NULL,'2ffd1252df30ce0c7a5e66da49031539',NULL,'2026-09-02 10:52:28','2026-09-02 10:52:28'),
(688,NULL,'bf4621425cbf003d6456ebc321dbc3bb',NULL,'2026-09-02 10:52:30','2026-09-02 10:52:30'),
(689,NULL,'9fef399589aa8f4d038acbcd75e9643b',NULL,'2026-09-02 10:52:32','2026-09-02 10:52:32'),
(690,NULL,'ce7e2a7690543fc1b7af992c60397520',NULL,'2026-09-02 10:52:33','2026-09-02 10:52:33'),
(691,NULL,'e5517b2127bdd4d128b5322e42f94698',NULL,'2026-09-02 10:52:35','2026-09-02 10:52:35'),
(692,NULL,'592e561178a839a87af87a17718a5793',NULL,'2026-09-02 10:52:37','2026-09-02 10:52:37'),
(693,NULL,'35821d1596dc7174ebb272d2b560f1cc',NULL,'2026-09-02 10:52:38','2026-09-02 10:52:38'),
(694,NULL,'c892810243b2fbbe08cbcf8e8e3bcc11',NULL,'2026-09-02 10:52:40','2026-09-02 10:52:40'),
(695,NULL,'9e44f2fb4dd071aa359002fe95a84d10',NULL,'2026-09-02 10:55:38','2026-09-02 10:55:38'),
(696,NULL,'ec87c1f2527478b1945d1c23f94a4771',NULL,'2026-09-02 10:55:39','2026-09-02 10:55:39'),
(697,NULL,'f7004239a0d7d0574f0d9ee1773a7d8c',NULL,'2026-09-02 10:55:41','2026-09-02 10:55:41'),
(698,NULL,'29ed186da96a33495cb5690f7777feb9',NULL,'2026-09-02 10:55:43','2026-09-02 10:55:43'),
(699,NULL,'206d704b7642ed7a3ef3929ba3b797ec',NULL,'2026-09-02 10:55:45','2026-09-02 10:55:45'),
(700,NULL,'f1724867aa88a2696616b23f6c2b47a3',NULL,'2026-09-02 10:55:46','2026-09-02 10:55:46'),
(701,NULL,'39d549ac8ddcde94e5168cd6ed003cae',NULL,'2026-09-02 10:55:48','2026-09-02 10:55:48'),
(702,NULL,'36f135ffd55c99cdad5b44b478ed322d',NULL,'2026-09-02 10:55:50','2026-09-02 10:55:50'),
(703,NULL,'0b7b05655cc8750b928d1dc8e1602b4d',NULL,'2026-09-02 10:56:10','2026-09-02 10:56:10'),
(704,NULL,'33db8b4946ba4f789e3169c6ad367559',NULL,'2026-09-02 10:57:04','2026-09-02 10:57:04'),
(705,NULL,'64d35e5f47df70a66bf8c6c3b1f9c82e',NULL,'2026-09-02 10:57:06','2026-09-02 10:57:06'),
(706,NULL,'95ca17db3f0181978666e98406f4d844',NULL,'2026-09-02 10:57:07','2026-09-02 10:57:07'),
(707,NULL,'f6d4c81d5261de7d22ebb0988547b7aa',NULL,'2026-09-02 10:57:09','2026-09-02 10:57:09'),
(708,NULL,'c41d035b0512de6d27cdce708eff0d21',NULL,'2026-09-02 10:57:10','2026-09-02 10:57:10'),
(709,NULL,'3844844e236058dc8b96191b843bf01d',NULL,'2026-09-02 10:57:12','2026-09-02 10:57:12'),
(710,NULL,'785904dd02d40555ef513cd529659ce2',NULL,'2026-09-02 10:57:14','2026-09-02 10:57:14'),
(711,NULL,'75a5b6b71ab69cece964ef46ee030a88',NULL,'2026-09-02 10:57:15','2026-09-02 10:57:15'),
(723,NULL,'75ca603b2e6232fd3972f63a865a9b00',NULL,'2026-09-02 11:08:37','2026-09-02 11:08:37'),
(724,NULL,'e6a0d55f21366e50fcafe7fcaa32a5b7',NULL,'2026-09-02 11:08:56','2026-09-02 11:08:56'),
(725,NULL,'abd46a4921eed1886e6fef21b32760a8',NULL,'2026-09-02 11:09:34','2026-09-02 11:09:34'),
(726,NULL,'92465ec7ee45578b202646ba3b5b3927',NULL,'2026-09-02 11:11:36','2026-09-02 11:11:36'),
(747,NULL,'c85e6465f892a992f1ac6851128983fd',NULL,'2026-09-02 18:24:59','2026-09-02 18:24:59'),
(729,NULL,'b719f29c1d8a9854eaababdae6faedd5',NULL,'2026-09-02 12:03:20','2026-09-02 12:03:20'),
(732,NULL,'5dd0f86d387fe4ad2a457b1795e08861',NULL,'2026-09-02 12:03:59','2026-09-02 12:03:59'),
(733,NULL,'06a8ee8c96c13f3ac8fabb269fcc9563',NULL,'2026-09-02 12:04:24','2026-09-02 12:04:24'),
(758,NULL,'219bbc496af2ab437afaf90d1af0eb8d',NULL,'2026-09-02 18:30:15','2026-09-02 18:30:15'),
(770,NULL,'820594e2604bc159b662546325df3b87',NULL,'2026-09-02 23:31:00','2026-09-02 23:31:00'),
(775,NULL,'573f3738b88399ca257405f5a3d3ca1d',NULL,'2026-09-02 23:40:11','2026-09-02 23:40:11'),
(776,NULL,'cb483500a6d0f59c9bd180290153efcc',NULL,'2026-09-02 23:40:45','2026-09-02 23:40:45'),
(777,NULL,'8b0b9aa9f6523a57abe3d82c4067f8b9',NULL,'2026-09-02 23:40:55','2026-09-02 23:40:55'),
(834,NULL,'848e8276ef80b5d94b9c2d0e35ebf7bd',NULL,'2026-09-03 01:07:20','2026-09-03 01:07:20'),
(835,NULL,'5a86b1e157d4e6f44ab41bfcfe30e382',NULL,'2026-09-03 01:19:49','2026-09-03 01:19:49'),
(836,NULL,'4849778e80e24181e4e3a8109edc11c1',NULL,'2026-09-03 01:20:07','2026-09-03 01:20:07'),
(838,NULL,'a848a958ebe42ecf02fe91ef07ab9a64',NULL,'2026-09-03 01:21:30','2026-09-03 01:21:30'),
(851,NULL,'a22dd68a45eb39a7ce84589d7b9686c2',NULL,'2026-09-03 03:13:46','2026-09-03 03:13:46'),
(852,NULL,'ea50c23e614f114556989b8f4f8ad71a',NULL,'2026-09-03 03:13:56','2026-09-03 03:13:56'),
(860,NULL,'42b436f88072af92073b731920e3584a',NULL,'2026-09-03 03:25:41','2026-09-03 03:25:41'),
(861,NULL,'7f0c748b62d9898ee5a1f2189220afd7',NULL,'2026-09-03 03:26:09','2026-09-03 03:26:09'),
(862,NULL,'60c84bab0f8806672130beb45294aa45',NULL,'2026-09-03 03:32:14','2026-09-03 03:32:14'),
(863,NULL,'1cb6926137a596ed220c16176c2239c7',NULL,'2026-09-03 03:34:01','2026-09-03 03:34:01'),
(864,NULL,'dad729933b52e16a1053af5c022c9bef',NULL,'2026-09-03 03:34:54','2026-09-03 03:34:54'),
(865,NULL,'934f23a48b64eed40baae15d881a5f92',NULL,'2026-09-03 03:35:29','2026-09-03 03:35:29'),
(866,NULL,'429afab4f783f3a4d526fe7cd8b0af29',NULL,'2026-09-03 03:37:13','2026-09-03 03:37:13'),
(867,NULL,'3ded9f336376a40431ffa9f207403dfb',NULL,'2026-09-03 03:37:28','2026-09-03 03:37:28'),
(868,NULL,'b42da2492ddfcd17d70869c9180cd5cd',NULL,'2026-09-03 03:39:02','2026-09-03 03:39:02'),
(869,NULL,'dc7f1c58a54169e83bce7ee6f69e27cd',NULL,'2026-09-03 03:41:00','2026-09-03 03:41:00'),
(870,NULL,'93fb3380747a7393aa0221c9db52e80f',NULL,'2026-09-03 03:41:16','2026-09-03 03:41:16'),
(872,NULL,'f12aad99f2bbd7b91163393d650c2436',NULL,'2026-09-03 09:08:10','2026-09-03 09:08:10'),
(901,NULL,'13b9ede781f479fa1a3d38e19969e232',NULL,'2026-09-03 11:04:36','2026-09-03 11:04:36'),
(874,NULL,'91a58d367a761997fa2cfbaf70224687',NULL,'2026-09-03 09:14:33','2026-09-03 09:14:33'),
(875,NULL,'1332cc7f48906b1e7c0865096fd39d16',NULL,'2026-09-03 09:14:44','2026-09-03 09:14:44'),
(876,NULL,'07473e994e00d47ea6a2fa8e02b059f4',NULL,'2026-09-03 09:15:05','2026-09-03 09:15:05'),
(877,NULL,'bd23586503f8a3038eee629eedfa63de',NULL,'2026-09-03 09:15:15','2026-09-03 09:15:15'),
(885,NULL,'741bc60a843c979b749e1d23ca020a0b',NULL,'2026-09-03 09:21:56','2026-09-03 09:21:56'),
(886,NULL,'c21e5857c47275293cc85ca1d7e86569',NULL,'2026-09-03 09:22:09','2026-09-03 09:22:09'),
(887,NULL,'d14e3de043a0654df0283134a655b856',NULL,'2026-09-03 09:23:42','2026-09-03 09:23:42'),
(888,NULL,'47558bd0cc25f1b9652244ae6aea2ae8',NULL,'2026-09-03 09:23:58','2026-09-03 09:23:58'),
(889,NULL,'173cfbb960833eee779723a0fd8484e0',NULL,'2026-09-03 09:24:11','2026-09-03 09:24:11'),
(890,NULL,'4e29c94b28996e1f4f79352abd489377',NULL,'2026-09-03 09:28:28','2026-09-03 09:28:28'),
(891,NULL,'89d2bfc46350416b4d5e78fab005c79a',NULL,'2026-09-03 09:28:34','2026-09-03 09:28:34'),
(892,NULL,'b5478b227515b72dc88827dbbf801076',NULL,'2026-09-03 09:28:34','2026-09-03 09:28:34'),
(893,NULL,'e70421e4f7f98eda7b37ad80334a2013',NULL,'2026-09-03 09:28:43','2026-09-03 09:28:43'),
(894,NULL,'15294e22c947d0afbb274597704a61b9',NULL,'2026-09-03 09:29:14','2026-09-03 09:29:14'),
(895,NULL,'67c9056db15eea85520691c9e0509007',NULL,'2026-09-03 09:31:47','2026-09-03 09:31:47'),
(896,NULL,'f17db4d3e99e40dbea40972dd9fd48fa',NULL,'2026-09-03 09:32:38','2026-09-03 09:32:38'),
(897,NULL,'91ca71bbe36ce8c3c1b9e96133eceb05',NULL,'2026-09-03 09:32:52','2026-09-03 09:32:52'),
(898,NULL,'35d15856b0e922b430cc0900f432251d',NULL,'2026-09-03 09:46:11','2026-09-03 09:46:11'),
(899,NULL,'ee8df3bbcaa9095f830090c6b2fd8290',NULL,'2026-09-03 09:46:52','2026-09-03 09:46:52'),
(900,NULL,'b642ffc48498e2ba6c664015aa925d5d',NULL,'2026-09-03 09:46:57','2026-09-03 09:46:57'),
(902,NULL,'dd155a02da65161f8581b40c263f883e',NULL,'2026-09-03 11:04:52','2026-09-03 11:04:52'),
(903,NULL,'5a5844d6d19eb5d59ded05c125fba773',NULL,'2026-09-03 11:05:25','2026-09-03 11:05:25'),
(904,NULL,'18177cd913a777fc5cc5924a03ba3d11',NULL,'2026-09-03 12:18:33','2026-09-03 12:18:33'),
(905,NULL,'d9eca14adfa518f37c7ac0d50dbbaaa6',NULL,'2026-09-03 12:18:40','2026-09-03 12:18:40'),
(906,NULL,'5a8d90e8842d6d174c466b1467a7e7c4',NULL,'2026-09-03 12:18:52','2026-09-03 12:18:52'),
(907,NULL,'1bbabcd2818b290c55b8c725047493df',NULL,'2026-09-03 12:18:52','2026-09-03 12:18:52'),
(908,NULL,'ebd43c07cf43d2fcb2ffbd8f189104c7',NULL,'2026-09-03 12:53:51','2026-09-03 12:53:51'),
(909,NULL,'4abbc43012828826561d8e7f5a85319c',NULL,'2026-09-03 12:54:15','2026-09-03 12:54:15'),
(910,NULL,'9f94dc7d9e20a6f98955e1a04b29336f',NULL,'2026-09-03 13:43:45','2026-09-03 13:43:45'),
(911,NULL,'9fac60bcebe1172947f37f3f33f38302',NULL,'2026-09-03 13:43:47','2026-09-03 13:43:47'),
(912,NULL,'7ae888a063c9187496d0fbd0c315f5db',NULL,'2026-09-03 13:43:49','2026-09-03 13:43:49'),
(913,NULL,'ae9fbe737ddf604a322e6ddf81a1a54c',NULL,'2026-09-03 13:43:51','2026-09-03 13:43:51'),
(914,NULL,'f2955b4a73d1631accc0028587264b77',NULL,'2026-09-03 13:43:53','2026-09-03 13:43:53'),
(915,NULL,'ddc5460ec0561aebdd816d853bf81b49',NULL,'2026-09-03 13:43:55','2026-09-03 13:43:55'),
(916,NULL,'0eaa0a75752b96c35cdcc0a81780c101',NULL,'2026-09-03 13:43:57','2026-09-03 13:43:57'),
(917,NULL,'2f9d55c8d9b19199e1a8cf7273246639',NULL,'2026-09-03 13:43:59','2026-09-03 13:43:59'),
(918,NULL,'769f835f577a555e5944a7ffce9b0817',NULL,'2026-09-03 13:44:21','2026-09-03 13:44:21'),
(919,NULL,'a503e2a075cd15482f33b3d9738e679e',NULL,'2026-09-03 13:44:31','2026-09-03 13:44:31'),
(920,NULL,'593584c9abd5ae0dcd2c3b9a3ef85b8c',NULL,'2026-09-03 13:44:35','2026-09-03 13:44:35'),
(921,NULL,'1c28f5140faf0d6272b52066e9e0e6fd',NULL,'2026-09-03 13:44:35','2026-09-03 13:44:35'),
(922,NULL,'ec8b89bf2faca9ccabdb99d54f92c8c4',NULL,'2026-09-03 13:44:35','2026-09-03 13:44:35'),
(923,NULL,'3f3e8250f852730294831f5a565240fe',NULL,'2026-09-03 13:44:35','2026-09-03 13:44:35'),
(924,NULL,'58201530744b65888bed11e0359438b8',NULL,'2026-09-03 13:44:52','2026-09-03 13:44:52'),
(925,NULL,'98c7e0ee11febcc54267c4c18d2f1ade',NULL,'2026-09-03 13:45:01','2026-09-03 13:45:01'),
(927,NULL,'ba7657248d64cb9486c62abe0fd82574',NULL,'2026-09-03 13:45:18','2026-09-03 13:45:18'),
(929,NULL,'997ca2bc8403fb6c6ad3eb000c563ccd',NULL,'2026-09-03 13:45:50','2026-09-03 13:45:50'),
(932,NULL,'90a0dca3071375026ce1bb98d7872194',NULL,'2026-09-03 13:47:05','2026-09-03 13:47:05'),
(933,NULL,'5f726b57eaa8e733ae33ff283a8d1216',NULL,'2026-09-03 13:48:54','2026-09-03 13:48:54'),
(934,NULL,'3a398c480e19da4e938409379b8ab319',NULL,'2026-09-03 13:48:56','2026-09-03 13:48:56'),
(935,NULL,'52ac1e412e8bdad80859b3b3e49e1c68',NULL,'2026-09-03 13:48:58','2026-09-03 13:48:58'),
(936,NULL,'572f8aacf82988fd54f9b932a619fac2',NULL,'2026-09-03 13:49:00','2026-09-03 13:49:00'),
(937,NULL,'ae09ab2c43a8220519f05cebb9b62b7d',NULL,'2026-09-03 13:49:03','2026-09-03 13:49:03'),
(938,NULL,'fe6f5b8611f32455d6d79e5770a204e7',NULL,'2026-09-03 13:49:05','2026-09-03 13:49:05'),
(939,NULL,'fc4f6ea79bdca209853b82deaa787c9d',NULL,'2026-09-03 13:49:07','2026-09-03 13:49:07'),
(940,NULL,'1d56228a8e478bb2db4cf412f337e579',NULL,'2026-09-03 21:29:23','2026-09-03 21:29:23'),
(941,NULL,'097c38f2044cf2023b100b7121183e60',NULL,'2026-09-03 21:29:31','2026-09-03 21:29:31'),
(942,NULL,'851452f46d9bdd996d266cb6fd0b4c69',NULL,'2026-09-03 21:43:31','2026-09-03 21:43:31'),
(943,NULL,'319374434574c3a79e4f20aac85cdb68',NULL,'2026-09-03 21:47:13','2026-09-03 21:47:13'),
(944,NULL,'b99fd2dbd0a02b0d2eb9f57a3f809f27',NULL,'2026-09-03 21:47:15','2026-09-03 21:47:15'),
(945,NULL,'f467b435b38b5b7be13e1027c1ba1f5d',NULL,'2026-09-03 21:47:17','2026-09-03 21:47:17'),
(946,NULL,'dfe97d031d1307a69505261b14560f5f',NULL,'2026-09-03 21:47:19','2026-09-03 21:47:19'),
(947,NULL,'3b6d2a721c0f5df49852aba74cf2a4cd',NULL,'2026-09-03 21:47:21','2026-09-03 21:47:21'),
(948,NULL,'553386136bd06022933d1235a8e4aad2',NULL,'2026-09-03 21:47:21','2026-09-03 21:47:21'),
(949,NULL,'e9b5755798a09a560017a4a9c82034fe',NULL,'2026-09-03 21:47:23','2026-09-03 21:47:23'),
(950,NULL,'b9204d9123d03a3786ba099a9955748b',NULL,'2026-09-03 21:49:27','2026-09-03 21:49:27'),
(951,NULL,'d715aef76bfd4d18f03de36c7e900a90',NULL,'2026-09-03 21:49:45','2026-09-03 21:49:45'),
(952,NULL,'aecb8cb9ede8ba3e20dbc42c618a38b8',NULL,'2026-09-03 21:49:56','2026-09-03 21:49:56'),
(953,NULL,'5ae5b82a3f83f1359182895a559b2e62',NULL,'2026-09-03 21:50:48','2026-09-03 21:50:48'),
(954,NULL,'7f226b3c0510d956444d26c4d5e3dc90',NULL,'2026-09-03 21:50:51','2026-09-03 21:50:51'),
(955,NULL,'8414adf82592bfc77d2cb3854105a94a',NULL,'2026-09-03 21:50:53','2026-09-03 21:50:53'),
(956,NULL,'e2471b9dd4aed2ce5b28d3cde44300a4',NULL,'2026-09-03 21:50:55','2026-09-03 21:50:55'),
(957,NULL,'fcd4e2aac5eddc00d52ce4f8b5872aae',NULL,'2026-09-03 21:50:56','2026-09-03 21:50:56'),
(958,NULL,'3106db4046bd9a60814338e2be34d453',NULL,'2026-09-03 21:50:56','2026-09-03 21:50:56'),
(959,NULL,'3f9914ba7cbee2abf6397bd1e226ca25',NULL,'2026-09-03 21:50:58','2026-09-03 21:50:58'),
(960,NULL,'02ad43b99888e5e00b88666eb2f2d798',NULL,'2026-09-03 21:53:14','2026-09-03 21:53:14'),
(961,NULL,'8416548128cfb0cb099074c5042e37b9',NULL,'2026-09-03 21:54:22','2026-09-03 21:54:22'),
(962,NULL,'b8f118b381cf8156294cd92ea7dc3356',NULL,'2026-09-03 21:58:13','2026-09-03 21:58:13'),
(963,NULL,'7c3aa1b16b6d1f4b805a5e19727c382c',NULL,'2026-09-03 21:58:15','2026-09-03 21:58:15'),
(964,NULL,'613091ca742c49fe21d43a2bce861561',NULL,'2026-09-03 21:58:17','2026-09-03 21:58:17'),
(965,NULL,'b9e2c42b03b11c98ae87ca51b88991cd',NULL,'2026-09-03 21:58:19','2026-09-03 21:58:19'),
(966,NULL,'ba28cecd0ae52b68172e14291fefff88',NULL,'2026-09-03 21:58:21','2026-09-03 21:58:21'),
(967,NULL,'1ff54bbb3fbec6816da81fa19b697452',NULL,'2026-09-03 21:58:21','2026-09-03 21:58:21'),
(968,NULL,'178f2144c2b8bb3f6ba53be6e8a4557f',NULL,'2026-09-03 21:58:23','2026-09-03 21:58:23'),
(969,NULL,'57b5eab62546d6425fd766e60239fab7',NULL,'2026-09-03 21:58:32','2026-09-03 21:58:32'),
(970,NULL,'b4c3ed612fe7593fce4d6fabc7caf98e',NULL,'2026-09-03 21:58:40','2026-09-03 21:58:40'),
(971,NULL,'24b331ba233136209f3baf16b424168a',NULL,'2026-09-03 22:03:34','2026-09-03 22:03:34'),
(972,NULL,'4bebd9823b81a4afef5ff786bcfaa7fc',NULL,'2026-09-03 22:03:36','2026-09-03 22:03:36'),
(973,NULL,'fa7f0ac6ed26b957219bc4b2b479f432',NULL,'2026-09-03 22:03:38','2026-09-03 22:03:38'),
(974,NULL,'3324856f0c16ec3a58c44366b175abf5',NULL,'2026-09-03 22:03:39','2026-09-03 22:03:39'),
(975,NULL,'667d14a9cd5ceabaad8e443515e8238e',NULL,'2026-09-03 22:03:41','2026-09-03 22:03:41'),
(976,NULL,'cae3a871042e951281e453283b3f74b4',NULL,'2026-09-03 22:03:44','2026-09-03 22:03:44'),
(977,NULL,'2fab75c6cb3d1adcd1a5de32c2104ac5',NULL,'2026-09-03 22:03:45','2026-09-03 22:03:45'),
(978,NULL,'24618d2b1dd7121fe0e0d113659ca995',NULL,'2026-09-03 22:03:47','2026-09-03 22:03:47'),
(979,NULL,'2ad380fe17f312951b954d4c8ed9d9a8',NULL,'2026-09-03 22:03:49','2026-09-03 22:03:49'),
(980,NULL,'1307447dcca63ef02c3431dbe1e6cd4b',NULL,'2026-09-03 22:03:50','2026-09-03 22:03:50'),
(981,NULL,'103066580172ad2730aaedf8f738cb3c',NULL,'2026-09-03 22:03:52','2026-09-03 22:03:52'),
(982,NULL,'46322decfc67525e24889336a4be70e0',NULL,'2026-09-03 22:03:55','2026-09-03 22:03:55'),
(983,NULL,'3f00e1809350b2a4cd9c5f52c1c199b8',NULL,'2026-09-03 22:04:10','2026-09-03 22:04:10'),
(984,NULL,'9e853148d14cde96d87f0ff4c6dfd581',NULL,'2026-09-03 22:05:31','2026-09-03 22:05:31'),
(985,NULL,'fb806452a4ab4c23bca35755bc6bf664',NULL,'2026-09-03 22:05:40','2026-09-03 22:05:40'),
(986,NULL,'a51b292788c80157b0141de0969fd795',NULL,'2026-09-03 22:06:20','2026-09-03 22:06:20'),
(987,NULL,'ff8da50255fd02bc688b4b1c87cbb49d',NULL,'2026-09-03 22:06:38','2026-09-03 22:06:38'),
(988,NULL,'9d89badf49fce3b9ff5e519be00461ff',NULL,'2026-09-03 22:06:47','2026-09-03 22:06:47'),
(989,NULL,'c3b508b4f9d1d183e5907564e1575a2a',NULL,'2026-09-03 22:07:15','2026-09-03 22:07:15'),
(990,NULL,'641f031121b581571928687652176864',NULL,'2026-09-03 22:07:25','2026-09-03 22:07:25'),
(991,NULL,'f35c4c87a4aa1c4be87dd349b012cf90',NULL,'2026-09-03 22:07:35','2026-09-03 22:07:35'),
(992,NULL,'f892e52f571e1ea8df5adc224a4aca90',NULL,'2026-09-03 22:07:45','2026-09-03 22:07:45'),
(993,NULL,'d89675474421e689360b97e3f62a0a64',NULL,'2026-09-03 22:08:00','2026-09-03 22:08:00'),
(994,NULL,'be6b35d6b017da57f8a85d615ae5c15e',NULL,'2026-09-03 22:08:02','2026-09-03 22:08:02'),
(995,NULL,'ae952976e62d708f7b3c7ac158eb0cd9',NULL,'2026-09-03 22:08:04','2026-09-03 22:08:04'),
(996,NULL,'bd5fb82888ff6a92001ea87c1ab42ee7',NULL,'2026-09-03 22:08:06','2026-09-03 22:08:06'),
(997,NULL,'cc864f578250b3dfc95ecc3da9f60d88',NULL,'2026-09-03 22:08:07','2026-09-03 22:08:07'),
(998,NULL,'2ca1f0a4b0be33cbf00b624e30b629a3',NULL,'2026-09-03 22:08:07','2026-09-03 22:08:07'),
(999,NULL,'c9de8d09e8196a83f39fab236474a816',NULL,'2026-09-03 22:08:09','2026-09-03 22:08:09'),
(1000,NULL,'ce6facd239c15cb4a00a6e638c2c0283',NULL,'2026-09-03 22:08:10','2026-09-03 22:08:10'),
(1001,NULL,'e634a88b03ffd71f41383d127b422166',NULL,'2026-09-03 22:08:19','2026-09-03 22:08:19'),
(1002,NULL,'dd32f3cf72a6e16346e312bf6eab3215',NULL,'2026-09-03 22:08:26','2026-09-03 22:08:26'),
(1003,NULL,'8045b15105559d245cac9f854cda7960',NULL,'2026-09-03 22:08:32','2026-09-03 22:08:32'),
(1004,NULL,'8aec8dbf0fb71f95fe6489ea65a38735',NULL,'2026-09-03 22:08:49','2026-09-03 22:08:49'),
(1005,NULL,'b7ea3ff0d7a219ad24f2771e80f9f9f7',NULL,'2026-09-03 22:08:51','2026-09-03 22:08:51'),
(1006,NULL,'688772d3dc139f1e22cfba647518a3bb',NULL,'2026-09-03 22:08:52','2026-09-03 22:08:52'),
(1007,NULL,'a8468c7326e00b82ca66a0159e402554',NULL,'2026-09-03 22:08:54','2026-09-03 22:08:54'),
(1008,NULL,'682ea548c5e8fc069e3a593f22124e4e',NULL,'2026-09-03 22:08:55','2026-09-03 22:08:55'),
(1009,NULL,'021aeef5b794992dd6ca62c8466a55ff',NULL,'2026-09-03 22:08:57','2026-09-03 22:08:57'),
(1010,NULL,'b61e6484ce752269f02584b1987b5768',NULL,'2026-09-03 22:08:57','2026-09-03 22:08:57'),
(1011,NULL,'4d4d1895b0dac43cb36fe3f7e5d21648',NULL,'2026-09-03 22:08:58','2026-09-03 22:08:58'),
(1012,NULL,'fae72c85605302cf1077c58a2dcebac3',NULL,'2026-09-03 22:09:00','2026-09-03 22:09:00'),
(1013,NULL,'c6bc09156e70783262d48f82bb847e75',NULL,'2026-09-03 22:09:01','2026-09-03 22:09:01'),
(1014,NULL,'66274fcaa53c5164ad0321ef110e44a7',NULL,'2026-09-03 22:09:02','2026-09-03 22:09:02'),
(1015,NULL,'20e415b812f4046da1e4318690fc24d7',NULL,'2026-09-03 22:09:04','2026-09-03 22:09:04'),
(1016,NULL,'446fddc50c81b82e18113c2a35eb1197',NULL,'2026-09-03 22:09:05','2026-09-03 22:09:05'),
(1017,NULL,'5e345490666496db8d95093a6f585de6',NULL,'2026-09-03 22:09:05','2026-09-03 22:09:05'),
(1018,NULL,'166312e1e01dda818ed29668bb99711e',NULL,'2026-09-03 22:09:14','2026-09-03 22:09:14'),
(1019,NULL,'e1eaacce42e8c69a0c54258068e2e781',NULL,'2026-09-03 22:09:16','2026-09-03 22:09:16'),
(1020,NULL,'6d96e63706cee73cbc70ac1342f834d1',NULL,'2026-09-03 22:09:17','2026-09-03 22:09:17'),
(1021,NULL,'562cb3b4141042430ba2d4d684176dbc',NULL,'2026-09-03 22:09:19','2026-09-03 22:09:19'),
(1022,NULL,'be6a44e5aad64af90912b7a462354244',NULL,'2026-09-03 22:09:20','2026-09-03 22:09:20'),
(1023,NULL,'39cc8fa95996d997ac701f8f53a516a4',NULL,'2026-09-03 22:09:22','2026-09-03 22:09:22'),
(1024,NULL,'078bbc0561c0f1a0fe8de28005031d7f',NULL,'2026-09-03 22:09:22','2026-09-03 22:09:22'),
(1025,NULL,'3ea4b2bbbc6965f34da709461e0c6126',NULL,'2026-09-03 22:09:23','2026-09-03 22:09:23'),
(1026,NULL,'e1ef6877fd7a1c4be81a850deb0219a0',NULL,'2026-09-03 22:09:24','2026-09-03 22:09:24'),
(1027,NULL,'4cb2b97a8b5a4dfda7760b8c93a4fcb0',NULL,'2026-09-03 22:09:26','2026-09-03 22:09:26'),
(1028,NULL,'6ec4c8c4d7ea7f87e9199cd3b63ca8c5',NULL,'2026-09-03 22:09:27','2026-09-03 22:09:27'),
(1029,NULL,'dea02c6cd6dccd0ae5acc268cb9434fb',NULL,'2026-09-03 22:09:29','2026-09-03 22:09:29'),
(1030,NULL,'cf170a926550e7d929c5f7fb807b8f9c',NULL,'2026-09-03 22:09:30','2026-09-03 22:09:30'),
(1031,NULL,'bbc42ff4c5b175e1a4df610057fe81e0',NULL,'2026-09-03 22:09:30','2026-09-03 22:09:30'),
(1032,NULL,'3c1c52077fd7e6b8daf8dd87f3fbcc63',NULL,'2026-09-03 22:17:38','2026-09-03 22:17:38'),
(1033,NULL,'e2a78b549feaf0cb6662e4e417b3254e',NULL,'2026-09-03 22:17:42','2026-09-03 22:17:42'),
(1034,NULL,'919257180592d3da1a64bf3bd892fb3d',NULL,'2026-09-03 22:17:42','2026-09-03 22:17:42'),
(1035,NULL,'0845cb92bc6b41f3a96d6d0307497679',NULL,'2026-09-03 22:17:54','2026-09-03 22:17:54'),
(1036,NULL,'f83bf14e2a65b871dd3dceefae056584',NULL,'2026-09-03 22:17:56','2026-09-03 22:17:56'),
(1037,NULL,'fc22c3253f7395e47464b709aa10341e',NULL,'2026-09-03 22:23:21','2026-09-03 22:23:21'),
(1038,NULL,'5f97c6b0d879700a0f8ea0e77ea82afb',NULL,'2026-09-03 22:23:34','2026-09-03 22:23:34'),
(1039,NULL,'17db3f102e974085ac90959031d63043',NULL,'2026-09-03 22:23:45','2026-09-03 22:23:45'),
(1040,NULL,'867c24492a53eba693f3a1e18fb4a9d7',NULL,'2026-09-03 22:23:51','2026-09-03 22:23:51'),
(1041,NULL,'5eddc8b70eed814f670522eb855391c7',NULL,'2026-09-03 22:24:24','2026-09-03 22:24:24'),
(1042,NULL,'4e7450148e9e4516bc80daf22a2a0e43',NULL,'2026-09-03 22:25:02','2026-09-03 22:25:02'),
(1043,NULL,'dd429b225f80c1cbfdee3d731792d8f3',NULL,'2026-09-03 22:25:20','2026-09-03 22:25:20'),
(1044,NULL,'3a39e2ec30530ac69cb8fc7fcc7c80c4',NULL,'2026-09-03 22:25:22','2026-09-03 22:25:22'),
(1045,NULL,'2ec6b57541a221845b9214bd6a712697',NULL,'2026-09-03 22:25:37','2026-09-03 22:25:37'),
(1046,NULL,'ab142eb1dc233f4115238ba12cd257df',NULL,'2026-09-03 22:25:37','2026-09-03 22:25:37'),
(1047,NULL,'d418e881b5831ccdc4d7fc9d9300e0f3',NULL,'2026-09-03 22:25:37','2026-09-03 22:25:37'),
(1048,NULL,'10b63f3e4a13c18c2abdb87d9910aee7',NULL,'2026-09-03 22:40:28','2026-09-03 22:40:28'),
(1049,NULL,'57722d03d8d65054979d1f8985bd42f9',NULL,'2026-09-03 22:41:44','2026-09-03 22:41:44'),
(1050,NULL,'2cfe310aff9a7de0e7a10f7f20adfaa9',NULL,'2026-09-03 22:44:06','2026-09-03 22:44:06'),
(1051,NULL,'6e2474568653cec4c8fa6cabb41290a3',NULL,'2026-09-03 22:44:06','2026-09-03 22:44:06'),
(1052,NULL,'99a7ec3d7590d77f131b58e2dc49b4a3',NULL,'2026-09-03 22:44:06','2026-09-03 22:44:06'),
(1053,NULL,'89946c696b160d6f87021ea775ca4ddf',NULL,'2026-09-03 22:44:06','2026-09-03 22:44:06'),
(1054,NULL,'f8f8141c0871cb488242014b24ccacb9',NULL,'2026-09-03 22:44:06','2026-09-03 22:44:06'),
(1055,NULL,'1d1e5768090b73544efe30852149708f',NULL,'2026-09-03 22:44:06','2026-09-03 22:44:06'),
(1056,NULL,'f68fbc84a0b22dfaf5978eda483cf794',NULL,'2026-09-03 22:44:19','2026-09-03 22:44:19'),
(1057,NULL,'0574fb9ea52d28ed582409a282c2dfa6',NULL,'2026-09-03 22:44:34','2026-09-03 22:44:34'),
(1058,NULL,'596de322035a79022089d823701359fb',NULL,'2026-09-03 22:44:37','2026-09-03 22:44:37'),
(1059,NULL,'ea83ffb505bbfcd8ff3371b26d6853ae',NULL,'2026-09-03 22:44:51','2026-09-03 22:44:51'),
(1060,NULL,'c4d38ca0781383399b2b984659b2963b',NULL,'2026-09-03 22:45:00','2026-09-03 22:45:00'),
(1061,NULL,'5738c0eb9041b8210e4b1472a513c3d6',NULL,'2026-09-03 22:45:09','2026-09-03 22:45:09'),
(1062,NULL,'34183519801ba214861c27bfa6fd6cf6',NULL,'2026-09-03 22:45:36','2026-09-03 22:45:36'),
(1063,NULL,'0fec19fd12669b8f7b15e4eb7bce4570',NULL,'2026-09-03 22:45:40','2026-09-03 22:45:40'),
(1064,NULL,'cd4044dd714660fe5efaada2df239f4f',NULL,'2026-09-03 22:45:50','2026-09-03 22:45:50'),
(1065,NULL,'dc4ed5e9c30ffd003457c72257284ce5',NULL,'2026-09-03 22:45:54','2026-09-03 22:45:54'),
(1066,NULL,'51a453bcce064a5543103730b103ee2f',NULL,'2026-09-03 22:46:05','2026-09-03 22:46:05'),
(1067,NULL,'d1b19b382dfa998cd1582ebb9dbf4fb3',NULL,'2026-09-03 22:46:10','2026-09-03 22:46:10'),
(1068,NULL,'042bd50147d925c8db247dac2682c340',NULL,'2026-09-03 22:46:19','2026-09-03 22:46:19'),
(1069,NULL,'f883fee5679c976ef871a14a34b7c1a8',NULL,'2026-09-03 22:46:19','2026-09-03 22:46:19'),
(1070,NULL,'046a2c5ba16b5508ce4c4b9a681ef1b0',NULL,'2026-09-03 22:54:11','2026-09-03 22:54:11'),
(1071,NULL,'89ccf7a8ffa96a5a6dee4401a3fee04a',NULL,'2026-09-03 22:54:12','2026-09-03 22:54:12'),
(1072,NULL,'c5652f5d69cb9d936a6208b30c4ef990',NULL,'2026-09-03 22:54:12','2026-09-03 22:54:12'),
(1073,NULL,'37bb24af6e83e49eff01930aed06ebbc',NULL,'2026-09-03 22:54:12','2026-09-03 22:54:12'),
(1074,NULL,'e0733082988c9b660ac47707ce391732',NULL,'2026-09-03 22:54:12','2026-09-03 22:54:12'),
(1075,NULL,'8f3813bcf55d7a9d18e7756b15f7577e',NULL,'2026-09-03 22:54:12','2026-09-03 22:54:12'),
(1076,NULL,'a9c3f48a63f1f958aa4a99f20266adfb',NULL,'2026-09-03 22:54:47','2026-09-03 22:54:47'),
(1077,NULL,'8a5f2076259b95799abc52e5c6877195',NULL,'2026-09-03 22:54:47','2026-09-03 22:54:47'),
(1078,NULL,'cd53b49eec63892b8296fda1c4562c02',NULL,'2026-09-03 22:54:47','2026-09-03 22:54:47'),
(1079,NULL,'d1aa006d45f02035c08051b5fd172d3c',NULL,'2026-09-03 22:54:47','2026-09-03 22:54:47'),
(1080,NULL,'a789c2b1c8ec2cd9a2193ebb77cb5ec9',NULL,'2026-09-03 22:54:59','2026-09-03 22:54:59'),
(1083,NULL,'b497762979f01b0fc1288f97e3fca6af',NULL,'2026-09-03 22:56:14','2026-09-03 22:56:14'),
(1084,NULL,'91a14dd9b0d40167b706927621f1c98a',NULL,'2026-09-03 22:56:14','2026-09-03 22:56:14'),
(1085,NULL,'b20f45890ce6f50476bad93d200698e7',NULL,'2026-09-03 22:56:14','2026-09-03 22:56:14'),
(1086,NULL,'79ccc12c7c7e8bc00c8fa4b9da126416',NULL,'2026-09-03 22:56:14','2026-09-03 22:56:14'),
(1087,NULL,'4ac4bbd1c25b4b1e4970944fa5886384',NULL,'2026-09-03 22:56:14','2026-09-03 22:56:14'),
(1088,NULL,'a6b042daa3b5c4da1b33fce641d1a16b',NULL,'2026-09-03 22:56:14','2026-09-03 22:56:14'),
(1089,NULL,'6e03aab5e9762b52b16f63397d803d22',NULL,'2026-09-03 22:56:23','2026-09-03 22:56:23'),
(1090,NULL,'4ed30d30ca9c7a92fb31a4a5e438cbe1',NULL,'2026-09-03 22:57:31','2026-09-03 22:57:31'),
(1094,NULL,'17abfaf252f9f91500bb65fc0576ec9b',NULL,'2026-09-03 23:01:48','2026-09-03 23:01:48'),
(1095,NULL,'bf459f510fb1a0ca34a318004f98759c',NULL,'2026-09-03 23:03:42','2026-09-03 23:03:42'),
(1096,NULL,'10f6c923301c7c712db58757e5ea6813',NULL,'2026-09-03 23:04:01','2026-09-03 23:04:01'),
(1097,NULL,'387ee722288cc4c997e12564b66f7041',NULL,'2026-09-03 23:04:12','2026-09-03 23:04:12'),
(1098,NULL,'003d3211ca8296312c12a2b0192fdb90',NULL,'2026-09-03 23:04:37','2026-09-03 23:04:37'),
(1099,NULL,'36ec804cb78fd756da6a1853edc7765c',NULL,'2026-09-03 23:11:11','2026-09-03 23:11:11'),
(1100,NULL,'3387c5dcebb6949beaa58f43411f1695',NULL,'2026-09-03 23:11:38','2026-09-03 23:11:38'),
(1101,NULL,'3de84b346c10544c9c44e31b58c22d6f',NULL,'2026-09-03 23:12:01','2026-09-03 23:12:01'),
(1104,NULL,'fcba0d45455242ad3818f1f01d0eee70',NULL,'2026-09-03 23:19:15','2026-09-03 23:19:15'),
(1103,4,NULL,NULL,'2026-09-03 23:16:22','2026-09-03 23:16:22'),
(1106,NULL,'7f3c761e7273a34d34596dfa03d71b82',NULL,'2026-09-03 23:22:03','2026-09-03 23:22:03'),
(1107,NULL,'0ea9e97aab7e580b260b4e16d924d1df',NULL,'2026-09-03 23:22:04','2026-09-03 23:22:04'),
(1109,NULL,'1834194492076141a1f791d6bf9a5cad',NULL,'2026-09-03 23:25:07','2026-09-03 23:25:07'),
(1110,NULL,'32e8edc03c5954e7b5878d0c3ac91abc',NULL,'2026-09-03 23:26:04','2026-09-03 23:26:04'),
(1111,NULL,'b7536532b813cc9e5a83b4aed43e1485',NULL,'2026-09-03 23:26:37','2026-09-03 23:26:37'),
(1112,NULL,'69cfa73b0fb3ceb96fff37731ea8b64a',NULL,'2026-09-03 23:27:04','2026-09-03 23:27:04'),
(1113,NULL,'cf9143cbba8fb0558f885dae1346d90d',NULL,'2026-09-03 23:27:18','2026-09-03 23:27:18'),
(1114,NULL,'e3953d4130920e3e72dafecad066e7cb',NULL,'2026-09-03 23:27:49','2026-09-03 23:27:49'),
(1115,NULL,'5b23d8127d32af3371079a7dddff6b24',NULL,'2026-09-03 23:34:24','2026-09-03 23:34:24'),
(1116,NULL,'3b98385d1e0bf179eee113c65c8a92ce',NULL,'2026-09-03 23:34:30','2026-09-03 23:34:30'),
(1117,NULL,'3758e1d49f0a361c5c5eb0fe5ec291a2',NULL,'2026-09-03 23:34:42','2026-09-03 23:34:42'),
(1118,NULL,'03dab8c1d7aaaa5bbc2637d2b1e6b497',NULL,'2026-09-03 23:35:28','2026-09-03 23:35:28'),
(1119,NULL,'eaf67cfe50cbe6f034fc42e8bfb5d737',NULL,'2026-09-03 23:36:20','2026-09-03 23:36:20'),
(1120,NULL,'f66b8d73a5b9a77d5a83d4f3128454d3',NULL,'2026-09-03 23:36:31','2026-09-03 23:36:31'),
(1121,NULL,'381a12da806186cd9718b9cc20b1b5dc',NULL,'2026-09-03 23:36:37','2026-09-03 23:36:37'),
(1122,NULL,'2e0f5afe9e51535ce062642722acf9c6',NULL,'2026-09-03 23:36:55','2026-09-03 23:36:55'),
(1123,NULL,'a25c482e6e89f4ff45d81d7003ea770c',NULL,'2026-09-03 23:37:06','2026-09-03 23:37:06'),
(1124,NULL,'e39ea7f47fe70cfc80c539050faad767',NULL,'2026-09-03 23:37:17','2026-09-03 23:37:17'),
(1151,NULL,'9264ed1c72caf52aca97213af506379e',NULL,'2026-09-04 00:12:29','2026-09-04 00:12:29'),
(1126,5,NULL,NULL,'2026-09-03 23:48:58','2026-09-03 23:48:58'),
(1152,NULL,'864c77989f8e651ffc05a9ea425ce446',NULL,'2026-09-04 00:12:40','2026-09-04 00:12:40'),
(1153,NULL,'f4a5848c246b1eadbcbcd243aaa527e2',NULL,'2026-09-04 00:13:57','2026-09-04 00:13:57'),
(1154,NULL,'0fe18dc39b37106b609360e91701b506',NULL,'2026-09-04 00:14:58','2026-09-04 00:14:58'),
(1155,NULL,'9b5e51eb9d348ecc96f6bdf542b34c00',NULL,'2026-09-04 00:15:51','2026-09-04 00:15:51'),
(1156,NULL,'afd6982e677fb63da93274e906a90c1e',NULL,'2026-09-04 00:16:02','2026-09-04 00:16:02'),
(1157,NULL,'c4b4adc193fc5716c84ac2936877450e',NULL,'2026-09-04 00:16:46','2026-09-04 00:16:46'),
(1158,NULL,'dd66d9fa0b885494621ce871c6466755',NULL,'2026-09-04 00:17:09','2026-09-04 00:17:09'),
(1159,NULL,'22db3d71984d2be9cf1e74afce1a3f95',NULL,'2026-09-04 00:17:14','2026-09-04 00:17:14'),
(1160,NULL,'5e266caa8c2079a9c8ca93b6fca785e3',NULL,'2026-09-04 00:17:41','2026-09-04 00:17:41'),
(1161,NULL,'e51df1038ad739d467752bb5598bd502',NULL,'2026-09-04 00:17:43','2026-09-04 00:17:43'),
(1163,NULL,'960093ddb31b8069f39bd7036399b62c',NULL,'2026-09-04 00:21:50','2026-09-04 00:21:50'),
(1165,NULL,'21b1d72114c8a2236205b8eda20b0282',NULL,'2026-09-04 00:22:39','2026-09-04 00:22:39'),
(1168,NULL,'60f4ae0cf3cf917edaadc829913e7ca1',NULL,'2026-09-04 00:24:07','2026-09-04 00:24:07'),
(1169,NULL,'ea2f005db4154f2e1e4c6b0685911ca9',NULL,'2026-09-04 00:24:36','2026-09-04 00:24:36'),
(1170,NULL,'2459faf41ee942eced6d221f9c697dba',NULL,'2026-09-04 00:25:16','2026-09-04 00:25:16'),
(1172,NULL,'9bf157bb521f57d87cab3c855f2d5949',NULL,'2026-09-04 00:28:27','2026-09-04 00:28:27'),
(1173,NULL,'59365741212942c0dbdfd0daf724d233',NULL,'2026-09-04 00:31:47','2026-09-04 00:31:47'),
(1174,NULL,'08b1a34d795d6216ca13eaca0a1aa00a',NULL,'2026-09-04 00:31:47','2026-09-04 00:31:47'),
(1175,NULL,'5b126a6158dc36261c85f6cb57821049',NULL,'2026-09-04 00:32:04','2026-09-04 00:32:04'),
(1176,NULL,'45b174a568afe209f7d865888f580245',NULL,'2026-09-04 00:32:53','2026-09-04 00:32:53'),
(1177,NULL,'45d17572add65d990c7d0925c2ef308b',NULL,'2026-09-04 00:32:53','2026-09-04 00:32:53'),
(1179,NULL,'00809e889acb89e035ac9c89aecc49e4',NULL,'2026-09-04 00:34:01','2026-09-04 00:34:01'),
(1180,NULL,'cf21847dac312820f9d2b3bf35d7131f',NULL,'2026-09-04 00:34:01','2026-09-04 00:34:01'),
(1181,NULL,'03c10f6f8ccb5e118b0c407022629ef1',NULL,'2026-09-04 00:34:01','2026-09-04 00:34:01'),
(1182,NULL,'9f86572d68fcc423d28dfcd9b7a96f09',NULL,'2026-09-04 00:34:01','2026-09-04 00:34:01'),
(1183,NULL,'df7774e9b2f3a407be24043bd865ed9e',NULL,'2026-09-04 00:34:01','2026-09-04 00:34:01'),
(1184,NULL,'7647e1ab5cb5ae65a19dffbf528fbf51',NULL,'2026-09-04 00:34:02','2026-09-04 00:34:02'),
(1185,NULL,'e30ed689a0061de39b984c4b1ad4a86d',NULL,'2026-09-04 00:34:02','2026-09-04 00:34:02'),
(1186,NULL,'f90a458f11114ee6fc2fa00a339fa569',NULL,'2026-09-04 00:34:02','2026-09-04 00:34:02'),
(1187,NULL,'0c78b733bb9ef9cf2562f78eacc4a65d',NULL,'2026-09-04 00:34:02','2026-09-04 00:34:02'),
(1188,NULL,'08cabe489ec39f997e8fa9a0a4723c47',NULL,'2026-09-04 00:34:02','2026-09-04 00:34:02'),
(1194,NULL,'dd2aa9b1676861535ca81482790d9cce',NULL,'2026-09-04 01:24:05','2026-09-04 01:24:05');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `card_image` varchar(500) DEFAULT NULL,
  `icon` varchar(64) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `banner_heading` varchar(190) DEFAULT NULL,
  `banner_subheading` varchar(300) DEFAULT NULL,
  `banner_image` varchar(500) DEFAULT NULL,
  `banner_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`banner_images`)),
  `banner_cta_text` varchar(80) DEFAULT NULL,
  `banner_cta_url` varchar(500) DEFAULT NULL,
  `banner_bg_color` varchar(20) DEFAULT NULL,
  `brand_logo` varchar(500) DEFAULT NULL,
  `brand_name` varchar(120) DEFAULT NULL,
  `sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sections`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_is_active_index` (`parent_id`,`is_active`),
  KEY `categories_sort_order_index` (`sort_order`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(16,NULL,'Ghee','ghee','Certified organic desi ghee — traditional bilona method, rich and aromatic.',NULL,NULL,NULL,1,1,1,NULL,NULL,'Pure Desi Ghee','Traditional bilona-churned, certified organic cow ghee.',NULL,NULL,NULL,NULL,'#00584b',NULL,'Organic Ghee',NULL,'2026-09-02 10:49:49','2026-09-02 11:08:08',NULL),
(17,16,'Jar Type','ghee-jar-type','Our desi ghee in convenient glass jars.',NULL,NULL,NULL,1,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'#00584b',NULL,NULL,NULL,'2026-09-02 10:49:49','2026-09-02 10:49:49',NULL),
(18,16,'Packed Type','ghee-packed-type','Desi ghee in sealed packs for everyday use.',NULL,NULL,NULL,2,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'#00584b',NULL,NULL,NULL,'2026-09-02 10:49:49','2026-09-02 10:49:49',NULL),
(19,16,'Multitype Ghee','ghee-multitype','A curated selection of our premium ghee varieties.',NULL,NULL,NULL,3,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'#00584b',NULL,NULL,NULL,'2026-09-02 10:49:49','2026-09-02 10:49:49',NULL),
(20,NULL,'Oil','oil','Cold-pressed and kachi ghani organic oils, pure and unrefined.',NULL,NULL,NULL,2,1,1,NULL,NULL,'Cold-Pressed Oils','Wood-pressed, naturally extracted organic oils.',NULL,NULL,NULL,NULL,'#00584b',NULL,'Organic Oils',NULL,'2026-09-02 10:49:49','2026-09-02 10:49:49',NULL),
(21,NULL,'Atta','atta','Stone-ground organic flours and atta — fresh, wholesome, chemical-free.',NULL,NULL,NULL,3,1,1,NULL,NULL,'Stone-Ground Atta','Certified organic flours milled fresh for you.',NULL,NULL,NULL,NULL,'#00584b',NULL,'Organic Atta',NULL,'2026-09-02 10:49:49','2026-09-02 10:49:49',NULL),
(22,NULL,'Combo','combo',NULL,NULL,NULL,NULL,0,1,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cod_collections`
--

DROP TABLE IF EXISTS `cod_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cod_collections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint(20) unsigned NOT NULL,
  `collected_by` bigint(20) unsigned NOT NULL,
  `collector_type` varchar(32) NOT NULL DEFAULT 'delivery_person',
  `amount` decimal(10,2) NOT NULL,
  `collected_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notes` text DEFAULT NULL,
  `receipt_ref` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cod_collections_payment_id_unique` (`payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cod_collections`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cod_collections` WRITE;
/*!40000 ALTER TABLE `cod_collections` DISABLE KEYS */;
INSERT INTO `cod_collections` VALUES
(3,11,2,'delivery_person',7470.00,'2026-09-04 00:05:17','COD collected on delivery',NULL,'2026-09-04 05:35:17');
/*!40000 ALTER TABLE `cod_collections` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupon_categories`
--

DROP TABLE IF EXISTS `coupon_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_categories` (
  `coupon_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`coupon_id`,`category_id`),
  KEY `coupon_categories_category_id_foreign` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupon_categories` WRITE;
/*!40000 ALTER TABLE `coupon_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupon_products`
--

DROP TABLE IF EXISTS `coupon_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_products` (
  `coupon_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`coupon_id`,`product_id`),
  KEY `coupon_products_product_id_foreign` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_products`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupon_products` WRITE;
/*!40000 ALTER TABLE `coupon_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_products` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupon_usages`
--

DROP TABLE IF EXISTS `coupon_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `coupon_usages_user_id_foreign` (`user_id`),
  KEY `coupon_usages_order_id_foreign` (`order_id`),
  KEY `coupon_usages_coupon_id_user_id_index` (`coupon_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_usages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupon_usages` WRITE;
/*!40000 ALTER TABLE `coupon_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_usages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int(10) unsigned DEFAULT NULL,
  `usage_count` int(10) unsigned NOT NULL DEFAULT 0,
  `per_user_limit` int(10) unsigned NOT NULL DEFAULT 1,
  `first_order_only` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES
(1,'FRESH15','15% off for new customers','percentage',15.00,200.00,499.00,NULL,NULL,NULL,0,1,1,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(2,'ORGANIC100','Flat ?100 off above ?999','fixed',100.00,NULL,999.00,NULL,NULL,500,0,3,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(3,'GHEE50','?50 off on ghee & oils','fixed',50.00,NULL,400.00,NULL,NULL,NULL,0,2,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `delivery_areas`
--

DROP TABLE IF EXISTS `delivery_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_areas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pincode` varchar(10) NOT NULL,
  `city` varchar(120) NOT NULL,
  `state` varchar(120) DEFAULT NULL,
  `area` varchar(160) DEFAULT NULL,
  `is_serviceable` tinyint(1) NOT NULL DEFAULT 1,
  `delivery_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `eta_days` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `cod_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_areas_pincode_is_serviceable_index` (`pincode`,`is_serviceable`),
  KEY `delivery_areas_pincode_index` (`pincode`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_areas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `delivery_areas` WRITE;
/*!40000 ALTER TABLE `delivery_areas` DISABLE KEYS */;
INSERT INTO `delivery_areas` VALUES
(1,'751001','Bhubaneswar','Odisha','Old Town',1,49.00,1,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(2,'751002','Bhubaneswar','Odisha','Laxmi Sagar',1,49.00,1,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(3,'751007','Bhubaneswar','Odisha','Saheed Nagar',1,49.00,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(4,'751009','Bhubaneswar','Odisha','Patia',1,49.00,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(5,'751024','Bhubaneswar','Odisha','Chandrasekharpur',1,49.00,1,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(6,'752101','Cuttack','Odisha',NULL,1,79.00,2,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(7,'110001','New Delhi','Delhi',NULL,0,0.00,1,0,'2026-08-26 14:41:24','2026-08-26 14:41:24');
/*!40000 ALTER TABLE `delivery_areas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `delivery_assignments`
--

DROP TABLE IF EXISTS `delivery_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `delivery_person_id` bigint(20) unsigned NOT NULL,
  `assigned_by` bigint(20) unsigned DEFAULT NULL,
  `status` enum('assigned','picked_up','out_for_delivery','delivered','failed') NOT NULL DEFAULT 'assigned',
  `attempt_count` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `failed_reason` varchar(255) DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_assignments_order_id_foreign` (`order_id`),
  KEY `delivery_assignments_assigned_by_foreign` (`assigned_by`),
  KEY `delivery_assignments_delivery_person_id_status_index` (`delivery_person_id`,`status`),
  KEY `delivery_assignments_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_assignments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `delivery_assignments` WRITE;
/*!40000 ALTER TABLE `delivery_assignments` DISABLE KEYS */;
INSERT INTO `delivery_assignments` VALUES
(4,11,2,1,'delivered',0,NULL,'2026-09-04 05:25:53','2026-09-04 00:05:17','2026-09-03 23:55:53','2026-09-04 00:05:17');
/*!40000 ALTER TABLE `delivery_assignments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `delivery_persons`
--

DROP TABLE IF EXISTS `delivery_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_persons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `employee_code` varchar(32) NOT NULL,
  `vehicle_number` varchar(32) DEFAULT NULL,
  `joined_on` date DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `delivery_persons_user_id_unique` (`user_id`),
  UNIQUE KEY `delivery_persons_employee_code_unique` (`employee_code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_persons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `delivery_persons` WRITE;
/*!40000 ALTER TABLE `delivery_persons` DISABLE KEYS */;
INSERT INTO `delivery_persons` VALUES
(1,4,'DP-001','OD-05-AB-1234','2026-02-26',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(2,5,'DP-002','OD-05-BC-5678','2026-02-26',1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(3,6,'DP-003',NULL,'2026-02-26',1,'2026-08-26 14:41:24','2026-08-26 14:41:24');
/*!40000 ALTER TABLE `delivery_persons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `discounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('percentage','fixed','bulk_tier') NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tiers`)),
  `scope` enum('product','category','cart') NOT NULL DEFAULT 'product',
  `discountable_type` varchar(255) DEFAULT NULL,
  `discountable_id` bigint(20) unsigned DEFAULT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int(10) unsigned DEFAULT NULL,
  `usage_count` int(10) unsigned NOT NULL DEFAULT 0,
  `priority` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discounts_discountable_type_discountable_id_index` (`discountable_type`,`discountable_id`),
  KEY `discounts_is_active_starts_at_ends_at_index` (`is_active`,`starts_at`,`ends_at`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES
(1,'Monsoon Rice Fest','percentage',10.00,NULL,'category','App\\Models\\Category',5,150.00,'2026-08-24 14:41:24','2026-09-07 14:41:24',NULL,0,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(2,'Almond Flash Deal','fixed',100.00,NULL,'product','App\\Models\\Product',23,NULL,'2026-08-25 14:41:24','2026-08-31 14:41:24',NULL,0,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24'),
(3,'Brown Rice Bulk Saver','bulk_tier',0.00,'[{\"qty\":2,\"percent\":5},{\"qty\":5,\"percent\":10}]','product','App\\Models\\Product',1,NULL,NULL,NULL,NULL,0,0,1,'2026-08-26 14:41:24','2026-08-26 14:41:24');
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `homepage_sections`
--

DROP TABLE IF EXISTS `homepage_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `homepage_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `homepage_sections_key_unique` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homepage_sections`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `homepage_sections` WRITE;
/*!40000 ALTER TABLE `homepage_sections` DISABLE KEYS */;
INSERT INTO `homepage_sections` VALUES
(1,'hero','Hero','High Protein Atta · All Products',1,10,'{\"slides\":[{\"desktop\":\"sections\\/hero-desktop.webp\",\"mobile\":\"sections\\/hero-mobile.webp\",\"alt\":\"High Protein Atta\",\"url\":\"http:\\/\\/localhost:8000\\/categories\"},{\"desktop\":\"sections\\/hero2-desktop.webp\",\"mobile\":\"sections\\/hero2-mobile.webp\",\"alt\":\"Explore all products\",\"url\":\"http:\\/\\/localhost:8000\\/categories\"}],\"product_count\":null,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-08-26 14:41:24','2026-09-03 00:28:36'),
(2,'trust_badges','Why Choose AB Organic Farm?','The AB Organic Farm difference',1,20,'{\"product_count\":null,\"items\":[{\"icon\":\"map-pin\",\"title\":\"Native Sourcing\",\"text\":\"Highest quality raw material from native regions all over India.\"},{\"icon\":\"leaf\",\"title\":\"Traditional Processing\",\"text\":\"Minimally processed using time-tested methods, made better. For maximum nutrition.\"},{\"icon\":\"shield-check\",\"title\":\"Extensive Quality Checks\",\"text\":\"Everything goes through rigorous lab tests, so you get only what is best.\"},{\"icon\":\"users\",\"title\":\"Better Rural Lives\",\"text\":\"Farmer families are empowered with every product you buy.\"}],\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-08-26 14:41:24','2026-09-03 00:28:39'),
(5,'trending','Trending Now','What our community is loving right now',0,120,'{\"product_count\":10,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-08-26 14:41:24','2026-09-03 00:37:54'),
(6,'best_sellers','Best Sellers','Trusted by thousands of households',1,110,'{\"product_count\":10,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-08-26 14:41:24','2026-09-03 00:40:57'),
(8,'new_arrivals','New Arrivals','Just stocked, fresh off the farm',0,130,'{\"product_count\":10,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-08-26 14:41:24','2026-09-03 00:28:36'),
(22,'logo_slider','Trusted by','',1,140,'{\"product_count\":null,\"images\":{\"alt\":\"\",\"desktop\":\"sections\\/dW8MoIVekingeW8g14CwzCtEFizz18nTjTNeFw8I.svg\",\"mobile\":\"\"},\"logos\":[\"logos\\/OYRaPN63gSxpGniYKjUlRc8JwxdOMdvQPLPqtnJq.svg\",\"logos\\/f81gFttHkXufHXCsdBniXbhp6PItUQ2lCN2EnoX2.svg\"]}','2026-09-01 10:30:19','2026-09-03 09:21:38'),
(12,'testimonials','What Do Our Customers Say','',1,70,'{\"product_count\":8,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-08-26 14:41:24','2026-09-03 00:28:36'),
(13,'welcome','Welcome To AB Organic Farm!','You\'re One Step Closer to Purity',1,15,'{\"product_count\":12,\"tabs\":[{\"title\":\"All\",\"key\":\"all\",\"type\":\"all\",\"inactive_icon\":\"images\\/nav\\/nav-all.svg\",\"active_icon\":\"images\\/nav\\/nav-all-active.svg\"},{\"title\":\"Ghee\",\"key\":\"ghee\",\"type\":\"category\",\"value\":\"ghee\",\"inactive_icon\":\"images\\/nav\\/nav-ghee.svg\",\"active_icon\":\"images\\/nav\\/nav-ghee-active.svg\"},{\"title\":\"Oil\",\"key\":\"oil\",\"type\":\"category\",\"value\":\"oil\",\"inactive_icon\":\"images\\/nav\\/nav-oils.svg\",\"active_icon\":\"images\\/nav\\/nav-oils-active.svg\"},{\"title\":\"Atta\",\"key\":\"atta\",\"type\":\"category\",\"value\":\"atta\",\"inactive_icon\":\"images\\/nav\\/nav-atta.svg\",\"active_icon\":\"images\\/nav\\/nav-atta-active.svg\"},{\"title\":\"Combos\",\"key\":\"combos\",\"type\":\"keyword\",\"value\":\"combo\",\"inactive_icon\":\"images\\/nav\\/nav-combos.svg\",\"active_icon\":\"images\\/nav\\/nav-combos-active.svg\",\"fallback\":{\"type\":\"categories\",\"values\":[\"ghee\",\"oil\",\"atta\"]}},{\"title\":\"Deal\",\"key\":\"deal\",\"type\":\"deal\",\"inactive_icon\":\"images\\/nav\\/nav-deal.svg\",\"active_icon\":\"images\\/nav\\/nav-deal-active.svg\"}],\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 09:44:06','2026-09-03 00:30:04'),
(14,'focus_ghee','Product in Focus:','Explore Our A2 Desi Ghee',1,35,'{\"product_count\":8,\"tabs\":[{\"title\":\"Gir\",\"key\":\"gir\",\"type\":\"keyword\",\"value\":\"gir\",\"inactive_icon\":\"images\\/nav\\/nav-gir.svg\",\"active_icon\":\"images\\/nav\\/nav-gir-active.svg\",\"fallback\":{\"type\":\"keyword\",\"value\":\"ghee\"}},{\"title\":\"Desi Cow\",\"key\":\"desi-cow\",\"type\":\"keyword\",\"value\":\"ghee\",\"inactive_icon\":\"images\\/nav\\/nav-desi.svg\",\"active_icon\":\"images\\/nav\\/nav-desi-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"ghee\"}},{\"title\":\"Buffalo\",\"key\":\"buffalo\",\"type\":\"keyword\",\"value\":\"buffalo\",\"inactive_icon\":\"images\\/nav\\/nav-buffalo.svg\",\"active_icon\":\"images\\/nav\\/nav-buffalo-active.svg\",\"fallback\":{\"type\":\"keyword\",\"value\":\"ghee\"}},{\"title\":\"Combo\",\"key\":\"ghee-combo\",\"type\":\"keyword\",\"value\":\"combo\",\"inactive_icon\":\"images\\/nav\\/nav-combo.svg\",\"active_icon\":\"images\\/nav\\/nav-combo-active.svg\",\"fallback\":{\"type\":\"keyword\",\"value\":\"ghee\"}}],\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 09:44:06','2026-09-03 00:36:59'),
(15,'focus_oils','Product in Focus:','Explore Our Cold-Pressed Oils',1,32,'{\"product_count\":8,\"tabs\":[{\"title\":\"Groundnut\",\"key\":\"groundnut\",\"type\":\"keyword\",\"value\":\"groundnut\",\"inactive_icon\":\"images\\/nav\\/nav-groundnut.svg\",\"active_icon\":\"images\\/nav\\/nav-groundnut-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"oil\"}},{\"title\":\"Mustard\",\"key\":\"mustard\",\"type\":\"keyword\",\"value\":\"mustard oil\",\"inactive_icon\":\"images\\/nav\\/nav-mustard.svg\",\"active_icon\":\"images\\/nav\\/nav-mustard-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"oil\"}},{\"title\":\"Sunflower\",\"key\":\"sunflower\",\"type\":\"keyword\",\"value\":\"sunflower\",\"inactive_icon\":\"images\\/nav\\/nav-sunflower.svg\",\"active_icon\":\"images\\/nav\\/nav-sunflower-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"oil\"}},{\"title\":\"Olive\",\"key\":\"olive\",\"type\":\"keyword\",\"value\":\"olive\",\"inactive_icon\":\"images\\/nav\\/nav-olive.svg\",\"active_icon\":\"images\\/nav\\/nav-olive-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"oil\"}},{\"title\":\"Coconut\",\"key\":\"coconut\",\"type\":\"keyword\",\"value\":\"coconut oil\",\"inactive_icon\":\"images\\/nav\\/nav-coconut.svg\",\"active_icon\":\"images\\/nav\\/nav-coconut-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"oil\"}},{\"title\":\"Sesame\",\"key\":\"sesame\",\"type\":\"keyword\",\"value\":\"sesame\",\"inactive_icon\":\"images\\/nav\\/nav-sesame.svg\",\"active_icon\":\"images\\/nav\\/nav-sesame-active.svg\",\"fallback\":{\"type\":\"category\",\"value\":\"oil\"}}],\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 09:44:06','2026-09-03 00:36:59'),
(16,'quality','Only Perfect Makes The Cut','',1,40,'{\"bg_desktop\":\"sections\\/perfect-bg.svg\",\"bg_mobile\":\"sections\\/perfect-bg-mobile.svg\",\"title_color\":\"#4199A8\",\"carousel\":[{\"image\":\"sections\\/perfect1.webp\",\"alt\":\"Only perfect makes the cut\"},{\"image\":\"sections\\/perfect2.webp\",\"alt\":\"Only perfect makes the cut\"},{\"image\":\"sections\\/perfect3.webp\",\"alt\":\"Only perfect makes the cut\"},{\"image\":\"sections\\/perfect4.webp\",\"alt\":\"Only perfect makes the cut\"}],\"product_count\":null,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 09:44:06','2026-09-03 00:28:36'),
(21,'superfoods','Explore our Superfoods','',1,60,'{\"product_count\":10,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 10:30:19','2026-09-03 00:28:36'),
(20,'combos','Healthy Combo Packs','',1,50,'{\"product_count\":10,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 10:30:19','2026-09-03 00:28:36'),
(19,'native_ingredients','Native Ingredients. No Substitutes.','',1,30,'{\"bg_desktop\":\"sections\\/native-bg.svg\",\"bg_mobile\":\"sections\\/native-bg-mobile.svg\",\"title_color\":\"#B5762A\",\"carousel\":[{\"image\":\"sections\\/native1.jpg\",\"alt\":\"Native ingredients\"},{\"image\":\"sections\\/native2.jpg\",\"alt\":\"Native ingredients\"},{\"image\":\"sections\\/native3.jpg\",\"alt\":\"Native ingredients\"},{\"image\":\"sections\\/native4.webp\",\"alt\":\"Native ingredients\"}],\"product_count\":null,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-01 10:30:19','2026-09-03 00:28:36'),
(23,'app_download','Download the AB Organic App','Order, track and save — all from the AB Organic app.',0,150,'{\"images\":{\"desktop\":\"sections\\/app-icon.jpg\",\"mobile\":\"sections\\/app-icon.jpg\",\"alt\":\"AB Organic app\"},\"android_url\":\"#\",\"ios_url\":\"#\",\"product_count\":null}','2026-09-01 12:01:49','2026-09-03 00:28:36'),
(24,'promotional_banners','Promotions & Deals','',1,45,'{\"product_count\":null,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-02 01:22:05','2026-09-03 00:28:36'),
(25,'recently_viewed','You were checking these out earlier.','Don\'t miss out; Complete your purchase Now.',1,36,'{\"product_count\":12,\"images\":{\"alt\":\"\",\"desktop\":\"\",\"mobile\":\"\"}}','2026-09-02 04:13:20','2026-09-03 00:36:59');
/*!40000 ALTER TABLE `homepage_sections` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_variant_id` bigint(20) unsigned NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `reserved` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(10) unsigned NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventories_product_variant_id_unique` (`product_variant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `inventories` WRITE;
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
INSERT INTO `inventories` VALUES
(173,173,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(172,172,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(171,171,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(170,170,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(169,169,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(168,168,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(167,167,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(166,166,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(165,165,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(164,164,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(163,163,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(162,162,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(161,161,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(160,160,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(159,159,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(158,158,120,0,10,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(157,157,0,0,10,'2026-09-03 03:22:27','2026-09-03 03:22:27'),
(156,156,47,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(155,155,115,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(154,154,38,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(153,153,85,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(152,152,30,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(151,151,104,0,10,'2026-09-03 03:13:10','2026-09-04 00:05:17'),
(150,150,21,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(149,149,37,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(148,148,20,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(147,147,80,0,10,'2026-09-03 03:13:10','2026-09-03 03:13:10');
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `inventory_transactions`
--

DROP TABLE IF EXISTS `inventory_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('purchase','sale','return','adjustment','reservation','release','cancel','damage') NOT NULL,
  `quantity` int(11) NOT NULL,
  `stock_after` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `inventory_transactions_user_id_foreign` (`user_id`),
  KEY `inventory_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `inventory_transactions_inventory_id_created_at_index` (`inventory_id`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_transactions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `inventory_transactions` WRITE;
/*!40000 ALTER TABLE `inventory_transactions` DISABLE KEYS */;
INSERT INTO `inventory_transactions` VALUES
(62,151,5,'sale',-3,104,'Sold on order dispatch','App\\Models\\Order',11,'2026-09-04 05:35:17'),
(61,151,5,'release',-3,107,'Dispatched','App\\Models\\Order',11,'2026-09-04 05:35:17'),
(60,151,5,'sale',-3,107,'Sold on order dispatch','App\\Models\\Order',11,'2026-09-04 05:34:45'),
(59,151,5,'release',-3,110,'Dispatched','App\\Models\\Order',11,'2026-09-04 05:34:45'),
(58,151,1,'reservation',3,110,'Order ORD-2026-000001','App\\Models\\Order',11,'2026-09-04 03:13:56'),
(57,156,NULL,'purchase',47,47,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(56,155,NULL,'purchase',115,115,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(55,154,NULL,'purchase',38,38,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(54,153,NULL,'purchase',85,85,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(53,152,NULL,'purchase',30,30,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(52,151,NULL,'purchase',110,110,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(51,150,NULL,'purchase',21,21,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(50,149,NULL,'purchase',37,37,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(49,148,NULL,'purchase',20,20,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10'),
(48,147,NULL,'purchase',80,80,'Opening stock (seed)',NULL,NULL,'2026-09-03 08:43:10');
/*!40000 ALTER TABLE `inventory_transactions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2024_01_01_000001_create_rbac_tables',1),
(5,'2024_01_01_000002_create_catalog_tables',1),
(6,'2024_01_01_000003_create_marketing_tables',1),
(7,'2024_01_01_000004_create_shopping_tables',1),
(8,'2024_01_01_000005_create_order_tables',1),
(9,'2024_01_01_000006_create_system_tables',1),
(10,'2024_01_01_000007_create_notifications_table',1),
(11,'2026_08_25_123916_create_recently_vieweds_table',1),
(12,'2026_08_26_090058_add_show_text_to_banners_table',1),
(13,'2026_08_27_000001_create_admin_notifications_table',2),
(14,'2026_08_28_095425_add_width_height_to_banners_table',3),
(15,'2026_09_01_150940_add_badge_and_promo_to_products_table',4),
(16,'2026_09_01_160033_create_notify_me_table',5),
(17,'2026_09_02_120556_add_banner_fields_to_categories_table',6),
(18,'2026_09_02_121835_add_sections_to_categories_table',7),
(19,'2026_09_02_123657_add_banner_images_to_categories_table',8),
(20,'2026_09_02_214500_add_card_image_to_categories_table',9),
(21,'2026_09_03_084546_relax_reviews_order_nullable',10),
(22,'2026_09_04_090000_create_pages_table',11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  KEY `notifications_notifiable_type_notifiable_id_read_at_index` (`notifiable_type`,`notifiable_id`,`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES
('0040b8ac-781c-40cc-a307-7911d038ac61','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',2,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000009 (\\u20b9680.50)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/9\",\"type\":\"order\"}',NULL,'2026-09-01 11:39:16','2026-09-01 11:39:16'),
('08c3c360-c3f4-4644-936c-7ad63c428aa5','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Out for Delivery\",\"message\":\"ORD-2026-000001 is now Out for Delivery.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/11\",\"type\":\"order_status\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17'),
('2029edda-b9d9-4fab-9da7-b027179d612c','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',1,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000001 (\\u20b97,470.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/11\",\"type\":\"order\"}',NULL,'2026-09-03 21:43:56','2026-09-03 21:43:56'),
('28e52e55-a0f9-40f9-b0a4-39c118aa3f05','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',2,'{\"title\":\"New order received\",\"message\":\"subrat Kumar sahoo placed order ORD-2026-000008 (\\u20b91,643.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/8\",\"type\":\"order\"}',NULL,'2026-09-01 05:27:21','2026-09-01 05:27:21'),
('2b040f73-2ef8-4a02-a111-733cfd970915','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',1,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000010 (\\u20b9916.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/10\",\"type\":\"order\"}',NULL,'2026-09-01 12:14:03','2026-09-01 12:14:03'),
('2c4b783d-2e6f-452d-a55e-9d9a8664d824','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Preparing\",\"message\":\"ORD-2026-000001 is now Preparing.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/11\",\"type\":\"order_status\"}',NULL,'2026-09-03 23:54:47','2026-09-03 23:54:47'),
('2f819ff6-8b91-4fe5-a8a0-28aab2b7c485','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Out for Delivery\",\"message\":\"ORD-2026-000001 is now Out for Delivery.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/11\",\"type\":\"order_status\"}',NULL,'2026-09-04 00:04:45','2026-09-04 00:04:45'),
('325ea547-5c71-4e51-bc4e-0989475aa6e1','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',3,'{\"title\":\"COD collected\",\"message\":\"\\u20b92,218.00 collected for ORD-2026-001000\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-08-25 06:43:28','2026-08-25 06:43:28'),
('3807c0d6-01f7-480b-a929-7ee39e309221','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',3,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000010 (\\u20b9916.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/10\",\"type\":\"order\"}',NULL,'2026-09-01 12:14:03','2026-09-01 12:14:03'),
('39bfed28-0c1a-49d2-b2d9-9d66f524c400','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',1,'{\"title\":\"COD collected\",\"message\":\"\\u20b92,218.00 collected for ORD-2026-001000\",\"url\":\"#\",\"type\":\"cod\"}','2026-08-26 12:30:18','2026-08-25 06:43:28','2026-08-26 12:30:18'),
('3c0200ab-794d-48de-af0e-7c9a85032029','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',1,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000006 (\\u20b91,584.00)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/6\",\"type\":\"order\"}','2026-08-26 12:30:18','2026-08-26 12:24:36','2026-08-26 12:30:18'),
('413b1c49-30c0-47a1-8889-6e015e3c0a5c','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',2,'{\"title\":\"COD collected\",\"message\":\"\\u20b97,470.00 collected for ORD-2026-000001\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17'),
('47a791c5-3512-4ff6-b5c2-a114dd1f43bb','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',2,'{\"title\":\"COD collected\",\"message\":\"\\u20b9797.00 collected for ORD-2026-001001\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-08-25 06:43:28','2026-08-25 06:43:28'),
('4d24e629-06d2-4147-8a5c-bd24b8e31664','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',2,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000007 (\\u20b9689.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/7\",\"type\":\"order\"}',NULL,'2026-09-01 04:13:50','2026-09-01 04:13:50'),
('4deb8ae3-a40a-4cfb-9d64-4f1c82af6b41','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',3,'{\"title\":\"COD collected\",\"message\":\"\\u20b9797.00 collected for ORD-2026-001001\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-08-25 06:43:28','2026-08-25 06:43:28'),
('7008699b-6196-40ed-8280-b37b6777c523','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',2,'{\"title\":\"COD collected\",\"message\":\"\\u20b92,218.00 collected for ORD-2026-001000\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-08-25 06:43:28','2026-08-25 06:43:28'),
('771bda73-f37b-43bf-9b28-cf259159b032','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Delivered\",\"message\":\"ORD-2026-000001 is now Delivered.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/11\",\"type\":\"order_status\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17'),
('7982c121-f9b1-4f37-81e3-ac96619c2156','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',2,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000010 (\\u20b9916.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/10\",\"type\":\"order\"}',NULL,'2026-09-01 12:14:03','2026-09-01 12:14:03'),
('8deb3f29-c15d-4aaf-abfc-2cfc7154b1ba','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',2,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000001 (\\u20b97,470.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/11\",\"type\":\"order\"}',NULL,'2026-09-03 21:43:56','2026-09-03 21:43:56'),
('916f500c-9a45-43b2-b6e1-a5d8f314650b','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',2,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000006 (\\u20b91,584.00)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/6\",\"type\":\"order\"}',NULL,'2026-08-26 12:24:36','2026-08-26 12:24:36'),
('973dc744-fd94-4c9d-8b64-b231e89002b3','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',3,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000009 (\\u20b9680.50)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/9\",\"type\":\"order\"}',NULL,'2026-09-01 11:39:16','2026-09-01 11:39:16'),
('98aaee69-a318-4bf1-9004-79373df9b253','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',1,'{\"title\":\"COD collected\",\"message\":\"\\u20b97,470.00 collected for ORD-2026-000001\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17'),
('9d4dc28c-255f-4e7e-8a38-bd80e1b5dd49','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',3,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000006 (\\u20b91,584.00)\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/6\",\"type\":\"order\"}',NULL,'2026-08-26 12:24:36','2026-08-26 12:24:36'),
('a23ddc29-7887-44af-bd29-b1da621a37d3','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',3,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000007 (\\u20b9689.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/7\",\"type\":\"order\"}',NULL,'2026-09-01 04:13:50','2026-09-01 04:13:50'),
('a3ad053f-e8e0-4a8c-9240-0480edd6e28e','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',1,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000007 (\\u20b9689.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/7\",\"type\":\"order\"}',NULL,'2026-09-01 04:13:50','2026-09-01 04:13:50'),
('a91fd6c7-3c2d-4148-a955-fd14eac9d757','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',1,'{\"title\":\"New order received\",\"message\":\"subrat Kumar sahoo placed order ORD-2026-000008 (\\u20b91,643.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/8\",\"type\":\"order\"}',NULL,'2026-09-01 05:27:21','2026-09-01 05:27:21'),
('a9a0f909-3f18-42c1-a3e3-b123a6c0fbf9','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',1,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000009 (\\u20b9680.50)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/9\",\"type\":\"order\"}',NULL,'2026-09-01 11:39:16','2026-09-01 11:39:16'),
('ae8e4aff-bf19-4017-bf04-f811984ec3a3','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Confirmed\",\"message\":\"ORD-2026-000001 is now Confirmed.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/11\",\"type\":\"order_status\"}',NULL,'2026-09-03 23:54:15','2026-09-03 23:54:15'),
('c6297a5b-6a40-433f-aaca-d2ba6ff898ff','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',3,'{\"title\":\"COD collected\",\"message\":\"\\u20b97,470.00 collected for ORD-2026-000001\",\"url\":\"#\",\"type\":\"cod\"}',NULL,'2026-09-04 00:05:17','2026-09-04 00:05:17'),
('d1fa951a-84e4-4027-b2ce-d5695949616d','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',7,'{\"title\":\"Order Returned\",\"message\":\"ORD-2026-001000 is now Returned.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/1\",\"type\":\"order_status\"}',NULL,'2026-08-26 01:00:55','2026-08-26 01:00:55'),
('e2db5ce7-1e3a-43d8-bd61-bb62dbe9cdcb','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Packed\",\"message\":\"ORD-2026-000001 is now Packed.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/11\",\"type\":\"order_status\"}',NULL,'2026-09-03 23:55:21','2026-09-03 23:55:21'),
('eda46058-ace4-4107-8607-fdce67125be7','App\\Notifications\\Customer\\OrderStatusUpdate','App\\Models\\User',1,'{\"title\":\"Order Confirmed\",\"message\":\"ORD-2026-000010 is now Confirmed.\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/account\\/orders\\/10\",\"type\":\"order_status\"}',NULL,'2026-09-01 12:15:12','2026-09-01 12:15:12'),
('f4307bb8-86b4-4c5b-9109-813a4933ad76','App\\Notifications\\Admin\\CodCollectedAlert','App\\Models\\User',1,'{\"title\":\"COD collected\",\"message\":\"\\u20b9797.00 collected for ORD-2026-001001\",\"url\":\"#\",\"type\":\"cod\"}','2026-08-26 12:30:18','2026-08-25 06:43:28','2026-08-26 12:30:18'),
('f6be3115-3c0b-4e42-a688-d9fea7129b56','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',3,'{\"title\":\"New order received\",\"message\":\"subrat Kumar sahoo placed order ORD-2026-000008 (\\u20b91,643.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/8\",\"type\":\"order\"}',NULL,'2026-09-01 05:27:21','2026-09-01 05:27:21'),
('fc5d2b2b-d72d-4640-a833-c3af76df0f7a','App\\Notifications\\Admin\\NewOrderAlert','App\\Models\\User',3,'{\"title\":\"New order received\",\"message\":\"Subrat Admin placed order ORD-2026-000001 (\\u20b97,470.00)\",\"url\":\"http:\\/\\/127.0.0.1:8001\\/admin\\/orders\\/11\",\"type\":\"order\"}',NULL,'2026-09-03 21:43:56','2026-09-03 21:43:56');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notify_me`
--

DROP TABLE IF EXISTS `notify_me`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notify_me` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_slug` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'notify_me',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notify_me_email_product_slug_type_unique` (`email`,`product_slug`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notify_me`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notify_me` WRITE;
/*!40000 ALTER TABLE `notify_me` DISABLE KEYS */;
INSERT INTO `notify_me` VALUES
(4,'subratsahoo199806@gmail.com',NULL,'','newsletter','2026-09-02 12:17:24','2026-09-02 12:17:24'),
(5,'sks.sanju9@gmail.com',NULL,'','newsletter','2026-09-02 12:17:28','2026-09-02 12:17:28'),
(6,'test-user@example.com','Superfood Combo Pack','superfood-combo','notify_me','2026-09-03 23:27:52','2026-09-03 23:27:52');
/*!40000 ALTER TABLE `notify_me` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_name` varchar(64) DEFAULT NULL,
  `sku` varchar(64) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `line_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_product_variant_id_foreign` (`product_variant_id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES
(25,11,147,151,'Multitype Ghee Gift Pack','Default','AB-GHEE-MUL-1-DEFAULT','products/147/photo-1.jpg',3,2490.00,0.00,7470.00,'2026-09-03 21:43:56','2026-09-03 21:43:56');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `order_status_histories`
--

DROP TABLE IF EXISTS `order_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_status_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `from_status` varchar(32) DEFAULT NULL,
  `to_status` varchar(32) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_status_histories_changed_by_foreign` (`changed_by`),
  KEY `order_status_histories_order_id_created_at_index` (`order_id`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_histories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `order_status_histories` WRITE;
/*!40000 ALTER TABLE `order_status_histories` DISABLE KEYS */;
INSERT INTO `order_status_histories` VALUES
(25,11,NULL,'pending','Order placed',NULL,'2026-09-04 03:13:56'),
(26,11,'pending','confirmed',NULL,1,'2026-09-04 05:24:15'),
(27,11,'confirmed','preparing',NULL,1,'2026-09-04 05:24:47'),
(28,11,'preparing','packed',NULL,1,'2026-09-04 05:25:21'),
(29,11,'packed','assigned','Assigned to Manoj Behera',1,'2026-09-04 05:25:53'),
(30,11,'assigned','out_for_delivery','Picked up by Manoj Behera',NULL,'2026-09-04 05:34:45'),
(31,11,'assigned','out_for_delivery','Picked up by Manoj Behera',NULL,'2026-09-04 05:35:17'),
(32,11,'out_for_delivery','delivered','Delivered by Manoj Behera',NULL,'2026-09-04 05:35:17');
/*!40000 ALTER TABLE `order_status_histories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(32) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` enum('pending','confirmed','preparing','packed','assigned','out_for_delivery','delivered','cancelled','returned','failed_delivery') NOT NULL DEFAULT 'pending',
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `product_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `ship_name` varchar(255) NOT NULL,
  `ship_phone` varchar(20) NOT NULL,
  `ship_house_no` varchar(255) NOT NULL,
  `ship_street` varchar(255) DEFAULT NULL,
  `ship_area` varchar(255) DEFAULT NULL,
  `ship_landmark` varchar(255) DEFAULT NULL,
  `ship_city` varchar(120) NOT NULL,
  `ship_state` varchar(120) NOT NULL,
  `ship_pincode` varchar(10) NOT NULL,
  `address_id` bigint(20) unsigned DEFAULT NULL,
  `payment_method` enum('cod') NOT NULL DEFAULT 'cod',
  `placed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_cancelled_by_foreign` (`cancelled_by`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  KEY `orders_address_id_foreign` (`address_id`),
  KEY `orders_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `orders_status_created_at_index` (`status`,`created_at`),
  KEY `orders_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES
(11,'ORD-2026-000001',1,'delivered',NULL,NULL,7470.00,0.00,0.00,0.00,7470.00,NULL,'subrat Kumar sahoo','9348225868','dsfsd','at-budhimari, Po -sartha, Dist -balasore','sds','sfsdf','Baleswar','Odisha','756027',6,'cod','2026-09-03 21:43:56','2026-09-03 23:54:15','2026-09-04 00:05:17',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36',NULL,'2026-09-03 21:43:56','2026-09-04 00:05:17');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short` varchar(255) DEFAULT NULL,
  `hero` varchar(255) DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'file-text',
  `lede` text DEFAULT NULL,
  `sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sections`)),
  `faqs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`faqs`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES
(1,'privacy-policy','Privacy Policy','How we collect, use and protect your personal information.','Your data, handled with care','shield-check','At AB Organic Farm, we treat your trust as seriously as we treat our soil. This policy explains exactly what we collect, why, and the measures we take to keep your personal information safe, private and protected.','[{\"heading\":\"Information we collect\",\"icon\":\"database\",\"body\":\"We collect only what is needed to serve you: your name, delivery address, phone number, email, order history and payment details. When you create an account or place an order we also store preferences so we can personalise your farm favourites.\"},{\"heading\":\"How we use your information\",\"icon\":\"sparkles\",\"body\":\"Your details power your orders \\u2014 processing payments, arranging delivery, sending updates and resolving queries. With your permission we also send seasonal offers and harvest updates. We never sell your data.\"},{\"heading\":\"How we protect it\",\"icon\":\"lock\",\"body\":\"All information travels over secure, encrypted connections and is stored on protected servers. Payment data is handled by trusted gateways \\u2014 we never see or store your full card number.\"},{\"heading\":\"Cookies and analytics\",\"icon\":\"cookie\",\"body\":\"We use cookies to keep your cart intact, remember preferences and improve the store. You can disable cookies in your browser at any time.\"},{\"heading\":\"Your rights\",\"icon\":\"user-check\",\"body\":\"You may request a copy of the data we hold, ask us to correct or delete it, withdraw marketing consent, or export your data. Email our support team and we respond within five working days.\"}]','[{\"q\":\"Do you share my information?\",\"a\":\"Only with delivery partners who need your address and payment processors who handle transactions. We never sell or rent your data.\"},{\"q\":\"How long do you keep my data?\",\"a\":\"Order records are kept as long as required for tax and warranty purposes (usually six years). Marketing data is kept until you withdraw consent.\"}]',1,1,'2026-09-03 22:53:51','2026-09-03 22:53:51'),
(2,'shipping-policy','Shipping Policy','Delivery areas, timelines, charges and what to expect.','From our farm to your doorstep','truck','We know fresh food should arrive fast and arrive right. This page spells out where we deliver, how long it takes, what it costs — and what makes our packaging kinder to both your food and the planet.','[{\"heading\":\"Where we deliver\",\"icon\":\"map-pin\",\"body\":\"We deliver across most of the region including urban centres and surrounding districts. Enter your pincode at checkout and our system instantly confirms whether we service your area.\"},{\"heading\":\"Order processing\",\"icon\":\"clock\",\"body\":\"Orders placed before 4pm on a working day are picked, packed and dispatched the same day. Later or weekend orders leave our facility the next working morning.\"},{\"heading\":\"Delivery timelines\",\"icon\":\"timer\",\"body\":\"Standard delivery takes 2\\u20134 working days; metro areas often arrive next-day. Express delivery is available at checkout where services permit (24\\u201348 hours).\"},{\"heading\":\"Charges & free delivery\",\"icon\":\"package-check\",\"body\":\"Delivery is FREE above a cart value of 499. Below that a small, flat fee is shown before you pay \\u2014 no surprise charges at the door.\"},{\"heading\":\"Tracking your order\",\"icon\":\"scan-line\",\"body\":\"As soon as your order ships you receive tracking by SMS and email. Follow it any time from My Orders in your account.\"}]','[{\"q\":\"Can I track my delivery live?\",\"a\":\"Yes \\u2014 you get a tracking link by SMS\\/email once dispatched, and can monitor progress in My Orders until it reaches you.\"},{\"q\":\"What if I live outside your zone?\",\"a\":\"Your pincode is checked at checkout. If we do not yet serve you, we add your area to our roadmap \\u2014 new localities launch regularly.\"}]',1,2,'2026-09-03 22:53:51','2026-09-03 22:53:51'),
(3,'refund-policy','Refund & Return Policy','Our promise if something is not perfect.','Not happy? We make it right','rotate-ccw','Fresh, organic food should arrive perfect — and when it does not, we do not argue. Our no-fuss refund and return policy puts your peace of mind first.','[{\"heading\":\"When you can return\",\"icon\":\"package-x\",\"body\":\"Request a return or refund within 48 hours of delivery if an item arrives damaged, spoiled, incorrect or does not match its description. Photographic evidence speeds up resolution.\"},{\"heading\":\"How refunds work\",\"icon\":\"refresh-ccw\",\"body\":\"Approved refunds go to your original payment method or store credit, whichever you prefer. Most are processed within 3\\u20135 working days after approval.\"},{\"heading\":\"Perishable goods\",\"icon\":\"leaf\",\"body\":\"Because our ghee, oils and flours are fresh, opened or consumed products cannot be returned for hygiene reasons unless faulty at delivery. Quality checks guarantee freshness before dispatch.\"},{\"heading\":\"Non-returnable items\",\"icon\":\"shield-x\",\"body\":\"Sealed and used personal-care and consumable items, discounted bulk packs and any opened product cannot be returned, except when they arrive damaged or defective.\"},{\"heading\":\"How to request a return\",\"icon\":\"headset\",\"body\":\"Contact our support team from the Contact page or your order details with your order ID and a short note. We guide you through the rest \\u2014 usually a replacement or refund within one working day.\"}]','[{\"q\":\"How long does a refund take?\",\"a\":\"Once approved, refunds settle within 3\\u20135 working days depending on your payment provider. You are emailed the moment it is processed.\"},{\"q\":\"I received a damaged item. What now?\",\"a\":\"Photograph it within 48 hours, raise a ticket from your order, and we will replace it or refund it \\u2014 usually the same day.\"}]',1,3,'2026-09-03 22:53:51','2026-09-03 22:53:51'),
(4,'terms-of-service','Terms of Service','The friendly rules that keep everything fair for you and for us.','Clear, fair terms for a better experience','file-check','These terms make sure we have a shared understanding — so shopping on AB Organic Farm is effortless and transparent. By using our store you agree to the terms below.','[{\"heading\":\"Using the store\",\"icon\":\"store\",\"body\":\"You agree to use the store lawfully, keep your account details accurate, and not misuse the service, our content or other customers\' data. Accounts are for personal use unless you have an approved wholesale arrangement.\"},{\"heading\":\"Orders & pricing\",\"icon\":\"badge-cent\",\"body\":\"All prices are in rupees and include prevailing taxes where stated. We may correct pricing errors before dispatch. An order is confirmed only after we send confirmation and accept payment.\"},{\"heading\":\"Product information\",\"icon\":\"info\",\"body\":\"We describe products and nutritional details in good faith. Because organic and seasonal produce varies naturally, minor differences in colour, size or texture are expected and not grounds for dispute.\"},{\"heading\":\"Intellectual property\",\"icon\":\"copyright\",\"body\":\"All content on this store \\u2014 text, images, logos and branding \\u2014 belongs to AB Organic Farm and may not be reused for commercial purposes without written permission.\"},{\"heading\":\"Limitation of liability\",\"icon\":\"scale\",\"body\":\"To the extent permitted by law our liability for any claim is limited to the value of the goods in the affected order. Nothing here limits your statutory consumer rights.\"}]','[{\"q\":\"Can I order in bulk for events?\",\"a\":\"Absolutely. Contact our wholesale team for pricing on ghee, oils, flour and pantry staples for events, restaurants and stores.\"},{\"q\":\"What law governs these terms?\",\"a\":\"These terms are governed by the laws of India, subject to the exclusive jurisdiction of the local courts.\"}]',1,4,'2026-09-03 22:53:51','2026-09-03 22:53:51'),
(5,'cancellation-policy','Cancellation Policy','Change your mind? Here is how to cancel.','Plans change. We understand','x-circle','Pressed the button too soon, or your plans changed? We make cancelling an order simple — and free of charge whenever we can.','[{\"heading\":\"Cancelling before dispatch\",\"icon\":\"package-open\",\"body\":\"Cancel an order free of charge any time before it is dispatched. Simply go to My Orders and tap Cancel, or message support with your order ID.\"},{\"heading\":\"Cancellation after dispatch\",\"icon\":\"truck\",\"body\":\"Once an order has left our facility we cannot stop it in transit, but you can refuse delivery or request a return within 48 hours \\u2014 we issue a full refund once the item returns to us.\"},{\"heading\":\"Pre-order & custom items\",\"icon\":\"calendar-x\",\"body\":\"Pre-orders and custom or wholesale orders already in production cannot be cancelled. These are always flagged clearly at checkout before you commit.\"},{\"heading\":\"Refunds on cancellation\",\"icon\":\"banknote\",\"body\":\"Refunds for pre-dispatch cancellations are processed to your original payment method within 3\\u20135 working days. Store credit is issued instantly if you prefer.\"}]','[{\"q\":\"Can I cancel and reorder immediately?\",\"a\":\"Yes. Cancel in My Orders, then place a new order right away. Cancellation before dispatch is instant.\"},{\"q\":\"Is there a fee to cancel?\",\"a\":\"No cancellation fee applies before dispatch. After dispatch a return may incur a standard reverse-logistics fee unless the item was faulty.\"}]',1,5,'2026-09-03 22:53:51','2026-09-03 22:53:51'),
(6,'returns-exchanges','Returns & Exchanges','Swap or send back an item, hassle-free.','Swaps and returns made simple','refresh-cw','Sometimes a swap is better than a refund. Our returns and exchange programme is designed to be painless, fast and genuinely useful.','[{\"heading\":\"Exchange window\",\"icon\":\"calendar-clock\",\"body\":\"You have 7 days from delivery to request an exchange for a different variant or size of the same product, as long as the item is unopened and in its original packaging.\"},{\"heading\":\"Eligible items\",\"icon\":\"package\",\"body\":\"Sealed pantry staples (ghee, oils, flour, dry goods) can be exchanged for any other variant of equal or greater value. Opened or perishable items cannot be exchanged for hygiene reasons.\"},{\"heading\":\"How exchanges work\",\"icon\":\"arrow-right-left\",\"body\":\"Raise an exchange request from your order. We arrange reverse pickup if eligible, you hand over the sealed item, and we dispatch the replacement once it reaches us \\u2014 usually within 2\\u20133 days.\"},{\"heading\":\"Returning an item\",\"icon\":\"package-search\",\"body\":\"Follow the same path as exchanges for returns. Where a return fee applies it is shown upfront. Damaged or incorrect items are swapped or refunded at no cost.\"}]','[{\"q\":\"Do I pay for reverse pickup?\",\"a\":\"Exchanges and returns for damaged, expired or incorrect items are free. Voluntary returns may attract a small reverse-logistics fee, shown before you confirm.\"},{\"q\":\"Can I exchange for a different product?\",\"a\":\"Within the eligible window you can exchange for any product of equal value; a higher-value product costs the difference.\"}]',1,6,'2026-09-03 22:53:51','2026-09-03 22:53:51');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `method` enum('cod') NOT NULL DEFAULT 'cod',
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','collected','refunded','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_order_id_unique` (`order_id`),
  KEY `payments_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES
(11,11,'cod',7470.00,'collected','2026-09-03 21:43:56','2026-09-04 00:05:17');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  KEY `permissions_group_index` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `thumb_path` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_sort_order_index` (`product_id`,`sort_order`)
) ENGINE=MyISAM AUTO_INCREMENT=470 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES
(469,173,'products/173/photo-4.jpg','products/173/photo-4.jpg','Oil + Atta Combo — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(468,173,'products/173/photo-3.jpg','products/173/photo-3.jpg','Oil + Atta Combo — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(467,173,'products/173/photo-2.jpg','products/173/photo-2.jpg','Oil + Atta Combo — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(466,173,'products/173/photo-1.jpg','products/173/photo-1.jpg','Oil + Atta Combo — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(465,172,'products/172/photo-4.jpg','products/172/photo-4.jpg','Breakfast Essentials Combo — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(464,172,'products/172/photo-3.jpg','products/172/photo-3.jpg','Breakfast Essentials Combo — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(463,172,'products/172/photo-2.jpg','products/172/photo-2.jpg','Breakfast Essentials Combo — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(462,172,'products/172/photo-1.jpg','products/172/photo-1.jpg','Breakfast Essentials Combo — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(461,171,'products/171/photo-4.jpg','products/171/photo-4.jpg','Immunity Boost Combo — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(460,171,'products/171/photo-3.jpg','products/171/photo-3.jpg','Immunity Boost Combo — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(459,171,'products/171/photo-2.jpg','products/171/photo-2.jpg','Immunity Boost Combo — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(458,171,'products/171/photo-1.jpg','products/171/photo-1.jpg','Immunity Boost Combo — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(457,170,'products/170/photo-4.jpg','products/170/photo-4.jpg','Everyday Kitchen Combo — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(455,170,'products/170/photo-2.jpg','products/170/photo-2.jpg','Everyday Kitchen Combo — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(456,170,'products/170/photo-3.jpg','products/170/photo-3.jpg','Everyday Kitchen Combo — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(454,170,'products/170/photo-1.jpg','products/170/photo-1.jpg','Everyday Kitchen Combo — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(453,169,'products/169/photo-4.jpg','products/169/photo-4.jpg','Jowar Flour — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(452,169,'products/169/photo-3.jpg','products/169/photo-3.jpg','Jowar Flour — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(451,169,'products/169/photo-2.jpg','products/169/photo-2.jpg','Jowar Flour — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(450,169,'products/169/photo-1.jpg','products/169/photo-1.jpg','Jowar Flour — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(449,168,'products/168/photo-4.jpg','products/168/photo-4.jpg','Ragi Flour — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(448,168,'products/168/photo-3.jpg','products/168/photo-3.jpg','Ragi Flour — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(447,168,'products/168/photo-2.jpg','products/168/photo-2.jpg','Ragi Flour — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(446,168,'products/168/photo-1.jpg','products/168/photo-1.jpg','Ragi Flour — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(445,167,'products/167/photo-4.jpg','products/167/photo-4.jpg','Bajra Flour — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(444,167,'products/167/photo-3.jpg','products/167/photo-3.jpg','Bajra Flour — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(443,167,'products/167/photo-2.jpg','products/167/photo-2.jpg','Bajra Flour — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(442,167,'products/167/photo-1.jpg','products/167/photo-1.jpg','Bajra Flour — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(441,166,'products/166/photo-4.jpg','products/166/photo-4.jpg','Chana Brown Flour (Besan) — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(440,166,'products/166/photo-3.jpg','products/166/photo-3.jpg','Chana Brown Flour (Besan) — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(439,166,'products/166/photo-2.jpg','products/166/photo-2.jpg','Chana Brown Flour (Besan) — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(438,166,'products/166/photo-1.jpg','products/166/photo-1.jpg','Chana Brown Flour (Besan) — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(437,165,'products/165/photo-4.jpg','products/165/photo-4.jpg','Wood-Pressed Mustard Oil — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(436,165,'products/165/photo-3.jpg','products/165/photo-3.jpg','Wood-Pressed Mustard Oil — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(435,165,'products/165/photo-2.jpg','products/165/photo-2.jpg','Wood-Pressed Mustard Oil — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(434,165,'products/165/photo-1.jpg','products/165/photo-1.jpg','Wood-Pressed Mustard Oil — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(433,164,'products/164/photo-4.jpg','products/164/photo-4.jpg','Extra Virgin Groundnut Oil — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(432,164,'products/164/photo-3.jpg','products/164/photo-3.jpg','Extra Virgin Groundnut Oil — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(431,164,'products/164/photo-2.jpg','products/164/photo-2.jpg','Extra Virgin Groundnut Oil — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(430,164,'products/164/photo-1.jpg','products/164/photo-1.jpg','Extra Virgin Groundnut Oil — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(429,163,'products/163/photo-4.jpg','products/163/photo-4.jpg','Cold-Pressed Sesame Oil — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(428,163,'products/163/photo-3.jpg','products/163/photo-3.jpg','Cold-Pressed Sesame Oil — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(427,163,'products/163/photo-2.jpg','products/163/photo-2.jpg','Cold-Pressed Sesame Oil — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(426,163,'products/163/photo-1.jpg','products/163/photo-1.jpg','Cold-Pressed Sesame Oil — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(425,162,'products/162/photo-4.jpg','products/162/photo-4.jpg','Cold-Pressed Sunflower Oil — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(424,162,'products/162/photo-3.jpg','products/162/photo-3.jpg','Cold-Pressed Sunflower Oil — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(423,162,'products/162/photo-2.jpg','products/162/photo-2.jpg','Cold-Pressed Sunflower Oil — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(422,162,'products/162/photo-1.jpg','products/162/photo-1.jpg','Cold-Pressed Sunflower Oil — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(421,161,'products/161/photo-4.jpg','products/161/photo-4.jpg','Organic Desi Ghee Pack — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(420,161,'products/161/photo-3.jpg','products/161/photo-3.jpg','Organic Desi Ghee Pack — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(419,161,'products/161/photo-2.jpg','products/161/photo-2.jpg','Organic Desi Ghee Pack — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(418,161,'products/161/photo-1.jpg','products/161/photo-1.jpg','Organic Desi Ghee Pack — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(417,160,'products/160/photo-4.jpg','products/160/photo-4.jpg','Bilona Churned Desi Ghee — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(416,160,'products/160/photo-3.jpg','products/160/photo-3.jpg','Bilona Churned Desi Ghee — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(415,160,'products/160/photo-2.jpg','products/160/photo-2.jpg','Bilona Churned Desi Ghee — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(414,160,'products/160/photo-1.jpg','products/160/photo-1.jpg','Bilona Churned Desi Ghee — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(413,159,'products/159/photo-4.jpg','products/159/photo-4.jpg','Buffalo Milk Ghee — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(412,159,'products/159/photo-3.jpg','products/159/photo-3.jpg','Buffalo Milk Ghee — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(410,159,'products/159/photo-1.jpg','products/159/photo-1.jpg','Buffalo Milk Ghee — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(411,159,'products/159/photo-2.jpg','products/159/photo-2.jpg','Buffalo Milk Ghee — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(409,158,'products/158/photo-4.jpg','products/158/photo-4.jpg','A2 Gir Cow Ghee 200 ml — close-up detail',3,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(408,158,'products/158/photo-3.jpg','products/158/photo-3.jpg','A2 Gir Cow Ghee 200 ml — lifestyle shot',2,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(407,158,'products/158/photo-2.jpg','products/158/photo-2.jpg','A2 Gir Cow Ghee 200 ml — angled view',1,0,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(406,158,'products/158/photo-1.jpg','products/158/photo-1.jpg','A2 Gir Cow Ghee 200 ml — main photo',0,1,'2026-09-03 19:13:32','2026-09-03 19:13:32'),
(397,155,'products/155/photo-4.jpg','products/155/photo-4.jpg','Pure & Pure Oil Combo — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(393,154,'products/154/photo-4.jpg','products/154/photo-4.jpg','Daily Ghee + Atta Combo — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(381,150,'products/150/photo-4.jpg','products/150/photo-4.jpg','Virgin Coconut Oil — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(377,149,'products/149/photo-4.jpg','products/149/photo-4.jpg','Cold Pressed Groundnut Oil — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(373,148,'products/148/photo-4.jpg','products/148/photo-4.jpg','Kachhi Ghani Mustard Oil — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(369,147,'products/147/photo-4.jpg','products/147/photo-4.jpg','Ghee Gift Pack — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(361,145,'products/145/photo-4.jpg','products/145/photo-4.jpg','Pure Village Cow Ghee — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(402,157,'products/157/photo-1.jpg','products/157/photo-1.jpg','Superfood Combo — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(353,143,'products/143/photo-4.jpg','products/143/photo-4.jpg','A2 Gir Cow Desi Ghee — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(404,157,'products/157/photo-3.jpg','products/157/photo-3.jpg','Superfood Combo — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(403,157,'products/157/photo-2.jpg','products/157/photo-2.jpg','Superfood Combo — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(398,156,'products/156/photo-1.jpg','products/156/photo-1.jpg','Festive Ghee Trio — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(399,156,'products/156/photo-2.jpg','products/156/photo-2.jpg','Festive Ghee Trio — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(400,156,'products/156/photo-3.jpg','products/156/photo-3.jpg','Festive Ghee Trio — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(394,155,'products/155/photo-1.jpg','products/155/photo-1.jpg','Pure & Pure Oil Combo — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(395,155,'products/155/photo-2.jpg','products/155/photo-2.jpg','Pure & Pure Oil Combo — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(390,154,'products/154/photo-1.jpg','products/154/photo-1.jpg','Daily Ghee + Atta Combo — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(396,155,'products/155/photo-3.jpg','products/155/photo-3.jpg','Pure & Pure Oil Combo — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(391,154,'products/154/photo-2.jpg','products/154/photo-2.jpg','Daily Ghee + Atta Combo — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(386,152,'products/152/photo-1.jpg','products/152/photo-1.jpg','Multigrain Atta — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(382,151,'products/151/photo-1.jpg','products/151/photo-1.jpg','Stone Ground Whole Wheat Atta — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(387,152,'products/152/photo-2.jpg','products/152/photo-2.jpg','Multigrain Atta — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(378,150,'products/150/photo-1.jpg','products/150/photo-1.jpg','Virgin Coconut Oil — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(370,148,'products/148/photo-1.jpg','products/148/photo-1.jpg','Kachhi Ghani Mustard Oil — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(374,149,'products/149/photo-1.jpg','products/149/photo-1.jpg','Cold Pressed Groundnut Oil — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(366,147,'products/147/photo-1.jpg','products/147/photo-1.jpg','Ghee Gift Pack — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(362,146,'products/146/photo-1.jpg','products/146/photo-1.jpg','Desi Ghee 500g Pack — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(358,145,'products/145/photo-1.jpg','products/145/photo-1.jpg','Pure Village Cow Ghee — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(350,143,'products/143/photo-1.jpg','products/143/photo-1.jpg','A2 Gir Cow Desi Ghee — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(354,144,'products/144/photo-1.jpg','products/144/photo-1.jpg','A2 Gir Cow Ghee (1L) — main photo',0,1,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(405,157,'products/157/photo-4.jpg','products/157/photo-4.jpg','Superfood Combo — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(401,156,'products/156/photo-4.jpg','products/156/photo-4.jpg','Festive Ghee Trio — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(392,154,'products/154/photo-3.jpg','products/154/photo-3.jpg','Daily Ghee + Atta Combo — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(352,143,'products/143/photo-3.jpg','products/143/photo-3.jpg','A2 Gir Cow Desi Ghee — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(357,144,'products/144/photo-4.jpg','products/144/photo-4.jpg','A2 Gir Cow Ghee (1L) — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(356,144,'products/144/photo-3.jpg','products/144/photo-3.jpg','A2 Gir Cow Ghee (1L) — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(365,146,'products/146/photo-4.jpg','products/146/photo-4.jpg','Desi Ghee 500g Pack — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(360,145,'products/145/photo-3.jpg','products/145/photo-3.jpg','Pure Village Cow Ghee — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(364,146,'products/146/photo-3.jpg','products/146/photo-3.jpg','Desi Ghee 500g Pack — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(368,147,'products/147/photo-3.jpg','products/147/photo-3.jpg','Ghee Gift Pack — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(372,148,'products/148/photo-3.jpg','products/148/photo-3.jpg','Kachhi Ghani Mustard Oil — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(376,149,'products/149/photo-3.jpg','products/149/photo-3.jpg','Cold Pressed Groundnut Oil — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(380,150,'products/150/photo-3.jpg','products/150/photo-3.jpg','Virgin Coconut Oil — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(385,151,'products/151/photo-4.jpg','products/151/photo-4.jpg','Stone Ground Whole Wheat Atta — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(389,152,'products/152/photo-4.jpg','products/152/photo-4.jpg','Multigrain Atta — close-up detail',3,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(384,151,'products/151/photo-3.jpg','products/151/photo-3.jpg','Stone Ground Whole Wheat Atta — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(383,151,'products/151/photo-2.jpg','products/151/photo-2.jpg','Stone Ground Whole Wheat Atta — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(379,150,'products/150/photo-2.jpg','products/150/photo-2.jpg','Virgin Coconut Oil — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(375,149,'products/149/photo-2.jpg','products/149/photo-2.jpg','Cold Pressed Groundnut Oil — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(371,148,'products/148/photo-2.jpg','products/148/photo-2.jpg','Kachhi Ghani Mustard Oil — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(367,147,'products/147/photo-2.jpg','products/147/photo-2.jpg','Ghee Gift Pack — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(363,146,'products/146/photo-2.jpg','products/146/photo-2.jpg','Desi Ghee 500g Pack — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(359,145,'products/145/photo-2.jpg','products/145/photo-2.jpg','Pure Village Cow Ghee — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(355,144,'products/144/photo-2.jpg','products/144/photo-2.jpg','A2 Gir Cow Ghee (1L) — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(388,152,'products/152/photo-3.jpg','products/152/photo-3.jpg','Multigrain Atta — lifestyle shot',2,0,'2026-09-03 17:48:30','2026-09-03 17:48:30'),
(351,143,'products/143/photo-2.jpg','products/143/photo-2.jpg','A2 Gir Cow Desi Ghee — angled view',1,0,'2026-09-03 17:48:30','2026-09-03 17:48:30');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  `weight_grams` int(10) unsigned DEFAULT NULL,
  `unit_label` varchar(32) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_is_active_sort_order_index` (`product_id`,`is_active`,`sort_order`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES
(173,173,'AB-COM-OILATTA-DEFAULT','Default',0,'Combo',1480.00,1199.00,1040.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(172,172,'AB-COM-BREAKFAST-DEFAULT','Default',0,'Combo',980.00,799.00,680.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(171,171,'AB-COM-IMMUNITY-DEFAULT','Default',0,'Combo',1850.00,1499.00,1300.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(170,170,'AB-COM-KITCHEN-DEFAULT','Default',0,'Combo',1920.00,1599.00,1400.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(169,169,'AB-ATTA-JW-1-DEFAULT','Default',1000,'1 kg',220.00,179.00,132.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(168,168,'AB-ATTA-RAG-1-DEFAULT','Default',1000,'1 kg',320.00,269.00,192.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(167,167,'AB-ATTA-BAJ-1-DEFAULT','Default',1000,'1 kg',260.00,219.00,156.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(166,166,'AB-ATTA-CHN-1-DEFAULT','Default',1000,'1 kg',240.00,199.00,144.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(165,165,'AB-OIL-MUS-500-DEFAULT','Default',500,'500 ml',370.00,299.00,222.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(164,164,'AB-OIL-PEA-EV-DEFAULT','Default',1000,'1 litre',780.00,649.00,468.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(163,163,'AB-OIL-SES-1-DEFAULT','Default',500,'500 ml',740.00,599.00,444.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(162,162,'AB-OIL-SUN-1-DEFAULT','Default',1000,'1 litre',620.00,499.00,372.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(161,161,'AB-GHEE-PKT-400-DEFAULT','Default',400,'400 g',1180.00,999.00,708.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(160,160,'AB-GHEE-BLN-1-DEFAULT','Default',1000,'1 litre',1990.00,1699.00,1194.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(159,159,'AB-GHEE-BUF-500-DEFAULT','Default',500,'500 ml',1450.00,1199.00,870.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(158,158,'AB-GHEE-A2-200-DEFAULT','Default',200,'200 ml',500.00,399.00,300.00,1,1,0,'2026-09-03 18:25:08','2026-09-03 18:25:08'),
(157,153,'AB-OIL-SES-1TEST-D','Default',NULL,NULL,420.00,NULL,NULL,1,1,0,'2026-09-03 03:22:27','2026-09-03 03:22:27'),
(156,152,'AB-ATTA-MG-5-DEFAULT','Default',500,'5 kg',410.00,359.00,246.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(155,151,'AB-ATTA-WW-5-DEFAULT','Default',500,'5 kg',340.00,289.00,204.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(154,150,'AB-OIL-COC-500-DEFAULT','Default',1000,'500 ml',680.00,599.00,408.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(153,149,'AB-OIL-GND-1-DEFAULT','Default',1000,'1 litre',610.00,549.00,366.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(152,148,'AB-OIL-MUS-1-DEFAULT','Default',1000,'1 litre',540.00,479.00,324.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(151,147,'AB-GHEE-MUL-1-DEFAULT','Default',500,'Gift pack',2800.00,2490.00,1680.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(150,146,'AB-GHEE-PCK-500-DEFAULT','Default',500,'500 g',940.00,849.00,564.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(149,145,'AB-GHEE-VIL-500-DEFAULT','Default',1000,'500 ml',990.00,899.00,594.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(148,144,'AB-GHEE-A2-1000-DEFAULT','Default',1000,'1 litre',2490.00,2190.00,1494.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10'),
(147,143,'AB-GHEE-A2-500-DEFAULT','Default',1000,'500 ml',1290.00,1099.00,774.00,1,1,0,'2026-09-03 03:13:10','2026-09-03 03:13:10');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `usage_instructions` text DEFAULT NULL,
  `storage_instructions` text DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `farmer_source` varchar(255) DEFAULT NULL,
  `certification` varchar(255) DEFAULT NULL,
  `is_organic` tinyint(1) NOT NULL DEFAULT 0,
  `badge_label` varchar(64) DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `regular_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `weight_grams` int(10) unsigned DEFAULT NULL,
  `unit_label` varchar(32) DEFAULT NULL,
  `promo_note` varchar(190) DEFAULT NULL,
  `status` enum('active','draft','out_of_stock') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `review_count` int(10) unsigned NOT NULL DEFAULT 0,
  `sold_count` int(10) unsigned NOT NULL DEFAULT 0,
  `view_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_uuid_unique` (`uuid`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_category_id_status_published_at_index` (`category_id`,`status`,`published_at`),
  KEY `products_status_is_featured_index` (`status`,`is_featured`),
  KEY `products_sold_count_index` (`sold_count`),
  FULLTEXT KEY `products_name_short_description_fulltext` (`name`,`short_description`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(173,'7defc451-c9ce-4fb0-bb49-8279fd2f729a',22,1,'Oil + Atta Combo Pack','oil-atta-combo-pack','AB-COM-OILATTA','Groundnut oil with stone-ground wheat atta — the classic Indian kitchen pair.','Oil + Atta Combo Pack from AB Organic Farm: Extra Virgin Groundnut Oil with Stone-Ground Whole Wheat Atta. The dependable daily duo.','Groundnut oil + stone-ground whole wheat atta.','• Best-selling daily pair<br>• Farm-fresh staples<br>• One-click kitchen restock',NULL,NULL,NULL,NULL,NULL,1,'',1040.00,1480.00,1199.00,0,'Combo',NULL,'active',1,0,1,0.00,0,0,8,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 22:25:37',NULL),
(172,'3def2b1f-1582-47c8-8a4e-fffa5b93cacf',22,1,'Breakfast Essentials Combo','breakfast-essentials-combo','AB-COM-BREAKFAST','Stone-ground atta + bajra + jowar flour trio for wholesome mornings.','Breakfast Essentials Combo from AB Organic Farm — a trio of stone-ground flours (Whole-wheat atta, Bajra, Jowar) for varied, wholesome breakfasts.','Whole-wheat atta + bajra flour + jowar flour.','• 3 flours, varied nutrition<br>• Stone-ground freshness<br>• Ideal for rotis & bhakris',NULL,NULL,NULL,NULL,NULL,1,'',680.00,980.00,799.00,0,'Combo',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(171,'8972b9fb-9f03-4fc1-91ed-8b65f5e1c03f',22,1,'Immunity Boost Combo','immunity-boost-combo','AB-COM-IMMUNITY','Sesame oil + ragi flour + dry fruits — your natural immunity & energy kit.','Immunity Boost Combo from AB Organic Farm: Sesame (Til) Oil with Ragi Flour and a small dry-fruit mix — nutrient-dense everyday allies.','Sesame oil + ragi flour + dry-fruit mix.','• Sesame for vitamin E<br>• Ragi for calcium & iron<br>• Natural energy, no tablets',NULL,NULL,NULL,NULL,NULL,1,'',1300.00,1850.00,1499.00,0,'Combo',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(170,'a0ab9945-4349-4fcf-8c26-04d89152b21d',22,1,'Everyday Kitchen Combo','everyday-kitchen-combo','AB-COM-KITCHEN','A2 ghee + kachhi ghani mustard oil — the farm-to-table daily cooking essentials.','Everyday Kitchen Combo from AB Organic Farm: A2 Gir Cow Ghee (500 ml) with Kachhi Ghani Mustard Oil (1 litre) — both staples your kitchen runs on.','A2 Gir Cow Ghee + Kachhi Ghani Mustard Oil.','• Complete daily cooking kit<br>• Farm-fresh, small-batch<br>• Save more than buying separately',NULL,NULL,NULL,NULL,NULL,1,'Best Deal',1400.00,1920.00,1599.00,0,'Combo',NULL,'active',1,0,1,0.00,0,0,1,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 13:44:35',NULL),
(169,'d513402f-1423-4c8a-b54a-2f88cfe48813',21,1,'Jowar (Sorghum) Flour','jowar-sorghum-flour','AB-ATTA-JW-1','Stone-ground jowar flour, light and gluten-free for everyday rotis.','Stone-Ground Jowar (Sorghum) Flour from AB Organic Farm. A light, grain-forward gluten-free flour — the gentle base for soft jowar bhakris.','100% organic sorghum (jowar) flour.','• Gluten-free & light<br>• Stone-ground<br>• Low gluten, high fibre',NULL,NULL,NULL,NULL,NULL,1,'',132.00,220.00,179.00,1000,'1 kg',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(168,'5b8aa497-bf0d-4e28-8c55-f79389c4b043',21,1,'Ragi (Finger Millet) Flour','ragi-finger-millet-flour','AB-ATTA-RAG-1','Stone-ground ragi flour, rich in calcium and a superfood staple.','Stone-Ground Ragi (Finger Millet) Flour from AB Organic Farm. Packed with calcium and iron, stone-ground for a naturally sweet, earthy flavour.','100% organic finger millet (ragi) flour.','• Rich in calcium & iron<br>• Gluten-free<br>• Naturally sweet (ragi halwa, dosa)',NULL,NULL,NULL,NULL,NULL,1,'Superfood',192.00,320.00,269.00,1000,'1 kg',NULL,'active',1,0,1,0.00,0,0,2,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 13:44:35',NULL),
(167,'5d608611-c469-46e2-9006-9ca6605d1d68',21,1,'Bajra (Pearl Millet) Flour','bajra-pearl-millet-flour','AB-ATTA-BAJ-1','Stone-ground bajra flour for wholesome, nutrient-rich flatbreads.','Stone-Ground Bajra (Pearl Millet) Flour from AB Organic Farm. Naturally gluten-free, high in fibre and iron — perfect for bajra rotis.','100% organic pearl millet (bajra) flour.','• Gluten-free & high iron<br>• Stone-ground<br>• Traditional rustic taste',NULL,NULL,NULL,NULL,NULL,1,'',156.00,260.00,219.00,1000,'1 kg',NULL,'active',1,0,1,0.00,0,0,1,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 23:08:26',NULL),
(166,'9ac3c8d2-10bc-48f2-ae63-2de0962102e6',21,1,'Chana Brown Flour (Besan)','chana-brown-flour-besan','AB-ATTA-CHN-1','Stone-ground chana flour (besan) from split chickpeas — fresh for cooking & snacks.','Stone-Ground Chana Flour (Besan) from AB Organic Farm, milled from premium split chickpeas. Ideal for besan chilla, pakoras and sweets.','100% stone-ground chana dal (Bengal gram) flour.','• Stone-ground, fresh<br>• High protein<br>• Great for gluten-free recipes',NULL,NULL,NULL,NULL,NULL,1,'',144.00,240.00,199.00,1000,'1 kg',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(165,'0b072c1f-d461-4d4a-918e-b6305773d0e3',20,1,'Organic Wood-Pressed Mustard Oil','organic-wood-pressed-mustard-oil','AB-OIL-MUS-500','Wood-pressed mustard oil, pungent and pure — traditional Indian cooking oil.','Organic Wood-Pressed Mustard Oil from AB Organic Farm — cold-pressed via Kachhi Ghani for that authentic pungent kick in every dish.','100% organic black mustard seeds, wood-pressed.','• Authentic pungent flavour\\n• Kachhi ghani wood pressing\\n• Naturally preserved',NULL,NULL,NULL,NULL,NULL,1,'',222.00,370.00,299.00,500,'500 ml',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(164,'f3f57cfe-89e6-42c3-b192-77a3a8c7f5af',20,1,'Extra Virgin Cold-Pressed Groundnut Oil','extra-virgin-cold-pressed-groundnut-oil','AB-OIL-PEA-EV','First-press cold-pressed peanut oil with a naturally round, peanutty taste.','Extra Virgin Cold-Pressed Groundnut Oil — first-press from select peanuts. Full peanutty aroma, wood-pressed and unrefined.','100% first-press groundnut (peanut) oil.','• First-press, unrefined\\n• Full peanut aroma\\n• Ideal for deep frying & tempering',NULL,NULL,NULL,NULL,NULL,1,'',468.00,780.00,649.00,1000,'1 litre',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(163,'8c17196f-1d2c-4088-96b2-bcdc8555771c',20,1,'Cold-Pressed Sesame (Til) Oil','cold-pressed-sesame-til-oil','AB-OIL-SES-1','Wood-pressed sesame til oil, aromatic and rich — great for cooking and Ayurveda.','Cold-Pressed Sesame (Til) Oil from AB Organic Farm, pressed from hulled sesame seeds for a nutty, golden oil prized in Ayurveda and everyday cooking.','100% cold-pressed sesame oil.','• Golden, nutty, aromatic\\n• Rich in vitamin E & lignans\\n• For cooking, massage & Ayurveda',NULL,NULL,NULL,NULL,NULL,1,'',444.00,740.00,599.00,500,'500 ml',NULL,'active',1,0,1,0.00,0,0,1,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 13:49:07',NULL),
(162,'e2e765b6-2759-416d-bd4f-60f95a80124c',20,1,'Cold-Pressed Sunflower Oil','cold-pressed-sunflower-oil','AB-OIL-SUN-1','Light cold-pressed sunflower oil, wood-pressed for a clean, nutty taste.','Cold-Pressed Sunflower Oil from AB Organic Farm, made by gentle wood-pressing sun-dried sunflower seeds. Light in flavour with a high smoke point.','100% cold-pressed sunflower seed oil. No solvents, no refining.','• Wood-pressed, cold extraction\\n• Light flavour, high smoke point\\n• No chemicals or hexane',NULL,NULL,NULL,NULL,NULL,1,'',372.00,620.00,499.00,1000,'1 litre',NULL,'active',1,0,1,0.00,0,0,4,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 13:45:06',NULL),
(161,'f5d72e65-df0b-48ed-94ed-60d85bc4c5f9',18,1,'Organic Desi Ghee (Packed 400 g)','organic-desi-ghee-packed-400-g','AB-GHEE-PKT-400','Convenient 400 g pack of organic desi ghee, ideal for daily cooking and storage.','Organic Desi Ghee in a handy 400 g pack from AB Organic Farm. Same farm-fresh purity as our jars, in a compact pack that stores easily.','100% organic desi cow milk ghee. No additives.','• Compact everyday pack\\n• Farm-fresh small batches\\n• High smoke point for cooking',NULL,NULL,NULL,NULL,NULL,1,'Best Seller',708.00,1180.00,999.00,400,'400 g',NULL,'active',1,0,1,0.00,0,0,1,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 22:20:37',NULL),
(160,'3e05af19-9776-4452-bd42-6dc3887476ca',17,1,'Bilona Churned Desi Ghee','bilona-churned-desi-ghee','AB-GHEE-BLN-1','Hand-churned bilona ghee from cultured A2 curd — the most traditional, aromatic ghee.','Bilona Churned Desi Ghee by the authentic bilona process: curd churned with a wooden bilona, slow-heated. The most traditional and aromatic ghee AB Organic makes.','100% pure A2 desi cow curd-churned ghee.','• Hand-churned traditional bilona method\\n• Fresh kadhi aroma\\n• Golden, liquid amber goodness',NULL,NULL,NULL,NULL,NULL,1,'Premium',1194.00,1990.00,1699.00,1000,'1 litre',NULL,'active',1,0,1,0.00,0,0,13,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 22:09:27',NULL),
(159,'8f42fbf2-063a-42a3-bba7-b0eae88cd3d8',17,1,'Buffalo Milk Ghee','buffalo-milk-ghee','AB-GHEE-BUF-500','Rich, thick buffalo ghee with a traditional aroma, slow-churned from grass-fed buffalo milk.','Buffalo Milk Ghee from AB Organic Farm, slow-churned from the milk of grass-fed Indian buffalo. Denser grain and deeper flavour prized in Indian kitchens.','100% pure buffalo milk organic ghee. Single ingredient, authentic slow-churned.','• Thick grain & traditional aroma\\n• Slow-churned, no palm oil\\n• Naturally rich and long-lasting',NULL,NULL,NULL,NULL,NULL,1,'',870.00,1450.00,1199.00,500,'500 ml',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(158,'5488a538-aac4-4444-9dd3-817bd97e8ad2',17,1,'A2 Gir Cow Ghee (200 ml Small Jar)','a2-gir-cow-ghee-200-ml-small-jar','AB-GHEE-A2-200','Small-format A2 Gir cow ghee, bilona-churned in small batches for a fresh, nutty aroma.','A2 Gir Cow Ghee (200 ml) from AB Organic Farm, prepared by the traditional bilona method from grass-fed Gir cows. Perfect small jar for first-time buyers and gifting.','100% pure A2 Gir cow milk ghee. No preservatives, no additives, no palm oil.','• Impure? Not here: single-cow-herd A2 ghee\\n• Rich in vitamins A, D, E, K\\n• Fresh bilona churned in small batches',NULL,NULL,NULL,NULL,NULL,1,'',300.00,500.00,399.00,200,'200 ml',NULL,'active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 18:25:08','2026-09-03 18:25:08','2026-09-03 18:25:08',NULL),
(157,'5b0530d4-a7aa-11f1-931e-1aaca51249eb',22,1,'Superfood Combo Pack','superfood-combo','AB-COM-SUPERFOOD','Ghee + atta + superfoods.','A healthy starter pack: multigrain atta 5kg plus ghee and nutrient-dense superfoods.','Multigrain atta + desi ghee + superfood seeds/grains','A balanced pantry built for good health',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic Â· NPOP',1,NULL,1890.00,2560.00,2299.00,NULL,'1 pack','Farm pantry starter','active',1,0,0,0.00,0,0,1,NULL,NULL,NULL,'2026-09-03 15:15:54','2026-09-03 15:15:54','2026-09-03 23:41:46',NULL),
(156,'5b052e68-a7aa-11f1-931e-1aaca51249eb',22,1,'Festive Ghee Trio','festive-ghee-trio','AB-COM-GHEE-TRIO','Three A2 + village ghee jars.','Gift set of three desi ghees: A2 gir 500ml, village cow 500ml, multitype gift pack.','A2 gir cow ghee + pure village cow ghee + multitype gift pack','A festive assortment of the finest ghees',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic Â· NPOP',1,NULL,2360.00,3280.00,2899.00,NULL,'1 gift set','Festive ghee gift set','active',1,0,1,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 15:15:54','2026-09-03 15:15:54','2026-09-03 15:15:54',NULL),
(155,'5b052a4e-a7aa-11f1-931e-1aaca51249eb',22,1,'Pure & Pure Oil Combo','pure-oil-combo','AB-COM-OIL-PURE','Mustard + groundnut + coconut oils.','A trio of pure cold-pressed oils: kachhi ghani mustard 1L, groundnut 1L, virgin coconut 500ml.','Kachhi ghani mustard oil + cold-pressed groundnut oil + virgin coconut oil','The best cold-pressed oils for every dish',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic Â· NPOP',1,NULL,1380.00,1830.00,1649.00,NULL,'1 pack','Save â‚¹181 on all three oils','active',1,0,0,0.00,0,0,0,NULL,NULL,NULL,'2026-09-03 15:15:54','2026-09-03 15:15:54','2026-09-03 15:15:54',NULL),
(152,'ab547222-1cd3-409d-b6eb-db16e71fb53c',21,1,'Multigrain Atta','multigrain-atta','AB-ATTA-MG-5','Farm-fresh Multigrain Atta — cold-processed in small batches, no additives.','Multigrain Atta from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,246.00,410.00,359.00,NULL,'5 kg',NULL,'active',0,0,1,0.00,0,107,2186,NULL,NULL,NULL,'2026-07-29 03:13:10','2026-09-03 03:13:10','2026-09-03 13:46:19',NULL),
(151,'faed676e-635a-4841-a929-aaf54feb36bb',21,1,'Stone-Ground Whole Wheat Atta','stone-ground-whole-wheat-atta','AB-ATTA-WW-5','Farm-fresh Stone-Ground Whole Wheat Atta — cold-processed in small batches, no additives.','Stone-Ground Whole Wheat Atta from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,204.00,340.00,289.00,NULL,'5 kg',NULL,'active',1,1,0,0.00,0,251,600,NULL,NULL,NULL,'2026-08-27 03:13:10','2026-09-03 03:13:10','2026-09-03 22:50:54',NULL),
(150,'07bf89fd-1ff1-4ab7-8b84-a6e4eaac0b0f',20,1,'Virgin Coconut Oil','virgin-coconut-oil','AB-OIL-COC-500','Farm-fresh Virgin Coconut Oil — cold-processed in small batches, no additives.','Virgin Coconut Oil from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,408.00,680.00,599.00,NULL,'500 ml',NULL,'active',1,0,0,0.00,0,461,2400,NULL,NULL,NULL,'2026-08-07 03:13:10','2026-09-03 03:13:10','2026-09-03 03:13:10',NULL),
(149,'65852072-7b77-4f37-9e33-eb6bc7cc6ddc',20,1,'Cold-Pressed Groundnut (Peanut) Oil','cold-pressed-groundnut-peanut-oil','AB-OIL-GND-1','Farm-fresh Cold-Pressed Groundnut (Peanut) Oil — cold-processed in small batches, no additives.','Cold-Pressed Groundnut (Peanut) Oil from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,366.00,610.00,549.00,NULL,'1 litre',NULL,'active',1,0,1,0.00,0,602,3478,NULL,NULL,NULL,'2026-08-30 03:13:10','2026-09-03 03:13:10','2026-09-03 03:13:10',NULL),
(148,'e0da611f-8a0d-41b4-93a2-89ef921da861',20,1,'Kachhi Ghani Mustard Oil','kachhi-ghani-mustard-oil','AB-OIL-MUS-1','Farm-fresh Kachhi Ghani Mustard Oil — cold-processed in small batches, no additives.','Kachhi Ghani Mustard Oil from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,324.00,540.00,479.00,NULL,'1 litre',NULL,'active',0,1,0,0.00,0,515,2245,NULL,NULL,NULL,'2026-08-10 03:13:10','2026-09-03 03:13:10','2026-09-03 22:36:17',NULL),
(147,'85d9752f-2249-4ce1-83d0-ff54deaf8dd0',19,1,'Multitype Ghee Gift Pack','multitype-ghee-gift-pack','AB-GHEE-MUL-1','Farm-fresh Multitype Ghee Gift Pack — cold-processed in small batches, no additives.','Multitype Ghee Gift Pack from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,1680.00,2800.00,2490.00,NULL,'Gift pack',NULL,'active',0,0,1,0.00,0,643,3921,NULL,NULL,NULL,'2026-07-23 03:13:10','2026-09-03 03:13:10','2026-09-03 22:20:49',NULL),
(146,'4d60bc2e-c7b0-46e7-8a03-ed200087e532',18,1,'Desi Ghee - Packed','desi-ghee-packed','AB-GHEE-PCK-500','Farm-fresh Desi Ghee - Packed — cold-processed in small batches, no additives.','Desi Ghee - Packed from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,564.00,940.00,849.00,NULL,'500 g',NULL,'active',0,0,0,0.00,0,408,680,NULL,NULL,NULL,'2026-08-01 03:13:10','2026-09-03 03:13:10','2026-09-03 03:13:10',NULL),
(145,'7026e37d-e994-4148-b891-6b78e0d70a78',17,1,'Pure Village Cow Ghee (Jar)','pure-village-cow-ghee-jar','AB-GHEE-VIL-500','Farm-fresh Pure Village Cow Ghee (Jar) — cold-processed in small batches, no additives.','Pure Village Cow Ghee (Jar) from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,594.00,990.00,899.00,NULL,'500 ml',NULL,'active',1,0,0,0.00,0,520,2440,NULL,NULL,NULL,'2026-07-30 03:13:10','2026-09-03 03:13:10','2026-09-03 21:43:19',NULL),
(154,'5b04b08c-a7aa-11f1-931e-1aaca51249eb',22,1,'Daily Ghee + Atta Combo','daily-ghee-atta-combo','AB-COM-GHEE-ATTA','A2 desi ghee 500ml with 5kg stone-ground atta.','Everything your kitchen needs daily: A2 gir cow desi ghee 500ml plus 5kg stone-ground whole wheat atta, delivered fresh.','A2 desi ghee + stone-ground whole wheat atta','Fresh daily cooking essentials',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic Â· NPOP',1,NULL,1540.00,2090.00,1799.00,NULL,'1 pack','Save â‚¹291 on farm favourites','active',1,1,1,0.00,0,0,2,NULL,NULL,NULL,'2026-09-03 15:15:54','2026-09-03 15:15:54','2026-09-03 23:29:22',NULL),
(144,'3fc8d065-571d-4745-b7a2-aa02dff03325',17,1,'Organic A2 Gir Cow Desi Ghee (Jar)','organic-a2-gir-cow-desi-ghee-jar-2','AB-GHEE-A2-1000','Farm-fresh Organic A2 Gir Cow Desi Ghee (Jar) — cold-processed in small batches, no additives.','Organic A2 Gir Cow Desi Ghee (Jar) from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,1494.00,2490.00,2190.00,NULL,'1 litre',NULL,'active',1,1,0,0.00,0,271,1239,NULL,NULL,NULL,'2026-08-22 03:13:10','2026-09-03 03:13:10','2026-09-03 03:13:10',NULL),
(143,'f4bb41cd-400e-4354-a64d-c8968f22093a',17,1,'Organic A2 Gir Cow Desi Ghee (Jar)','organic-a2-gir-cow-desi-ghee-jar','AB-GHEE-A2-500','Farm-fresh Organic A2 Gir Cow Desi Ghee (Jar) — cold-processed in small batches, no additives.','Organic A2 Gir Cow Desi Ghee (Jar) from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.','100% pure single ingredient. No preservatives, no artificial colouring.','• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable',NULL,NULL,'Odisha, India','GreenRoot Farmer Collective','India Organic · NPOP',1,NULL,774.00,1290.00,1099.00,NULL,'500 ml',NULL,'active',1,1,0,0.00,0,102,3690,NULL,NULL,NULL,'2026-07-27 03:13:10','2026-09-03 03:13:10','2026-09-03 22:39:54',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `recently_viewed`
--

DROP TABLE IF EXISTS `recently_viewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recently_viewed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(64) DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `recently_viewed_product_id_foreign` (`product_id`),
  KEY `recently_viewed_user_id_viewed_at_index` (`user_id`,`viewed_at`),
  KEY `recently_viewed_session_id_viewed_at_index` (`session_id`,`viewed_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recently_viewed`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `recently_viewed` WRITE;
/*!40000 ALTER TABLE `recently_viewed` DISABLE KEYS */;
/*!40000 ALTER TABLE `recently_viewed` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `recently_vieweds`
--

DROP TABLE IF EXISTS `recently_vieweds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recently_vieweds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `recently_vieweds_product_id_foreign` (`product_id`),
  KEY `recently_vieweds_user_id_product_id_index` (`user_id`,`product_id`),
  KEY `recently_vieweds_session_id_product_id_index` (`session_id`,`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recently_vieweds`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `recently_vieweds` WRITE;
/*!40000 ALTER TABLE `recently_vieweds` DISABLE KEYS */;
INSERT INTO `recently_vieweds` VALUES
(89,NULL,'5wKB1xjBK80NrZDG4KR8BkEIQicjD5PmHORQyzyV',152,'2026-09-03 09:22:11'),
(90,NULL,'fBMbC5fmfpuuMAilT863UaiNEExb7Pqd9cinyX3y',143,'2026-09-03 09:23:42'),
(91,NULL,'voQoegiLVtd5voftTxQWICV8yvSNJ5tJ9oDK3oeF',143,'2026-09-03 12:18:52'),
(92,NULL,'iW4mBEakpyyRmd7ye6kT3jKV6nFUtyKb5zmu7rsj',154,'2026-09-03 12:18:52'),
(93,1,NULL,147,'2026-09-03 12:21:34'),
(94,1,NULL,151,'2026-09-03 12:45:28'),
(95,1,NULL,162,'2026-09-03 12:56:25'),
(96,NULL,'IPYoDVtcSvpTEItC7XN2g6uwalsTYo5CoZmwJYgG',162,'2026-09-03 13:43:53'),
(97,NULL,'QfVDj0bAlDAWs0A0wsnF4SGUCjFvUUi3CGzG9jdz',168,'2026-09-03 13:43:55'),
(98,NULL,'nJo4pmma8rbEIip84GZ1juKfVKRQa93jgBQ3GUjj',160,'2026-09-03 13:43:57'),
(99,NULL,'z1TJOPFtGhYsccesI16eMYkI2FcEmg0IqaatvY4S',162,'2026-09-03 13:44:35'),
(100,NULL,'EBNpXOPDw7qvgBCXkxX89GwsIx9zIwDfmMrRhTMX',168,'2026-09-03 13:44:35'),
(101,NULL,'LWf6xtgX8R6OrLRyTmTMBPCHxAjxmOA4cEWxY2Ed',170,'2026-09-03 13:44:35'),
(102,NULL,'6ibANKOLXi8PHqp14RLimzWkFWPQYhEn8O8D8YWK',160,'2026-09-03 13:44:35'),
(103,1,NULL,162,'2026-09-03 13:45:06'),
(104,1,NULL,152,'2026-09-03 13:45:25'),
(105,1,NULL,152,'2026-09-03 13:45:57'),
(106,1,NULL,152,'2026-09-03 13:46:19'),
(107,NULL,'n787NIP1rm8Jh2hlE0kKNzScqoF4S7dddivlaU7W',160,'2026-09-03 13:49:05'),
(108,NULL,'BnYbWHg2OUWHsC5uxdbayRArBYqHGI51MXuIrxj7',163,'2026-09-03 13:49:07'),
(109,1,NULL,148,'2026-09-03 21:21:03'),
(110,1,NULL,147,'2026-09-03 21:21:47'),
(111,1,NULL,151,'2026-09-03 21:22:24'),
(112,1,NULL,145,'2026-09-03 21:43:19'),
(113,NULL,'4KbbQZ7g6k9W6jtGZ3fQI4uKpzS1HRMuWbDfdWlg',160,'2026-09-03 21:47:19'),
(114,NULL,'p3jv1qCg6i2sTjLnUEoJ0lnKaAL8cXFB2vRQNJsP',160,'2026-09-03 21:50:55'),
(115,NULL,'vrRAENxMUR0pMUbpZwtNtG2dkfbgTw5wMAj0hx4z',160,'2026-09-03 21:58:19'),
(116,NULL,'PZKaWx0Y37h5YSmsjHn1LO3lhaKnlligWflI7JEp',160,'2026-09-03 22:03:39'),
(117,NULL,'AjqWbG1hOgkGZiafxmFw91cpan4219DrYuaonoBx',160,'2026-09-03 22:03:50'),
(118,NULL,'0usn86MDGRiWQ8S9ArtyPPESzfIiYFOsNHCqWL6E',160,'2026-09-03 22:08:04'),
(119,NULL,'FqecEcc2yiWlvjwB1piRcckiR9Rl3jBRqZT1LLIE',160,'2026-09-03 22:08:54'),
(120,NULL,'ZE4kKiBukBrgYE9EtymmcvYBnZGIsBIGmvvrIkka',160,'2026-09-03 22:09:02'),
(121,NULL,'uQxXYFa9BpJHMhNcQCUi7MwsJyI3YW2ayBDh5Pix',160,'2026-09-03 22:09:19'),
(122,NULL,'d2rItqKtG6W9XQvgGDfbiXcdlLz7sTY9MG0eY9bJ',160,'2026-09-03 22:09:27'),
(123,1,NULL,161,'2026-09-03 22:20:37'),
(124,1,NULL,147,'2026-09-03 22:20:49'),
(125,1,NULL,148,'2026-09-03 22:21:46'),
(126,NULL,'GiRGaR3OdTO3tCJwx2NT5Xzl7qOBmo8xT23JinTD',173,'2026-09-03 22:23:21'),
(127,NULL,'J0sH6yfi5aEdZVorB7qXTZdfkGzP0GEXVdKtj4CM',173,'2026-09-03 22:23:34'),
(128,NULL,'Pxen5IynxjYjcO6WUJmBBOGj9118RIN0LkYBH5kC',173,'2026-09-03 22:23:45'),
(129,NULL,'Pxen5IynxjYjcO6WUJmBBOGj9118RIN0LkYBH5kC',173,'2026-09-03 22:23:46'),
(130,NULL,'e1onln9cs0lAxGduUsewKP3fLzYNXOOpc8r7toUV',173,'2026-09-03 22:23:51'),
(131,NULL,'s9efIoQUUCoWaWwNoat6zyOOKWBqZyqwuENcJy6I',173,'2026-09-03 22:25:21'),
(132,NULL,'bmTljenbe8nDqMEfMZOAgw5tekTFfKLHxd3foghO',173,'2026-09-03 22:25:24'),
(133,NULL,'D2wXW9d56UdOmEJn6sXRpDCk1Kyin8NPK1nX5DlN',173,'2026-09-03 22:25:37'),
(134,1,NULL,148,'2026-09-03 22:36:17'),
(135,1,NULL,143,'2026-09-03 22:39:47'),
(136,1,NULL,143,'2026-09-03 22:39:54'),
(137,1,NULL,151,'2026-09-03 22:50:54'),
(138,1,NULL,167,'2026-09-03 23:08:26'),
(139,1,NULL,154,'2026-09-03 23:29:22'),
(140,1,NULL,157,'2026-09-03 23:41:46');
/*!40000 ALTER TABLE `recently_vieweds` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `related_products`
--

DROP TABLE IF EXISTS `related_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `related_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `related_product_id` bigint(20) unsigned NOT NULL,
  `type` enum('similar','bought_together') NOT NULL DEFAULT 'similar',
  `score` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rp_unique` (`product_id`,`related_product_id`,`type`),
  KEY `related_products_related_product_id_foreign` (`related_product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_products`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `related_products` WRITE;
/*!40000 ALTER TABLE `related_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `related_products` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_order_id_foreign` (`order_id`),
  KEY `reviews_product_id_status_index` (`product_id`,`status`),
  KEY `reviews_status_index` (`status`),
  KEY `reviews_order_id_index` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES
(5,152,7,NULL,5,'Verified purchase','Genuinely farm-fresh — you can taste the difference. Packaging was excellent.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(6,152,10,NULL,5,'Verified purchase','Genuinely farm-fresh — you can taste the difference. Packaging was excellent.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(7,151,9,NULL,5,'Verified purchase','Ordered for my family, everyone loved it. Will reorder for sure.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(8,151,12,NULL,5,'Verified purchase','Ordered for my family, everyone loved it. Will reorder for sure.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(9,150,11,NULL,5,'Verified purchase','Pure and authentic. Exactly what you expect from an organic brand.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(10,150,7,NULL,5,'Verified purchase','Pure and authentic. Exactly what you expect from an organic brand.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(11,149,13,NULL,5,'Verified purchase','Great price for the quality. Delivered fast and fresh.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(12,149,8,NULL,5,'Verified purchase','Great price for the quality. Delivered fast and fresh.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(13,148,7,NULL,5,'Verified purchase','Best desi ghee I have tried. Aroma is incredible after it melts.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(14,148,10,NULL,5,'Verified purchase','Best desi ghee I have tried. Aroma is incredible after it melts.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(15,147,9,NULL,5,'Verified purchase','Very satisfied. The cold-press method really preserves the nutrients.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(16,147,12,NULL,5,'Verified purchase','Very satisfied. The cold-press method really preserves the nutrients.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(17,146,11,NULL,5,'Verified purchase','Quality is top notch and it lasts long. Highly recommended.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(18,146,7,NULL,5,'Verified purchase','Quality is top notch and it lasts long. Highly recommended.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(19,145,13,NULL,5,'Verified purchase','My mother says it tastes just like the village ghee. Perfect.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(20,145,8,NULL,5,'Verified purchase','My mother says it tastes just like the village ghee. Perfect.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(21,144,7,NULL,5,'Verified purchase','Genuinely farm-fresh — you can taste the difference. Packaging was excellent.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(22,144,10,NULL,5,'Verified purchase','Genuinely farm-fresh — you can taste the difference. Packaging was excellent.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(23,143,9,NULL,5,'Verified purchase','Ordered for my family, everyone loved it. Will reorder for sure.',NULL,'approved',1,'2026-09-03 03:16:30','2026-09-03 03:16:30'),
(24,143,12,NULL,5,'Verified purchase','Ordered for my family, everyone loved it. Will reorder for sure.',NULL,'approved',0,'2026-09-03 03:16:30','2026-09-03 03:16:30');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES
(1,1),
(2,2),
(3,3),
(4,4),
(4,5),
(4,6),
(5,7),
(5,8),
(5,9),
(5,10),
(5,11),
(5,12),
(5,13);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'super_admin','Super Admin','2026-08-26 14:41:23','2026-08-26 14:41:23'),
(2,'admin','Admin','2026-08-26 14:41:23','2026-08-26 14:41:23'),
(3,'delivery_manager','Delivery Manager','2026-08-26 14:41:23','2026-08-26 14:41:23'),
(4,'delivery_person','Delivery Person','2026-08-26 14:41:23','2026-08-26 14:41:23'),
(5,'customer','Customer','2026-08-26 14:41:23','2026-08-26 14:41:23');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `search_logs`
--

DROP TABLE IF EXISTS `search_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `term` varchar(190) NOT NULL,
  `results_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `search_logs_user_id_foreign` (`user_id`),
  KEY `search_logs_term_index` (`term`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `search_logs` WRITE;
/*!40000 ALTER TABLE `search_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('jFoz5CfI6J5KUlFgCsv88fv0mYAtfPcqrNu4pXJM',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWU9zNnVzTVZSYlFBd1RvbnlKZ2tKMkdYTHlud1B0Y0Zhdm9lWWZqTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi9ub3RpZmljYXRpb25zL2ZyZXNoIjtzOjU6InJvdXRlIjtzOjI1OiJhZG1pbi5ub3RpZmljYXRpb25zLmZyZXNoIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1788532647);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_group_key_unique` (`group`,`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'store','name','AB Organic Farm','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(2,'store','tagline','Good Food. Naturally Better.','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(3,'store','phone','+91 94370 00000','2026-08-26 14:41:24','2026-09-02 23:40:32'),
(5,'store','address','Plot 12, Green Valley Road, Bhubaneswar, Odisha 751001','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(6,'delivery','standard_charge','49','2026-08-26 14:41:24','2026-08-26 14:41:24'),
(7,'delivery','free_above','499','2026-08-26 14:41:24','2026-09-02 08:00:30'),
(8,'delivery','min_order','199','2026-08-26 14:41:24','2026-08-26 14:41:24'),
(9,'order','auto_confirm','0','2026-08-26 14:41:24','2026-08-26 14:41:24'),
(10,'order','cancellation_window_hours','24','2026-08-26 14:41:24','2026-08-26 14:41:24'),
(11,'cod','enabled','1','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(12,'cod','max_order_value','10000','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(13,'cod','instructions','Please keep exact change ready. Our delivery partner will collect the cash at your doorstep.','2026-08-26 14:41:24','2026-08-26 14:41:24'),
(14,'seo','title','AB Organic Farm — Organic Products Delivered | Farm to Home','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(39,'cod','min_order_value','0','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(15,'seo','description','Shop certified organic fruits, vegetables, grains, pulses, spices and natural personal care. Fresh from farms to your doorstep. Cash on Delivery available.','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(36,'og','title','AB Organic Farm — Organic Products Delivered | Farm to Home','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(37,'og','description','Shop certified organic fruits, vegetables, grains, pulses, spices and natural personal care. Fresh from farms to your doorstep.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(16,'social','facebook','https://facebook.com/verdurafarms','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(17,'social','instagram','https://instagram.com/verdurafarms','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(18,'social','youtube','','2026-08-26 14:41:24','2026-08-26 14:41:24'),
(19,'social','whatsapp','+91 94370 00000','2026-08-26 14:41:24','2026-09-02 23:31:57'),
(22,'display','logo','logos/ab-organic-label.svg','2026-09-01 13:10:56','2026-09-02 18:37:04'),
(23,'display','logo_white','logos/ab-organic-label-white.svg','2026-09-01 13:10:56','2026-09-01 13:10:56'),
(94,'store','email','hello@verdurafarms.in','2026-09-02 18:36:55','2026-09-02 23:31:57'),
(38,'og','image_url','','2026-09-02 18:27:48','2026-09-02 18:27:48'),
(25,'display','nav_menu','[{\"label\":\"All Products\",\"icon\":\"nav-all\",\"url\":\"/categories/all\",\"highlight\":false,\"children\":[]},{\"label\":\"Ghee\",\"icon\":\"nav-ghee\",\"url\":\"/categories/ghee\",\"highlight\":false,\"children\":[{\"label\":\"Jar Type\",\"url\":\"/categories/ghee-jar-type\"},{\"label\":\"Packed Type\",\"url\":\"/categories/ghee-packed-type\"},{\"label\":\"Multitype Ghee\",\"url\":\"/categories/ghee-multitype\"}]},{\"label\":\"Oil\",\"icon\":\"nav-oils\",\"url\":\"/categories/oil\",\"highlight\":false,\"children\":[]},{\"label\":\"Atta\",\"icon\":\"nav-atta\",\"url\":\"/categories/atta\",\"highlight\":false,\"children\":[]},{\"label\":\"Hot Deals\",\"icon\":\"nav-deal\",\"url\":\"/search?q=deal\",\"highlight\":true,\"children\":[]},{\"label\":\"Shop\",\"icon\":\"nav-category\",\"url\":\"/categories\",\"highlight\":false,\"children\":[{\"label\":\"Ghee\",\"url\":\"/categories/ghee\"},{\"label\":\"Oil\",\"url\":\"/categories/oil\"},{\"label\":\"Atta\",\"url\":\"/categories/atta\"}]},{\"label\":\"Healthy Combo\",\"icon\":\"nav-combos\",\"url\":\"/search?q=combo\",\"highlight\":false,\"children\":[]}]','2026-09-02 04:54:02','2026-09-02 23:31:57'),
(93,'home','cta_title','Go Organic. Go Fresh. Go Fast.','2026-09-02 18:30:32','2026-09-02 23:33:07'),
(90,'home','cta_subtitle','Join thousands of families who trust AB Organic Farm for their daily groceries.','2026-09-02 18:30:04','2026-09-02 23:33:07'),
(91,'home','cta_button','Start Shopping','2026-09-02 18:30:04','2026-09-02 23:33:07'),
(92,'home','cta_link','/categories/all','2026-09-02 18:30:04','2026-09-02 23:33:07'),
(96,'cod','advance_percent','0','2026-09-02 18:36:55','2026-09-02 23:33:07'),
(97,'inventory','low_stock_threshold','5','2026-09-02 18:36:55','2026-09-02 23:33:07'),
(98,'notify','admin_email','hello@verdurafarms.in','2026-09-02 18:36:55','2026-09-02 23:33:07'),
(99,'footer','copyright','AB Organic Farm Pvt. Ltd.','2026-09-02 18:36:55','2026-09-02 23:31:57'),
(100,'display','whatsapp_name','AB Organic Farm','2026-09-02 18:36:55','2026-09-02 23:31:57'),
(26,'display','trust_pills','[{\"text\":\"100% Certified Organic\",\"icon\":\"shield-check\"},{\"text\":\"Lab Tested\",\"icon\":\"flask-conical\"},{\"text\":\"Farm to Table\",\"icon\":\"truck\"}]','2026-09-02 08:00:30','2026-09-02 23:31:57'),
(74,'display','app_download_enabled','0','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(75,'display','app_download_heading','Unlock 17% OFF exclusively on the App','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(27,'footer','company_name','AB Organic Farm','2026-09-02 08:00:30','2026-09-02 23:31:57'),
(29,'footer','newsletter_heading','Stay in the loop','2026-09-02 08:00:30','2026-09-02 23:31:57'),
(30,'footer','newsletter_sub','Fresh offers & farm stories. No spam.','2026-09-02 08:00:30','2026-09-02 23:31:57'),
(32,'display','whatsapp_greeting','Hi there! How can we help you today?','2026-09-02 08:01:11','2026-09-02 23:31:57'),
(33,'display','app_icon','','2026-09-02 08:02:28','2026-09-02 08:02:28'),
(88,'display','bottom_nav','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(41,'cod','delivery_charges','49','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(42,'cod','free_delivery_above','499','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(44,'inventory','email_alerts','1','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(95,'seo','keywords','organic food, atta, ghee, natural oils, AB Organic','2026-09-02 18:36:55','2026-09-02 23:33:07'),
(46,'notify','sms','0','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(47,'notify','whatsapp','1','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(49,'home','delivery_charge_text','Free delivery above ₹499','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(50,'home','tags','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(51,'home','promo_cards','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(52,'home','brand_title','Shop by Brand','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(53,'home','brand_subtitle','Explore a curated range from trusted brands.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(54,'home','featured_title','Featured Products','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(55,'home','featured_subtitle','Hand-picked organic favourites our customers love.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(56,'home','best_title','Best Sellers','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(57,'home','best_subtitle','The products everyone keeps coming back for.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(58,'home','new_title','New Arrivals','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(59,'home','new_subtitle','Fresh from the farm and just landed in store.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(60,'home','why_title','Why Choose Us','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(61,'home','why_items','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(62,'home','testimonial_title','What Our Customers Say','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(67,'footer','tagline','Good Food. Naturally Better.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(68,'footer','address','Plot 12, Green Valley Road, Bhubaneswar, Odisha 751001','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(69,'footer','links_services','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(70,'footer','links_policies','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(71,'footer','socials','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(72,'store','contact_link','#','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(73,'display','announcement_items','[\"Free delivery on orders above ₹499\",\"100% certified organic · straight from the farm\"]','2026-09-02 18:27:48','2026-09-02 23:49:39'),
(76,'display','app_download_sub','Get the AB Organic Farm app today.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(77,'display','app_download_url2','#','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(78,'display','app_store_url','#','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(79,'display','app_download_url','#','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(80,'display','rewards_enabled','1','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(81,'display','rewards_mainline','Earn rewards on every order!','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(82,'display','rewards_coins','0','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(83,'display','rewards_subline','Your rewards await','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(84,'display','rewards_items','[]','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(85,'display','whatsapp_enabled','0','2026-09-02 18:27:48','2026-09-02 18:30:04'),
(86,'display','whatsapp_number','919999999999','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(87,'display','whatsapp_message','Hi! I have a question about your products.','2026-09-02 18:27:48','2026-09-02 23:33:07'),
(101,'home','search_placeholder','Search products, e.g. ghee','2026-09-02 23:33:07','2026-09-02 23:33:07');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `location` varchar(120) DEFAULT NULL,
  `message` text NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `avatar_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_uuid_unique` (`uuid`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'f0c1bd74-a4a6-4ad9-ab73-2ef5fd76b6c5','Subrat Admin','admin@verdura.test',NULL,'$2y$12$hInqYXC.RlpQ3hlR4LZx0up45lXT5ONe4LQmRII.TLKIfkCYAEsyy',NULL,NULL,'2026-08-26 14:41:23','2026-09-04 04:24:29',NULL,NULL,NULL,1,'2026-09-04 04:24:29',NULL),
(2,'b34c3d42-e1b0-46f9-9a5b-ac1edcfd0c49','Priya Manager','manager@verdura.test',NULL,'$2y$12$fI6D/W23gLkXvIVEb0YMkeRRaSH2zZ7n2xLcCMArOqRRnvY5xZ/5m',NULL,NULL,'2026-08-26 14:41:23','2026-08-26 14:41:23',NULL,NULL,NULL,1,NULL,NULL),
(3,'498f01f2-76de-4241-b192-46e7e5af36e4','Rakesh Das','delivery.manager@verdura.test',NULL,'$2y$12$usWY7rYBfOsbYbPVNbnxJuKclN7dUIYMEYEHmv63Ikej0vT8TtqNW',NULL,NULL,'2026-08-26 14:41:23','2026-08-26 14:41:23',NULL,NULL,NULL,1,NULL,NULL),
(4,'4aa5cddd-013f-4703-b827-cd02cefe9eb7','Dillip Sahu','dillip@verdura.test',NULL,'$2y$12$m3lMcdTabXtWXRE69kI5aOHpInpDIE8j8dNoDgbY8S7.cMLpZHp.i',NULL,NULL,'2026-08-26 14:41:24','2026-09-03 23:22:31',NULL,NULL,NULL,1,'2026-09-03 23:22:31',NULL),
(5,'520b86d9-9c75-409e-9601-f55437416feb','Manoj Behera','manoj@verdura.test',NULL,'$2y$12$fV8SlGffNfOxS.29S7dQaOTUVKcxmgZ9j9pgpgPoNQMcXi40ckLO6',NULL,NULL,'2026-08-26 14:41:24','2026-09-04 00:05:14',NULL,NULL,NULL,1,'2026-09-04 00:05:14',NULL),
(6,'eab79b5e-3ce5-4409-bd2a-5196523034ea','Sanjay Malik','sanjay@verdura.test',NULL,'$2y$12$cjw.UADRwfyxbYLQ226epOgmKlHaz9tA7YX6VQZEw0RE9Vf9Q9fDa',NULL,NULL,'2026-08-26 14:41:24','2026-08-26 14:41:24',NULL,NULL,NULL,1,NULL,NULL),
(7,'998bd897-9772-4062-8da3-b1b1af7a00c5','Ankita Mohanty','ankita@example.com',NULL,'$2y$12$pMkavezJmHrj/01C4IV5B.CD/EaazDvjMCE9wmlaUTUaGFk2J15Ma','9437011111',NULL,'2026-08-26 14:41:24','2026-09-01 04:21:06',NULL,NULL,NULL,1,'2026-09-01 04:21:06',NULL),
(8,'d7bdc6f8-9cf1-4242-a897-7acd3986570e','Rahul Panda','rahul@example.com',NULL,'$2y$12$Vk.1C1mNflXSYpIfDMmrUuIFI7rZEICYGSLbzGLrT12DrwWeJIl6y','9437022222',NULL,'2026-08-26 14:41:24','2026-08-26 14:41:24',NULL,NULL,NULL,1,NULL,NULL),
(9,'8f79a08a-9526-4f82-bcc4-38b648d55513','Sneha Rath','sneha@example.com',NULL,'$2y$12$0dOBtgJjuaJNyOUj93peQeOFFI6iKLcckTYxrloI68Prh9504Poqe','9437033333',NULL,'2026-08-26 14:41:24','2026-08-26 14:41:24',NULL,NULL,NULL,1,NULL,NULL),
(10,'f20f1e69-ada7-4b97-a405-6d02a283244c','Debashish Nayak','debashish@example.com',NULL,'$2y$12$T21HFXRigkaxjpOrXtQQFeVm0mzl1F90ORgCQ8g89fL.W43uHWAoe','9437044444',NULL,'2026-08-26 14:41:25','2026-08-26 14:41:25',NULL,NULL,NULL,1,NULL,NULL),
(11,'1b9c51aa-2c6b-4153-9699-938bf2bc8adb','Priyanka Sahoo','priyanka@example.com',NULL,'$2y$12$SiiTZHDFAnuA2zKooiIktOsoJR9Uo2lMaTCMJBbsPftt4QGpFJV8a','9437055555',NULL,'2026-08-26 14:41:25','2026-08-26 14:41:25',NULL,NULL,NULL,1,NULL,NULL),
(12,'b0a9fb52-010b-4912-9496-e071e5bae67a','Test User','newuser1788256994@example.com',NULL,'$2y$04$/a5YtuQan9GjldqDXs3QZe/kCf0nnHuoJ.VXiYc8B5UOV4shvbFea','9768833823',NULL,'2026-09-01 04:33:14','2026-09-01 04:33:14',NULL,'male',NULL,1,NULL,NULL),
(13,'bb69f720-3fdc-4eec-9859-02924422a582','subrat Kumar sahoo','subrat@gmail.com',NULL,'$2y$12$P9.4Sj8QGPCUX9RsIHq1peF//FjAmJ5nq48DaYAG6oKzmI7313Na2','9348225868','5j2xrR5T0iZAIzrbhIT03VNM0Bl5eCHKGxf3QBRM7jwQW5Dx7vMHG4wLXGt2','2026-09-01 04:40:01','2026-09-01 04:40:18',NULL,NULL,NULL,1,'2026-09-01 04:40:18',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_items_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlist_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
INSERT INTO `wishlist_items` VALUES
(3,1,148,'2026-09-04 02:51:02'),
(4,1,147,'2026-09-04 02:51:47'),
(5,1,151,'2026-09-04 02:52:24'),
(6,1,167,'2026-09-04 04:38:25'),
(7,4,173,'2026-09-04 04:50:27');
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-09-04 20:07:58
