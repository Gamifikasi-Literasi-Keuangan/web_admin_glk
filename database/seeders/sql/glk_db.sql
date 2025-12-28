-- =====================================================
-- SEEDER DATA - Gamifikasi Literasi Keuangan
-- =====================================================
-- File ini berisi data INSERT untuk seeding database
-- Pastikan migrations sudah dijalankan sebelum execute file ini
--
-- Usage:
--   php artisan db:seed --class=SqlSeeder
--   atau import manual via MySQL client
-- =====================================================

--
-- Host: mysql-glk-ahmadriyadhalmaliki-9dda.g.aivencloud.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.0.35
--
--
--
--
--
--
LOCK TABLES `ai_feedback_loop` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `ai_models` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `auth_tokens` WRITE;
INSERT INTO `auth_tokens` VALUES ('8ATge0oaE4cyRF2aFD68AR6s2IDlCFLW8NebhDNfyAlneF9yTFLCu7hWInUZ','refresh',7,'2025-12-29 06:43:55','2025-11-29 06:43:55'),('bgmLse4vIIn3TKQ7CJxhKs1GnEhdqyte3lRx89odCnJt1X8lP687PQFWeCLm','refresh',7,'2025-12-28 11:36:39','2025-11-28 11:36:39'),('Czl3D7gsDMbkCrXK9fopTiQpOKDYkA66lAeNjfkBiAq2fKBAOPGT5L4VH419','refresh',4,'2025-12-27 07:35:35','2025-11-27 07:35:35'),('EYyMqkiDo3wuD1YtG1ihJOCU6orYbdTFp1ZZQc80LkjO5bB9ET2QERETqhzy','refresh',7,'2025-12-29 08:42:29','2025-11-29 08:42:29'),('FETp9uW5KNrKLOxli0kkZ2avo2sbvPIRz9dKwFbO4E0pfGL5bckaM05G7eej','refresh',7,'2025-12-29 02:56:10','2025-11-29 02:56:10'),('FmKAfigCfgEUklbyV0kfraO6ae4HhG7469hwG7OJmTGAGdgCHaanLgGHdSkv','refresh',5,'2025-12-28 03:01:34','2025-11-28 03:01:34'),('gjljPZ5Ym7xQ340RMaQtAKS4YbGDsSJHI02uuaK0YwRCqrwWH7rVPD5tlz0T','refresh',7,'2025-12-28 15:03:36','2025-11-28 15:03:36'),('Ljr5puKn2fgd0kbTwquWXgSBUcj4ZcBhOexYAJiUeaMTogdefQN3EfsqU56W','refresh',7,'2025-12-28 12:42:23','2025-11-28 12:42:23'),('pBSmNTVHfTR7qCUCwVnTWBK844VwNhnAYrE8dmZ8kRHPqtZLtyWkKAxPIYns','refresh',7,'2025-12-29 06:26:33','2025-11-29 06:26:33'),('q8MDU0TgwyS5hZeHqDm8rVPDwQREbdgN9cxfBswsRyRhvhoE2CgQIiEU8WG0','refresh',5,'2025-12-28 03:13:33','2025-11-28 03:13:33'),('xMXBN88bTETso1udgEnvZjy1RnY0vxSQrjqvEtpQzLPj8OyaIMnMyk4jvemd','refresh',7,'2025-12-29 02:20:35','2025-11-29 02:20:35'),('YeS1XNAVgNLnQgIsJEGLIntExDeZzZZ6xqojIudzHwfurZfeFGbFGpDWRYJv','refresh',7,'2025-12-28 14:46:40','2025-11-28 14:46:40');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `auth_users` WRITE;
INSERT INTO `auth_users` VALUES (2,'mona_lisa','google_1029384756',NULL,'player','https://ui-avatars.com/api/?name=Mona','2025-11-27 02:53:33','2025-11-27 07:28:45'),(4,'Mona Lisa Test','google_test_123',NULL,'player','https://via.placeholder.com/150','2025-11-27 07:35:35','2025-11-27 07:35:35'),(5,'Mona Tester','google_test_user_001',NULL,'player','https://ui-avatars.com/api/?name=Mona+Tester','2025-11-28 03:00:10','2025-11-28 03:00:10'),(6,'dev_tester','dev_123',NULL,'player',NULL,'2025-11-28 07:00:59','2025-11-28 07:00:59'),(7,'Tester Postman','google_id_tester_001',NULL,'player','https://ui-avatars.com/api/?name=Tester+Postman','2025-11-28 11:36:38','2025-11-28 11:36:38');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `boardtiles` WRITE;
UNLOCK TABLES;
--
--
--
--
-- LOCK TABLES `cards` WRITE;
-- INSERT INTO `cards` VALUES ('chance_01','chance','Bonus Tahunan','Selamat! Kinerja Anda bagus.',15,'add_balance','["pendapatan"]',NULL,1,15,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33','2025-11-27 02:53:33'),('risk_01','risk','Sakit Gigi','Anda harus ke dokter gigi segera.',-5,'pay_cash','["kesehatan", "pengeluaran"]',NULL,2,-5,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33','2025-11-27 02:53:33');
-- UNLOCK TABLES;
--
--
--
--
LOCK TABLES `config` WRITE;
INSERT INTO `config` VALUES (1,2,5,50,1,'2025-11-27 02:53:33','2025-11-28 04:01:02');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `game_sessions` WRITE;
INSERT INTO `game_sessions` VALUES ('game001','player123',4,50,'active','player123',1,'{\"turn_phase\":\"waiting\",\"last_dice\":0}','2025-11-27 02:53:33',NULL,'2025-11-27 02:53:33','2025-11-28 14:09:11');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `interventiontemplates` WRITE;
INSERT INTO `interventiontemplates` VALUES (1,'low','Hati-hati','Kamu mulai mengambil keputusan berisiko. Coba pertimbangkan risikonya.','[{\"id\": \"ok\", \"text\": \"Mengerti\"}]',0),(2,'moderate','Peringatan Strategi','⚠️ Kamu sudah 3x salah mengambil keputusan. Mungkin perlu review konsep dasar?','[{\"id\": \"heed\", \"text\": \"Lihat Penjelasan Singkat\"}, {\"id\": \"ignore\", \"text\": \"Lanjut Tanpa Hint\"}]',0),(3,'critical','Bahaya Finansial','🛑 Skor kamu kritis! Sistem menyarankan berhenti sejenak untuk evaluasi.','[{\"id\": \"review\", \"text\": \"Evaluasi Sekarang\"}]',1);
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `participatesin` WRITE;
INSERT INTO `participatesin` VALUES (1,'game001','player123',1,0,1000,'connected',1,NULL,'2025-11-27 02:53:33','2025-11-28 14:14:08','2025-11-28 14:14:08'),(8,'game001','player_zEGwhs9e',2,6,0,'connected',1,NULL,'2025-11-29 05:35:05','2025-11-30 05:27:57','2025-11-29 05:35:05');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `personal_access_tokens` WRITE;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',4,'game-client','e6fdbab5f28079a4075a39b0dbba3b033d12bd8ce4a4bda3331a63a04fcb97b6','[\"*\"]',NULL,NULL,'2025-11-27 07:35:35','2025-11-27 07:35:35'),(2,'App\\Models\\User',4,'access_token','ad52dbcbe4a4101d7b9116ecb59491aff0597967b7d25cb88a48b83d7516adbe','[\"*\"]',NULL,NULL,'2025-11-27 07:35:35','2025-11-27 07:35:35'),(3,'App\\Models\\User',5,'game-client','494c20b85171a43ad58ae5ee3bc19c3211a03ffcf9157246c88cdc2bb7a5c33a','[\"*\"]',NULL,NULL,'2025-11-28 03:00:13','2025-11-28 03:00:13'),(4,'App\\Models\\User',5,'game-client','c74a7b30fe308623446b9ad2ff7680fdba5131d2befd36e27f25ef9c84f0bb58','[\"*\"]',NULL,NULL,'2025-11-28 03:01:33','2025-11-28 03:01:33'),(5,'App\\Models\\User',5,'game-client','08629c171e51e871ee6fb12911ab661f1caf9a08586cda5492b4c84083f951a6','[\"*\"]',NULL,NULL,'2025-11-28 03:13:33','2025-11-28 03:13:33'),(6,'App\\Models\\User',5,'game-client','48d5e6ba325f1f0271257913bb858824758aae77aaf3098c0fa8c2174f22756d','[\"*\"]',NULL,NULL,'2025-11-28 03:13:43','2025-11-28 03:13:43'),(7,'App\\Models\\User',6,'dev-token','42b862b5c4d9d374a51608e80b9f272ad1315115f310425538a0047a0c471093','[\"*\"]',NULL,NULL,'2025-11-28 11:26:10','2025-11-28 11:26:10'),(8,'App\\Models\\User',7,'game-client','45de9a1bf7040b362afe5c8ebf209f54c824d423669ea1325a88b92e030af9bf','[\"*\"]','2025-11-28 11:41:05',NULL,'2025-11-28 11:36:39','2025-11-28 11:41:05'),(9,'App\\Models\\User',7,'game-client','1aa119b38b047305fabb1d8bd933959ad04dff12de1bdb3ed6fd2ad96f23d9bf','[\"*\"]','2025-11-28 12:34:49',NULL,'2025-11-28 11:47:11','2025-11-28 12:34:49'),(10,'App\\Models\\User',7,'game-client','1577e40636c82fd221488060c319ee632cde79ac65d708a975f54a2a141e990d','[\"*\"]','2025-11-28 12:42:54',NULL,'2025-11-28 12:42:23','2025-11-28 12:42:54'),(11,'App\\Models\\User',7,'game-client','630eb8e25e31470da5ae65c4b030ca1c7590dfe5774d9b1511a91b4505b76986','[\"*\"]',NULL,NULL,'2025-11-28 14:33:15','2025-11-28 14:33:15'),(12,'App\\Models\\User',7,'game-client','db1f0516addf94fee93cf9a13c7d75697f4ca8e272d9d40f72dbe4b696208f2a','[\"*\"]',NULL,NULL,'2025-11-28 14:40:44','2025-11-28 14:40:44'),(13,'App\\Models\\User',7,'game-client','d1db2064970a2540751eae5d0ee097a86e4cabf622b8ff415f7ff4695880a835','[\"*\"]',NULL,NULL,'2025-11-28 14:46:40','2025-11-28 14:46:40'),(14,'App\\Models\\User',7,'game-client','438da206bc560b198a1c2b0eca6bbbfcf3e0053bbd2830d6c1f5804b683343d9','[\"*\"]',NULL,NULL,'2025-11-28 15:03:36','2025-11-28 15:03:36'),(15,'App\\Models\\User',7,'game-client','eb6ef81ac6119374c2f6a5aa807939e1d8c1591c9496068d1f2750a7f1ac0717','[\"*\"]',NULL,NULL,'2025-11-28 15:09:34','2025-11-28 15:09:34'),(16,'App\\Models\\User',7,'game-client','387a8d3896b76cc141c1d7215e9a14d6ab7430cc42e11f17555d8e1a8b1b0ae9','[\"*\"]',NULL,NULL,'2025-11-29 02:18:23','2025-11-29 02:18:23'),(17,'App\\Models\\User',7,'game-client','0cb437f33306dbf0343ae12eb1d1ce4d2152bd29e2c89f1a7efb826effd94335','[\"*\"]',NULL,NULL,'2025-11-29 02:20:35','2025-11-29 02:20:35'),(18,'App\\Models\\User',7,'game-client','37603e86ff944fd8051a9a10760dd88ece28d67830aaf734b95a67248784cf4c','[\"*\"]','2025-11-29 06:49:17',NULL,'2025-11-29 02:56:10','2025-11-29 06:49:17'),(19,'App\\Models\\User',7,'game-client','d9c815d8be9305e9b20c9758318d0739caa91d11005e5e1d84ba299931744acf','[\"*\"]','2025-11-30 09:40:50',NULL,'2025-11-29 04:17:49','2025-11-30 09:40:50'),(20,'App\\Models\\User',7,'game-client','6f5fefac28f07e365a81183cf8ba83e67db7807d458fc38074481e66d761a0c4','[\"*\"]','2025-11-30 07:28:05',NULL,'2025-11-29 05:37:37','2025-11-30 07:28:05'),(21,'App\\Models\\User',7,'game-client','a4bc6a2df786cd662e3d84c3e3339764086f7d3bbb58fabc40d6570179d60e7c','[\"*\"]','2025-11-29 06:24:28',NULL,'2025-11-29 06:16:10','2025-11-29 06:24:28'),(22,'App\\Models\\User',7,'game-client','0776482775cba5c48b37dbc5e7c6bcc4a1a80e21c3cb53be4ba7305bb15f18b7','[\"*\"]',NULL,NULL,'2025-11-29 06:26:33','2025-11-29 06:26:33'),(23,'App\\Models\\User',7,'game-client','f8186333830ecedb5b0e875248d9d860852b3d2d3f31c1abc563269b952f2319','[\"*\"]','2025-11-29 06:44:24',NULL,'2025-11-29 06:34:05','2025-11-29 06:44:24'),(24,'App\\Models\\User',7,'game-client','12363f0d734454270717c8c6af7a18ca1b3a5b5eeca89abeda0f0da2fc4b49d8','[\"*\"]','2025-11-30 06:47:53',NULL,'2025-11-29 06:43:55','2025-11-30 06:47:53'),(25,'App\\Models\\User',7,'game-client','8fed9d4bc58353805546279ecc52fc259ab91c563b22c616beea029a5dc6a058','[\"*\"]','2025-11-29 08:42:42',NULL,'2025-11-29 08:42:29','2025-11-29 08:42:42'),(26,'App\\Models\\User',7,'game-client','62f71813e2c9ffc7e9256639e0cf47f8e1cc6a9f16c6fe19ba7193ab035f2a20','[\"*\"]','2025-11-30 04:55:16',NULL,'2025-11-29 08:46:57','2025-11-30 04:55:16'),(27,'App\\Models\\User',7,'game-client','0c3ffa43645579be70b113dbee7ea32df475bea35594ef45678eaeb575c915fa','[\"*\"]','2025-11-30 02:12:53',NULL,'2025-11-30 02:11:47','2025-11-30 02:12:53'),(28,'App\\Models\\User',7,'game-client','bd37be273389da9cf0d21e6f02a94d563b2369a830b5639a617493eebd2ba26d','[\"*\"]','2025-11-30 05:02:36',NULL,'2025-11-30 03:23:35','2025-11-30 05:02:36'),(29,'App\\Models\\User',7,'game-client','4602d0737acb6cbb9ae549dab42732f0c767229fd0cadbafe33a1107f8531f47','[\"*\"]','2025-11-30 07:30:25',NULL,'2025-11-30 05:13:50','2025-11-30 07:30:25'),(30,'App\\Models\\User',7,'game-client','59dffe6538a1cbb5b863d375d45afd3fb60b83a43e9d4c1983c37b8d530899a9','[\"*\"]','2025-11-30 10:04:57',NULL,'2025-11-30 09:38:35','2025-11-30 10:04:57');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `player_decisions` WRITE;
INSERT INTO `player_decisions` VALUES (7,'player_zEGwhs9e','game001',NULL,NULL,'sc_01','scenario',NULL,0,-10,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 09:49:28'),(8,'player_zEGwhs9e','game001',NULL,NULL,'sc_02','scenario',NULL,0,-10,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 09:48:28'),(9,'player_zEGwhs9e','game001',NULL,NULL,'sc_03','scenario',NULL,0,-10,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 09:47:28'),(10,'player_zEGwhs9e','game001',NULL,1,'pinjaman_teman_01','intervention_log',NULL,0,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 10:04:11'),(11,'player_zEGwhs9e','game001',NULL,1,'pinjaman_teman_01','intervention_log',NULL,0,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-30 10:04:59');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `playerprofile` WRITE;
INSERT INTO `playerprofile` VALUES ('player_dev',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.3,NULL,NULL,NULL,'2025-11-28 11:26:08','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-28 11:26:09','2025-11-28 11:26:09'),('player_dsxOKbEg',NULL,NULL,NULL,NULL,NULL,NULL,'[]',NULL,NULL,0,NULL,NULL,NULL,'2025-11-28 03:00:11','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-28 03:00:11','2025-11-28 03:00:11'),('player_QYcxnDRB',NULL,NULL,NULL,NULL,NULL,NULL,'[]',NULL,NULL,0,NULL,NULL,NULL,'2025-11-27 07:35:35','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-27 07:35:35','2025-11-27 07:35:35'),('player_zEGwhs9e','\"[\\\"A\\\",\\\"B\\\",\\\"C\\\"]\"','UNKNOWN PLAYER','Unknown','[]','[]',NULL,'{\"pendapatan\":60,\"anggaran\":40,\"tabungan_dan_dana_darurat\":30,\"utang\":80,\"investasi\":20,\"asuransi_dan_proteksi\":10,\"tujuan_jangka_panjang\":20}',NULL,NULL,0,NULL,NULL,NULL,'2025-11-30 09:40:51','{\"critical\":0.3,\"high\":0.5,\"medium\":0.7}',NULL,'2025-11-28 11:36:39','2025-11-28 12:42:54'),('player123','[\"A\", \"B\", \"C\"]','Financial Explorer','High Risk',NULL,'[\"utang\", \"tabungan_dan_dana_darurat\", \"investasi\"]',NULL,'{\r\n        \"pendapatan\": 70, \r\n        \"anggaran\": 60, \r\n        \"tabungan_dan_dana_darurat\": 40, \r\n        \"utang\": 30, \r\n        \"investasi\": 25, \r\n        \"asuransi_dan_proteksi\": 50, \r\n        \"tujuan_jangka_panjang\": 45\r\n    }',NULL,NULL,0.75,NULL,NULL,NULL,'2025-11-30 06:46:46','{\"critical\": 0.30, \"high\": 0.50, \"medium\": 0.70}',NULL,'2025-11-27 02:53:33','2025-11-27 02:53:33');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `players` WRITE;
INSERT INTO `players` VALUES ('player_dev',6,'Developer',NULL,1,0,NULL,NULL,'2025-11-28 11:26:08','2025-11-28 11:26:08'),('player_dsxOKbEg',5,'Mona Tester','https://ui-avatars.com/api/?name=Mona+Tester',1,0,'Android','id_ID','2025-11-28 03:00:11','2025-11-28 04:03:38'),('player_QYcxnDRB',4,'Mona Lisa Test','https://via.placeholder.com/150',1,0,'Android','id_ID','2025-11-27 07:35:35','2025-11-28 04:03:38'),('player_zEGwhs9e',7,'Tester Postman','https://api.dicebear.com/7.x/adventurer/svg?seed=Char_2',2,0,'Android','id_ID','2025-11-28 11:36:38','2025-11-28 11:36:38'),('player123',1,'Mona Lisa','https://ui-avatars.com/api/?name=Mona',1,5,'Android','id_ID','2025-11-27 02:53:33','2025-11-28 04:03:38');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `profiling_answers` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `profiling_inputs` WRITE;
INSERT INTO `profiling_inputs` VALUES (1,'player_zEGwhs9e','{\r\n        "pendapatan": 60,\r\n        "anggaran": 40,\r\n        "tabungan_dan_dana_darurat": 30,\r\n        "utang": 80,\r\n        "investasi": 20,\r\n        "asuransi_dan_proteksi": 10,\r\n        "tujuan_jangka_panjang": 20\r\n    }','2025-11-28 12:41:58','2025-11-28 12:41:58');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `profiling_results` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `quiz_cards` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `quiz_options` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `recommendations` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `scenario_options` WRITE;
-- INSERT INTO `scenario_options` VALUES (1,'sc_utang_01','A','Langsung pinjam untuk belanja','{\"utang\": -10}','Bahaya! Bunga tinggi menanti.',0),(2,'sc_utang_01','B','Cek legalitas OJK dulu','{\"utang\": 5, \"literasi\": 5}','Bagus! Selalu waspada.',1),(3,'sc_saving_01','A','Pakai Dana Darurat','{\"tabungan\": -5}','Tepat! Itulah gunanya dana darurat.',1),(4,'sc_saving_01','B','Gesek Kartu Kredit','{\"utang\": -10}','Jangan tambah utang jika ada tabungan.',0),(5,'sc_asuransi_01','A','Membeli asuransi kendaraan TLO atau All Risk','{\"asuransi_dan_proteksi\": 10, \"pengeluaran\": -2}','Tepat! Risiko kehilangan aset kini dialihkan ke asuransi.',1),(6,'sc_asuransi_01','B','Tidak membeli asuransi agar hemat uang','{\"asuransi_dan_proteksi\": -10, \"tabungan\": 5}','Berbahaya. Jika motor hilang, Anda rugi total.',0),(7,'sc_investasi_01','A','Reksadana Pasar Uang','{\"investasi\": 12, \"risiko\": -5}','Pilihan cerdas! Risiko rendah dan likuid, cocok untuk pemula.',1),(8,'sc_investasi_01','B','Saham Gorengan / Crypto Viral','{\"investasi\": -5, \"risiko\": 20}','Terlalu berisiko! Anda bisa kehilangan modal dengan cepat.',0);
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `scenarios` WRITE;
INSERT INTO `scenarios` VALUES ('sc_asuransi_01','asuransi_dan_proteksi','Perlindungan Aset','Anda baru saja membeli motor baru untuk bekerja. Apa langkah finansial terbaik untuk melindungi aset ini?','["asuransi", "aset"]',30,10,'Memahami pentingnya asuransi kendaraan',NULL,NULL,NULL,'2025-11-30 07:01:43','2025-11-30 07:01:43'),('sc_investasi_01','investasi','Pilihan Investasi Pemula','Anda memiliki uang dingin Rp 1.000.000 yang tidak terpakai. Instrumen investasi apa yang paling tepat untuk mulai belajar?','["investasi", "pemula"]',40,12,'Mengenal instrumen investasi risiko rendah',NULL,NULL,NULL,'2025-11-30 07:06:26','2025-11-30 07:06:26'),('sc_saving_01','tabungan_dan_dana_darurat','Motor Mogok','Motor rusak butuh 1 juta. Pakai uang apa?','["emergency", "saving"]',45,8,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33','2025-11-27 02:53:33'),('sc_utang_01','utang','Jeratan Pinjol','Anda tergoda iklan pinjol cair cepat. Apa tindakan Anda?','["risk", "debt"]',40,10,NULL,NULL,NULL,NULL,'2025-11-27 02:53:33','2025-11-27 02:53:33');
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `sessions` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `telemetry` WRITE;
UNLOCK TABLES;
--
--
--
--
LOCK TABLES `turns` WRITE;
UNLOCK TABLES;
--
-- Dumping routines for database 'defaultdb'
--
-- Dump completed on 2025-11-30 17:58:27