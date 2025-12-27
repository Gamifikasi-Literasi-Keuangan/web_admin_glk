-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: mysql-glk-ahmadriyadhalmaliki-9dda.g.aivencloud.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ 'ebcd933f-caa7-11f0-a93b-8e56c451e8a0:1-373';

--
-- Table structure for table `ai_feedback_loop`
--

DROP TABLE IF EXISTS `ai_feedback_loop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_feedback_loop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` varchar(50) DEFAULT NULL,
  `risk_level` varchar(20) DEFAULT NULL,
  `trigger_type` varchar(50) DEFAULT NULL,
  `threshold_value` float DEFAULT NULL,
  `intervention_template_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_feedback_loop`
--

LOCK TABLES `ai_feedback_loop` WRITE;
/*!40000 ALTER TABLE `ai_feedback_loop` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_feedback_loop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_models`
--

DROP TABLE IF EXISTS `ai_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_models` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model_type` varchar(20) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `model_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `ai_models_chk_1` CHECK (json_valid(`model_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_models`
--

LOCK TABLES `ai_models` WRITE;
/*!40000 ALTER TABLE `ai_models` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_tokens` (
  `token` varchar(100) NOT NULL,
  `type` enum('access','refresh') DEFAULT 'access',
  `userId` int NOT NULL,
  `expiresAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token`),
  KEY `auth_tokens_ibfk_1` (`userId`),
  CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `auth_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_tokens`
--

LOCK TABLES `auth_tokens` WRITE;
/*!40000 ALTER TABLE `auth_tokens` DISABLE KEYS */;
INSERT INTO `auth_tokens` VALUES ('8ATge0oaE4cyRF2aFD68AR6s2IDlCFLW8NebhDNfyAlneF9yTFLCu7hWInUZ','refresh',7,'2025-12-29 06:43:55','2025-11-29 06:43:55'),('bgmLse4vIIn3TKQ7CJxhKs1GnEhdqyte3lRx89odCnJt1X8lP687PQFWeCLm','refresh',7,'2025-12-28 11:36:39','2025-11-28 11:36:39'),('Czl3D7gsDMbkCrXK9fopTiQpOKDYkA66lAeNjfkBiAq2fKBAOPGT5L4VH419','refresh',4,'2025-12-27 07:35:35','2025-11-27 07:35:35'),('EYyMqkiDo3wuD1YtG1ihJOCU6orYbdTFp1ZZQc80LkjO5bB9ET2QERETqhzy','refresh',7,'2025-12-29 08:42:29','2025-11-29 08:42:29'),('FETp9uW5KNrKLOxli0kkZ2avo2sbvPIRz9dKwFbO4E0pfGL5bckaM05G7eej','refresh',7,'2025-12-29 02:56:10','2025-11-29 02:56:10'),('FmKAfigCfgEUklbyV0kfraO6ae4HhG7469hwG7OJmTGAGdgCHaanLgGHdSkv','refresh',5,'2025-12-28 03:01:34','2025-11-28 03:01:34'),('gjljPZ5Ym7xQ340RMaQtAKS4YbGDsSJHI02uuaK0YwRCqrwWH7rVPD5tlz0T','refresh',7,'2025-12-28 15:03:36','2025-11-28 15:03:36'),('Ljr5puKn2fgd0kbTwquWXgSBUcj4ZcBhOexYAJiUeaMTogdefQN3EfsqU56W','refresh',7,'2025-12-28 12:42:23','2025-11-28 12:42:23'),('pBSmNTVHfTR7qCUCwVnTWBK844VwNhnAYrE8dmZ8kRHPqtZLtyWkKAxPIYns','refresh',7,'2025-12-29 06:26:33','2025-11-29 06:26:33'),('q8MDU0TgwyS5hZeHqDm8rVPDwQREbdgN9cxfBswsRyRhvhoE2CgQIiEU8WG0','refresh',5,'2025-12-28 03:13:33','2025-11-28 03:13:33'),('xMXBN88bTETso1udgEnvZjy1RnY0vxSQrjqvEtpQzLPj8OyaIMnMyk4jvemd','refresh',7,'2025-12-29 02:20:35','2025-11-29 02:20:35'),('YeS1XNAVgNLnQgIsJEGLIntExDeZzZZ6xqojIudzHwfurZfeFGbFGpDWRYJv','refresh',7,'2025-12-28 14:46:40','2025-11-28 14:46:40');
/*!40000 ALTER TABLE `auth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_users`
--

DROP TABLE IF EXISTS `auth_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `passwordHash` text,
  `role` varchar(20) DEFAULT 'player',
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_users`
--

LOCK TABLES `auth_users` WRITE;
/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
INSERT INTO `auth_users` VALUES (1,'mona_lisa','google_1029384756',NULL,'player','https://ui-avatars.com/api/?name=Mona','2025-11-27 02:53:33','2025-11-27 07:28:45'),(4,'Mona Lisa Test','google_test_123',NULL,'player','https://via.placeholder.com/150','2025-11-27 07:35:35','2025-11-27 07:35:35'),(5,'Mona Tester','google_test_user_001',NULL,'player','https://ui-avatars.com/api/?name=Mona+Tester','2025-11-28 03:00:10','2025-11-28 03:00:10'),(6,'dev_tester','dev_123',NULL,'player',NULL,'2025-11-28 07:00:59','2025-11-28 07:00:59'),(7,'Tester Postman','google_id_tester_001',NULL,'player','https://ui-avatars.com/api/?name=Tester+Postman','2025-11-28 11:36:38','2025-11-28 11:36:38');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boardtiles`
--

DROP TABLE IF EXISTS `boardtiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boardtiles` (
  `tile_id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `linked_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `position_index` int DEFAULT NULL,
  PRIMARY KEY (`tile_id`),
  CONSTRAINT `boardtiles_chk_1` CHECK (json_valid(`linked_content`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boardtiles`
--

LOCK TABLES `boardtiles` WRITE;
/*!40000 ALTER TABLE `boardtiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `boardtiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `id` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `title` varchar(200) NOT NULL,
  `narration` text NOT NULL,
  `scoreChange` int NOT NULL,
  `action` varchar(50) NOT NULL,
  `categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `difficulty` int DEFAULT NULL,
  `expected_benefit` int DEFAULT '0',
  `learning_objective` text,
  `weak_area_relevance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `cluster_relevance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `historical_success_rate` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `cards_chk_1` CHECK (json_valid(`categories`)),
  CONSTRAINT `cards_chk_2` CHECK (json_valid(`tags`)),
  CONSTRAINT `cards_chk_3` CHECK (json_valid(`weak_area_relevance`)),
  CONSTRAINT `cards_chk_4` CHECK (json_valid(`cluster_relevance`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES ('chance_01','chance','Bonus Tahunan','Selamat! Kinerja Anda bagus.',15,'add_balance','[\"pendapatan\"]',NULL,1,15,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33'),('risk_01','risk','Sakit Gigi','Anda harus ke dokter gigi segera.',-5,'pay_cash','[\"kesehatan\", \"pengeluaran\"]',NULL,2,-5,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `minPlayers` int NOT NULL DEFAULT '2',
  `maxPlayers` int NOT NULL DEFAULT '5',
  `max_turns` int DEFAULT '50',
  `version` int NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,2,5,50,1,'2025-11-27 02:53:33','2025-11-28 04:01:02');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_sessions`
--

DROP TABLE IF EXISTS `game_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game_sessions` (
  `sessionId` varchar(50) NOT NULL,
  `host_player_id` varchar(50) NOT NULL,
  `max_players` int DEFAULT '5',
  `max_turns` int DEFAULT '100',
  `status` varchar(20) NOT NULL,
  `current_player_id` varchar(50) DEFAULT NULL,
  `current_turn` int DEFAULT '0',
  `game_state` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sessionId`),
  KEY `game_sessions_ibfk_1` (`host_player_id`),
  KEY `game_sessions_ibfk_2` (`current_player_id`),
  CONSTRAINT `game_sessions_ibfk_1` FOREIGN KEY (`host_player_id`) REFERENCES `players` (`PlayerId`),
  CONSTRAINT `game_sessions_ibfk_2` FOREIGN KEY (`current_player_id`) REFERENCES `players` (`PlayerId`),
  CONSTRAINT `game_sessions_chk_1` CHECK (json_valid(`game_state`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_sessions`
--

LOCK TABLES `game_sessions` WRITE;
/*!40000 ALTER TABLE `game_sessions` DISABLE KEYS */;
INSERT INTO `game_sessions` VALUES ('game001','player123',4,50,'active','player123',1,'{\"turn_phase\":\"waiting\",\"last_dice\":0}','2025-11-27 02:53:33',NULL,'2025-11-27 02:53:33','2025-11-28 14:09:11');
/*!40000 ALTER TABLE `game_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interventiontemplates`
--

DROP TABLE IF EXISTS `interventiontemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `interventiontemplates` (
  `level` int NOT NULL,
  `risk_level` varchar(20) NOT NULL,
  `title_template` varchar(200) NOT NULL,
  `message_template` text NOT NULL,
  `actions_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_mandatory` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`level`),
  CONSTRAINT `interventiontemplates_chk_1` CHECK (json_valid(`actions_template`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interventiontemplates`
--

LOCK TABLES `interventiontemplates` WRITE;
/*!40000 ALTER TABLE `interventiontemplates` DISABLE KEYS */;
INSERT INTO `interventiontemplates` VALUES (1,'low','Hati-hati','Kamu mulai mengambil keputusan berisiko. Coba pertimbangkan risikonya.','[{\"id\": \"ok\", \"text\": \"Mengerti\"}]',0),(2,'moderate','Peringatan Strategi','⚠️ Kamu sudah 3x salah mengambil keputusan. Mungkin perlu review konsep dasar?','[{\"id\": \"heed\", \"text\": \"Lihat Penjelasan Singkat\"}, {\"id\": \"ignore\", \"text\": \"Lanjut Tanpa Hint\"}]',0),(3,'critical','Bahaya Finansial','🛑 Skor kamu kritis! Sistem menyarankan berhenti sejenak untuk evaluasi.','[{\"id\": \"review\", \"text\": \"Evaluasi Sekarang\"}]',1);
/*!40000 ALTER TABLE `interventiontemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participatesin`
--

DROP TABLE IF EXISTS `participatesin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participatesin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessionId` varchar(50) NOT NULL,
  `playerId` varchar(50) NOT NULL,
  `player_order` int DEFAULT NULL,
  `position` int DEFAULT '0',
  `score` int DEFAULT '0',
  `connection_status` varchar(20) DEFAULT 'disconnected',
  `is_ready` tinyint(1) DEFAULT '0',
  `rank` int DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sessionId` (`sessionId`),
  KEY `playerId` (`playerId`),
  CONSTRAINT `participatesin_ibfk_1` FOREIGN KEY (`sessionId`) REFERENCES `game_sessions` (`sessionId`),
  CONSTRAINT `participatesin_ibfk_2` FOREIGN KEY (`playerId`) REFERENCES `players` (`PlayerId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participatesin`
--

LOCK TABLES `participatesin` WRITE;
/*!40000 ALTER TABLE `participatesin` DISABLE KEYS */;
INSERT INTO `participatesin` VALUES (1,'game001','player123',1,0,1000,'connected',1,NULL,'2025-11-27 02:53:33','2025-11-28 14:14:08','2025-11-28 14:14:08'),(8,'game001','player_zEGwhs9e',2,6,0,'connected',1,NULL,'2025-11-29 05:35:05','2025-11-30 05:27:57','2025-11-29 05:35:05');
/*!40000 ALTER TABLE `participatesin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',4,'game-client','e6fdbab5f28079a4075a39b0dbba3b033d12bd8ce4a4bda3331a63a04fcb97b6','[\"*\"]',NULL,NULL,'2025-11-27 07:35:35','2025-11-27 07:35:35'),(2,'App\\Models\\User',4,'access_token','ad52dbcbe4a4101d7b9116ecb59491aff0597967b7d25cb88a48b83d7516adbe','[\"*\"]',NULL,NULL,'2025-11-27 07:35:35','2025-11-27 07:35:35'),(3,'App\\Models\\User',5,'game-client','494c20b85171a43ad58ae5ee3bc19c3211a03ffcf9157246c88cdc2bb7a5c33a','[\"*\"]',NULL,NULL,'2025-11-28 03:00:13','2025-11-28 03:00:13'),(4,'App\\Models\\User',5,'game-client','c74a7b30fe308623446b9ad2ff7680fdba5131d2befd36e27f25ef9c84f0bb58','[\"*\"]',NULL,NULL,'2025-11-28 03:01:33','2025-11-28 03:01:33'),(5,'App\\Models\\User',5,'game-client','08629c171e51e871ee6fb12911ab661f1caf9a08586cda5492b4c84083f951a6','[\"*\"]',NULL,NULL,'2025-11-28 03:13:33','2025-11-28 03:13:33'),(6,'App\\Models\\User',5,'game-client','48d5e6ba325f1f0271257913bb858824758aae77aaf3098c0fa8c2174f22756d','[\"*\"]',NULL,NULL,'2025-11-28 03:13:43','2025-11-28 03:13:43'),(7,'App\\Models\\User',6,'dev-token','42b862b5c4d9d374a51608e80b9f272ad1315115f310425538a0047a0c471093','[\"*\"]',NULL,NULL,'2025-11-28 11:26:10','2025-11-28 11:26:10'),(8,'App\\Models\\User',7,'game-client','45de9a1bf7040b362afe5c8ebf209f54c824d423669ea1325a88b92e030af9bf','[\"*\"]','2025-11-28 11:41:05',NULL,'2025-11-28 11:36:39','2025-11-28 11:41:05'),(9,'App\\Models\\User',7,'game-client','1aa119b38b047305fabb1d8bd933959ad04dff12de1bdb3ed6fd2ad96f23d9bf','[\"*\"]','2025-11-28 12:34:49',NULL,'2025-11-28 11:47:11','2025-11-28 12:34:49'),(10,'App\\Models\\User',7,'game-client','1577e40636c82fd221488060c319ee632cde79ac65d708a975f54a2a141e990d','[\"*\"]','2025-11-28 12:42:54',NULL,'2025-11-28 12:42:23','2025-11-28 12:42:54'),(11,'App\\Models\\User',7,'game-client','630eb8e25e31470da5ae65c4b030ca1c7590dfe5774d9b1511a91b4505b76986','[\"*\"]',NULL,NULL,'2025-11-28 14:33:15','2025-11-28 14:33:15'),(12,'App\\Models\\User',7,'game-client','db1f0516addf94fee93cf9a13c7d75697f4ca8e272d9d40f72dbe4b696208f2a','[\"*\"]',NULL,NULL,'2025-11-28 14:40:44','2025-11-28 14:40:44'),(13,'App\\Models\\User',7,'game-client','d1db2064970a2540751eae5d0ee097a86e4cabf622b8ff415f7ff4695880a835','[\"*\"]',NULL,NULL,'2025-11-28 14:46:40','2025-11-28 14:46:40'),(14,'App\\Models\\User',7,'game-client','438da206bc560b198a1c2b0eca6bbbfcf3e0053bbd2830d6c1f5804b683343d9','[\"*\"]',NULL,NULL,'2025-11-28 15:03:36','2025-11-28 15:03:36'),(15,'App\\Models\\User',7,'game-client','eb6ef81ac6119374c2f6a5aa807939e1d8c1591c9496068d1f2750a7f1ac0717','[\"*\"]',NULL,NULL,'2025-11-28 15:09:34','2025-11-28 15:09:34'),(16,'App\\Models\\User',7,'game-client','387a8d3896b76cc141c1d7215e9a14d6ab7430cc42e11f17555d8e1a8b1b0ae9','[\"*\"]',NULL,NULL,'2025-11-29 02:18:23','2025-11-29 02:18:23'),(17,'App\\Models\\User',7,'game-client','0cb437f33306dbf0343ae12eb1d1ce4d2152bd29e2c89f1a7efb826effd94335','[\"*\"]',NULL,NULL,'2025-11-29 02:20:35','2025-11-29 02:20:35'),(18,'App\\Models\\User',7,'game-client','37603e86ff944fd8051a9a10760dd88ece28d67830aaf734b95a67248784cf4c','[\"*\"]','2025-11-29 06:49:17',NULL,'2025-11-29 02:56:10','2025-11-29 06:49:17'),(19,'App\\Models\\User',7,'game-client','d9c815d8be9305e9b20c9758318d0739caa91d11005e5e1d84ba299931744acf','[\"*\"]','2025-11-30 09:40:50',NULL,'2025-11-29 04:17:49','2025-11-30 09:40:50'),(20,'App\\Models\\User',7,'game-client','6f5fefac28f07e365a81183cf8ba83e67db7807d458fc38074481e66d761a0c4','[\"*\"]','2025-11-30 07:28:05',NULL,'2025-11-29 05:37:37','2025-11-30 07:28:05'),(21,'App\\Models\\User',7,'game-client','a4bc6a2df786cd662e3d84c3e3339764086f7d3bbb58fabc40d6570179d60e7c','[\"*\"]','2025-11-29 06:24:28',NULL,'2025-11-29 06:16:10','2025-11-29 06:24:28'),(22,'App\\Models\\User',7,'game-client','0776482775cba5c48b37dbc5e7c6bcc4a1a80e21c3cb53be4ba7305bb15f18b7','[\"*\"]',NULL,NULL,'2025-11-29 06:26:33','2025-11-29 06:26:33'),(23,'App\\Models\\User',7,'game-client','f8186333830ecedb5b0e875248d9d860852b3d2d3f31c1abc563269b952f2319','[\"*\"]','2025-11-29 06:44:24',NULL,'2025-11-29 06:34:05','2025-11-29 06:44:24'),(24,'App\\Models\\User',7,'game-client','12363f0d734454270717c8c6af7a18ca1b3a5b5eeca89abeda0f0da2fc4b49d8','[\"*\"]','2025-11-30 06:47:53',NULL,'2025-11-29 06:43:55','2025-11-30 06:47:53'),(25,'App\\Models\\User',7,'game-client','8fed9d4bc58353805546279ecc52fc259ab91c563b22c616beea029a5dc6a058','[\"*\"]','2025-11-29 08:42:42',NULL,'2025-11-29 08:42:29','2025-11-29 08:42:42'),(26,'App\\Models\\User',7,'game-client','62f71813e2c9ffc7e9256639e0cf47f8e1cc6a9f16c6fe19ba7193ab035f2a20','[\"*\"]','2025-11-30 04:55:16',NULL,'2025-11-29 08:46:57','2025-11-30 04:55:16'),(27,'App\\Models\\User',7,'game-client','0c3ffa43645579be70b113dbee7ea32df475bea35594ef45678eaeb575c915fa','[\"*\"]','2025-11-30 02:12:53',NULL,'2025-11-30 02:11:47','2025-11-30 02:12:53'),(28,'App\\Models\\User',7,'game-client','bd37be273389da9cf0d21e6f02a94d563b2369a830b5639a617493eebd2ba26d','[\"*\"]','2025-11-30 05:02:36',NULL,'2025-11-30 03:23:35','2025-11-30 05:02:36'),(29,'App\\Models\\User',7,'game-client','4602d0737acb6cbb9ae549dab42732f0c767229fd0cadbafe33a1107f8531f47','[\"*\"]','2025-11-30 07:30:25',NULL,'2025-11-30 05:13:50','2025-11-30 07:30:25'),(30,'App\\Models\\User',7,'game-client','59dffe6538a1cbb5b863d375d45afd3fb60b83a43e9d4c1983c37b8d530899a9','[\"*\"]','2025-11-30 10:04:57',NULL,'2025-11-30 09:38:35','2025-11-30 10:04:57');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_decisions`
--

DROP TABLE IF EXISTS `player_decisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `player_decisions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` varchar(50) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `turn_id` varchar(50) DEFAULT NULL,
  `turn_number` int DEFAULT NULL,
  `content_id` varchar(50) NOT NULL,
  `content_type` varchar(50) NOT NULL,
  `selected_option` char(1) DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `score_change` int NOT NULL,
  `decision_time_seconds` int DEFAULT NULL,
  `intervention_triggered` tinyint(1) DEFAULT '0',
  `intervention_level` int DEFAULT NULL,
  `behavioral_signals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `intervention_id` varchar(100) DEFAULT NULL,
  `intervention_type` varchar(100) DEFAULT NULL,
  `player_response` varchar(50) DEFAULT NULL,
  `actual_decisions` varchar(50) DEFAULT NULL,
  `vector_representation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `similarity_score` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `player_decisions_ibfk_1` (`player_id`),
  KEY `player_decisions_ibfk_2` (`session_id`),
  KEY `player_decisions_ibfk_3` (`turn_id`),
  CONSTRAINT `player_decisions_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`PlayerId`),
  CONSTRAINT `player_decisions_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `game_sessions` (`sessionId`),
  CONSTRAINT `player_decisions_ibfk_3` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`turn_id`),
  CONSTRAINT `player_decisions_chk_1` CHECK (json_valid(`behavioral_signals`)),
  CONSTRAINT `player_decisions_chk_2` CHECK (json_valid(`vector_representation`))
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_decisions`
--

LOCK TABLES `player_decisions` WRITE;
/*!40000 ALTER TABLE `player_decisions` DISABLE KEYS */;
INSERT INTO `player_decisions` VALUES (7,'player_zEGwhs9e','game001',NULL,NULL,'sc_01','scenario',NULL,0,-10,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 09:49:28'),(8,'player_zEGwhs9e','game001',NULL,NULL,'sc_02','scenario',NULL,0,-10,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 09:48:28'),(9,'player_zEGwhs9e','game001',NULL,NULL,'sc_03','scenario',NULL,0,-10,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 09:47:28'),(10,'player_zEGwhs9e','game001',NULL,1,'pinjaman_teman_01','intervention_log',NULL,0,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 10:04:11'),(11,'player_zEGwhs9e','game001',NULL,1,'pinjaman_teman_01','intervention_log',NULL,0,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 10:04:59');
/*!40000 ALTER TABLE `player_decisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playerprofile`
--

DROP TABLE IF EXISTS `playerprofile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `playerprofile` (
  `PlayerId` varchar(50) NOT NULL,
  `onboarding_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `cluster` varchar(50) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `traits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `weak_areas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `recommended_focus` varchar(100) DEFAULT NULL,
  `lifetime_scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `decision_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `behavior_pattern` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `confidence_level` float DEFAULT '0.3',
  `fuzzy_scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ann_probabilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `last_recommendation` text,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thresholds` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `last_threshold_update_reason` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PlayerId`),
  CONSTRAINT `playerprofile_ibfk_1` FOREIGN KEY (`PlayerId`) REFERENCES `players` (`PlayerId`),
  CONSTRAINT `playerprofile_chk_1` CHECK (json_valid(`onboarding_answers`)),
  CONSTRAINT `playerprofile_chk_2` CHECK (json_valid(`traits`)),
  CONSTRAINT `playerprofile_chk_3` CHECK (json_valid(`weak_areas`)),
  CONSTRAINT `playerprofile_chk_4` CHECK (json_valid(`lifetime_scores`)),
  CONSTRAINT `playerprofile_chk_5` CHECK (json_valid(`decision_history`)),
  CONSTRAINT `playerprofile_chk_6` CHECK (json_valid(`behavior_pattern`)),
  CONSTRAINT `playerprofile_chk_7` CHECK (json_valid(`fuzzy_scores`)),
  CONSTRAINT `playerprofile_chk_8` CHECK (json_valid(`ann_probabilities`)),
  CONSTRAINT `playerprofile_chk_9` CHECK (json_valid(`thresholds`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playerprofile`
--

LOCK TABLES `playerprofile` WRITE;
/*!40000 ALTER TABLE `playerprofile` DISABLE KEYS */;
INSERT INTO `playerprofile` VALUES ('player_dev',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.3,NULL,NULL,NULL,'2025-11-28 11:26:08','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-28 11:26:09','2025-11-28 11:26:09'),('player_dsxOKbEg',NULL,NULL,NULL,NULL,NULL,NULL,'[]',NULL,NULL,0,NULL,NULL,NULL,'2025-11-28 03:00:11','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-28 03:00:11','2025-11-28 03:00:11'),('player_QYcxnDRB',NULL,NULL,NULL,NULL,NULL,NULL,'[]',NULL,NULL,0,NULL,NULL,NULL,'2025-11-27 07:35:35','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-27 07:35:35','2025-11-27 07:35:35'),('player_zEGwhs9e','\"[\\\"A\\\",\\\"B\\\",\\\"C\\\"]\"','UNKNOWN PLAYER','Unknown','[]','[]',NULL,'{\"pendapatan\":60,\"anggaran\":40,\"tabungan_dan_dana_darurat\":30,\"utang\":80,\"investasi\":20,\"asuransi_dan_proteksi\":10,\"tujuan_jangka_panjang\":20}',NULL,NULL,0,NULL,NULL,NULL,'2025-11-30 09:40:51','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-28 11:36:39','2025-11-28 12:42:54'),('player123','[\"A\", \"B\", \"C\"]','Financial Explorer','High Risk',NULL,'[\"utang\", \"tabungan_dan_dana_darurat\", \"investasi\"]',NULL,'{\r\n        \"pendapatan\": 70, \r\n        \"anggaran\": 60, \r\n        \"tabungan_dan_dana_darurat\": 40, \r\n        \"utang\": 30, \r\n        \"investasi\": 25, \r\n        \"asuransi_dan_proteksi\": 50, \r\n        \"tujuan_jangka_panjang\": 45\r\n    }',NULL,NULL,0.75,NULL,NULL,NULL,'2025-11-30 06:46:46','{\"critical\": 0.30, \"high\": 0.50, \"medium\": 0.70}',NULL,'2025-11-27 02:53:33','2025-11-27 02:53:33');
/*!40000 ALTER TABLE `playerprofile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `players` (
  `PlayerId` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `avatar_url` text,
  `character_id` int DEFAULT '1',
  `gamesPlayed` int DEFAULT '0',
  `initial_platform` varchar(20) DEFAULT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PlayerId`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `players_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES ('player_dev',6,'Developer',NULL,1,0,NULL,NULL,'2025-11-28 11:26:08','2025-11-28 11:26:08'),('player_dsxOKbEg',5,'Mona Tester','https://ui-avatars.com/api/?name=Mona+Tester',1,0,'Android','id_ID','2025-11-28 03:00:11','2025-11-28 04:03:38'),('player_QYcxnDRB',4,'Mona Lisa Test','https://via.placeholder.com/150',1,0,'Android','id_ID','2025-11-27 07:35:35','2025-11-28 04:03:38'),('player_zEGwhs9e',7,'Tester Postman','https://api.dicebear.com/7.x/adventurer/svg?seed=Char_2',2,0,'Android','id_ID','2025-11-28 11:36:38','2025-11-28 11:36:38'),('player123',1,'Mona Lisa','https://ui-avatars.com/api/?name=Mona',1,5,'Android','id_ID','2025-11-27 02:53:33','2025-11-28 04:03:38');
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiling_answers`
--

DROP TABLE IF EXISTS `profiling_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiling_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` varchar(50) NOT NULL,
  `question_id` int NOT NULL,
  `answer` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `profiling_answers_ibfk_1` (`player_id`),
  CONSTRAINT `profiling_answers_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`PlayerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiling_answers`
--

LOCK TABLES `profiling_answers` WRITE;
/*!40000 ALTER TABLE `profiling_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiling_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiling_inputs`
--

DROP TABLE IF EXISTS `profiling_inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiling_inputs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` varchar(50) DEFAULT NULL,
  `feature` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `profiling_inputs_chk_1` CHECK (json_valid(`feature`))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiling_inputs`
--

LOCK TABLES `profiling_inputs` WRITE;
/*!40000 ALTER TABLE `profiling_inputs` DISABLE KEYS */;
INSERT INTO `profiling_inputs` VALUES (1,'player_zEGwhs9e','{\r\n        \"pendapatan\": 60,\r\n        \"anggaran\": 40,\r\n        \"tabungan_dan_dana_darurat\": 30,\r\n        \"utang\": 80,\r\n        \"investasi\": 20,\r\n        \"asuransi_dan_proteksi\": 10,\r\n        \"tujuan_jangka_panjang\": 20\r\n    }','2025-11-28 12:41:58');
/*!40000 ALTER TABLE `profiling_inputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiling_results`
--

DROP TABLE IF EXISTS `profiling_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiling_results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` varchar(50) DEFAULT NULL,
  `fuzzy_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ann_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `final_class` varchar(50) DEFAULT NULL,
  `recommended_focus` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `profiling_results_chk_1` CHECK (json_valid(`fuzzy_output`)),
  CONSTRAINT `profiling_results_chk_2` CHECK (json_valid(`ann_output`)),
  CONSTRAINT `profiling_results_chk_3` CHECK (json_valid(`recommended_focus`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiling_results`
--

LOCK TABLES `profiling_results` WRITE;
/*!40000 ALTER TABLE `profiling_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiling_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_cards`
--

DROP TABLE IF EXISTS `quiz_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_cards` (
  `id` varchar(50) NOT NULL,
  `question` text NOT NULL,
  `correctOption` char(1) NOT NULL,
  `correctScore` int NOT NULL,
  `incorrectScore` int NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `difficulty` int DEFAULT NULL,
  `learning_objective` text,
  `weak_area_relevance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `cluster_relevance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `historical_success_rate` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `quiz_cards_chk_1` CHECK (json_valid(`tags`)),
  CONSTRAINT `quiz_cards_chk_2` CHECK (json_valid(`weak_area_relevance`)),
  CONSTRAINT `quiz_cards_chk_3` CHECK (json_valid(`cluster_relevance`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_cards`
--

LOCK TABLES `quiz_cards` WRITE;
/*!40000 ALTER TABLE `quiz_cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiz_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_options`
--

DROP TABLE IF EXISTS `quiz_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quizId` varchar(50) NOT NULL,
  `optionId` char(1) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_options_ibfk_1` (`quizId`),
  CONSTRAINT `quiz_options_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quiz_cards` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_options`
--

LOCK TABLES `quiz_options` WRITE;
/*!40000 ALTER TABLE `quiz_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiz_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recommendations`
--

DROP TABLE IF EXISTS `recommendations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recommendations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` varchar(50) DEFAULT NULL,
  `recommendation` text,
  `category` varchar(50) DEFAULT NULL,
  `reason` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `peer_insight` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `components` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `path_steps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  CONSTRAINT `recommendations_chk_1` CHECK (json_valid(`reason`)),
  CONSTRAINT `recommendations_chk_2` CHECK (json_valid(`peer_insight`)),
  CONSTRAINT `recommendations_chk_3` CHECK (json_valid(`components`)),
  CONSTRAINT `recommendations_chk_4` CHECK (json_valid(`path_steps`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recommendations`
--

LOCK TABLES `recommendations` WRITE;
/*!40000 ALTER TABLE `recommendations` DISABLE KEYS */;
/*!40000 ALTER TABLE `recommendations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenario_options`
--

DROP TABLE IF EXISTS `scenario_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scenario_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `scenarioId` varchar(50) NOT NULL,
  `optionId` char(1) NOT NULL,
  `text` text NOT NULL,
  `scoreChange` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `response` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `scenario_options_ibfk_1` (`scenarioId`),
  CONSTRAINT `scenario_options_ibfk_1` FOREIGN KEY (`scenarioId`) REFERENCES `scenarios` (`id`),
  CONSTRAINT `scenario_options_chk_1` CHECK (json_valid(`scoreChange`))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenario_options`
--

LOCK TABLES `scenario_options` WRITE;
/*!40000 ALTER TABLE `scenario_options` DISABLE KEYS */;
INSERT INTO `scenario_options` VALUES (1,'sc_utang_01','A','Langsung pinjam untuk belanja','{\"utang\": -10}','Bahaya! Bunga tinggi menanti.',0),(2,'sc_utang_01','B','Cek legalitas OJK dulu','{\"utang\": 5, \"literasi\": 5}','Bagus! Selalu waspada.',1),(3,'sc_saving_01','A','Pakai Dana Darurat','{\"tabungan\": -5}','Tepat! Itulah gunanya dana darurat.',1),(4,'sc_saving_01','B','Gesek Kartu Kredit','{\"utang\": -10}','Jangan tambah utang jika ada tabungan.',0),(5,'sc_asuransi_01','A','Membeli asuransi kendaraan TLO atau All Risk','{\"asuransi_dan_proteksi\": 10, \"pengeluaran\": -2}','Tepat! Risiko kehilangan aset kini dialihkan ke asuransi.',1),(6,'sc_asuransi_01','B','Tidak membeli asuransi agar hemat uang','{\"asuransi_dan_proteksi\": -10, \"tabungan\": 5}','Berbahaya. Jika motor hilang, Anda rugi total.',0),(7,'sc_investasi_01','A','Reksadana Pasar Uang','{\"investasi\": 12, \"risiko\": -5}','Pilihan cerdas! Risiko rendah dan likuid, cocok untuk pemula.',1),(8,'sc_investasi_01','B','Saham Gorengan / Crypto Viral','{\"investasi\": -5, \"risiko\": 20}','Terlalu berisiko! Anda bisa kehilangan modal dengan cepat.',0);
/*!40000 ALTER TABLE `scenario_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenarios`
--

DROP TABLE IF EXISTS `scenarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scenarios` (
  `id` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `question` text NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `difficulty` int DEFAULT NULL,
  `expected_benefit` int DEFAULT '0',
  `learning_objective` text,
  `weak_area_relevance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `cluster_relevance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `historical_success_rate` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `scenarios_chk_1` CHECK (json_valid(`tags`)),
  CONSTRAINT `scenarios_chk_2` CHECK (json_valid(`weak_area_relevance`)),
  CONSTRAINT `scenarios_chk_3` CHECK (json_valid(`cluster_relevance`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenarios`
--

LOCK TABLES `scenarios` WRITE;
/*!40000 ALTER TABLE `scenarios` DISABLE KEYS */;
INSERT INTO `scenarios` VALUES ('sc_asuransi_01','asuransi_dan_proteksi','Perlindungan Aset','Anda baru saja membeli motor baru untuk bekerja. Apa langkah finansial terbaik untuk melindungi aset ini?','[\"asuransi\", \"aset\"]',30,10,'Memahami pentingnya asuransi kendaraan',NULL,NULL,NULL,'2025-11-30 07:01:43'),('sc_investasi_01','investasi','Pilihan Investasi Pemula','Anda memiliki uang dingin Rp 1.000.000 yang tidak terpakai. Instrumen investasi apa yang paling tepat untuk mulai belajar?','[\"investasi\", \"pemula\"]',40,12,'Mengenal instrumen investasi risiko rendah',NULL,NULL,NULL,'2025-11-30 07:06:26'),('sc_saving_01','tabungan_dan_dana_darurat','Motor Mogok','Motor rusak butuh 1 juta. Pakai uang apa?','[\"emergency\", \"saving\"]',45,8,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33'),('sc_utang_01','utang','Jeratan Pinjol','Anda tergoda iklan pinjol cair cepat. Apa tindakan Anda?','[\"risk\", \"debt\"]',40,10,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33');
/*!40000 ALTER TABLE `scenarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telemetry`
--

DROP TABLE IF EXISTS `telemetry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telemetry` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessionId` varchar(50) NOT NULL,
  `playerId` varchar(50) NOT NULL,
  `turn_id` varchar(50) NOT NULL,
  `tile_id` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `telemetry_ibfk_1` (`sessionId`),
  KEY `telemetry_ibfk_2` (`playerId`),
  CONSTRAINT `telemetry_ibfk_1` FOREIGN KEY (`sessionId`) REFERENCES `game_sessions` (`sessionId`),
  CONSTRAINT `telemetry_ibfk_2` FOREIGN KEY (`playerId`) REFERENCES `players` (`PlayerId`),
  CONSTRAINT `telemetry_chk_1` CHECK (json_valid(`metadata`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telemetry`
--

LOCK TABLES `telemetry` WRITE;
/*!40000 ALTER TABLE `telemetry` DISABLE KEYS */;
/*!40000 ALTER TABLE `telemetry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turns`
--

DROP TABLE IF EXISTS `turns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turns` (
  `turn_id` varchar(50) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `player_id` varchar(50) NOT NULL,
  `turn_number` int NOT NULL,
  `ended_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`turn_id`),
  KEY `turns_ibfk_1` (`session_id`),
  KEY `turns_ibfk_2` (`player_id`),
  CONSTRAINT `turns_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `game_sessions` (`sessionId`),
  CONSTRAINT `turns_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `players` (`PlayerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turns`
--

LOCK TABLES `turns` WRITE;
/*!40000 ALTER TABLE `turns` DISABLE KEYS */;
/*!40000 ALTER TABLE `turns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'defaultdb'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 17:58:27
