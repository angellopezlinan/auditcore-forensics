SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- activity_log
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_log` VALUES
(1,'default','updated','App\\Models\\User','updated',1,NULL,NULL,'{\"attributes\":{\"name\":\"Auditor Test\"},\"old\":{\"name\":\"Jefe Policia\"}}',NULL,'2026-03-16 12:15:57','2026-03-16 12:15:57'),
(2,'default','updated','App\\Models\\User','updated',1,NULL,NULL,'{\"attributes\":{\"name\":\"Auditor AESA\"},\"old\":{\"name\":\"Auditor Test\"}}',NULL,'2026-03-16 12:16:12','2026-03-16 12:16:12'),
(3,'default','updated','App\\Models\\User','updated',1,NULL,NULL,'{\"attributes\":{\"name\":\"Audit View Test\",\"updated_at\":\"2026-03-16T12:28:17.000000Z\"},\"old\":{\"name\":\"Auditor AESA\",\"updated_at\":\"2026-03-16T12:16:12.000000Z\"}}',NULL,'2026-03-16 12:28:17','2026-03-16 12:28:17'),
(4,'default','updated','App\\Models\\User','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Jefe de Polic\\u00eda\",\"updated_at\":\"2026-03-17T08:44:33.000000Z\"},\"old\":{\"name\":\"Audit View Test\",\"updated_at\":\"2026-03-16T12:28:17.000000Z\"}}',NULL,'2026-03-17 08:44:33','2026-03-17 08:44:33'),
(5,'default','updated','App\\Models\\User','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"password\":\"$2y$12$or7AcpVuqSiJdv3J0Ywdre242CR1.eFkyd9e\\/qwnwgFMT27O\\/NWIm\",\"updated_at\":\"2026-03-17T08:55:11.000000Z\"},\"old\":{\"password\":\"$2y$12$1M95WG8nM76Ck7eGGrSTMOh6p0GUvOY2y.M52WQxTAQqblv0HgMgi\",\"updated_at\":\"2026-03-17T08:44:33.000000Z\"}}',NULL,'2026-03-17 08:55:11','2026-03-17 08:55:11'),
(6,'default','updated','App\\Models\\User','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"password\":\"$2y$12$LAZSXf0vrjqUflIX6DRkcOznUt4.sZ44WqqhPLAty6ThjKchSuB3G\",\"updated_at\":\"2026-03-17T09:04:20.000000Z\"},\"old\":{\"password\":\"$2y$12$or7AcpVuqSiJdv3J0Ywdre242CR1.eFkyd9e\\/qwnwgFMT27O\\/NWIm\",\"updated_at\":\"2026-03-17T08:55:11.000000Z\"}}',NULL,'2026-03-17 09:04:20','2026-03-17 09:04:20'),
(7,'default','updated','App\\Models\\User','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"two_factor_secret\":\"eyJpdiI6IkVGelkvQlQ3bXlmbDFYdkZYRWVNWlE9PSIsInZhbHVlIjoiNHNlUzFWSjFjZ3JYeXNld3lSRkd4amhYSFpScm93V3ZXSDJsaCs0SEpzMD0iLCJtYWMiOiJhMmM3NDhjNTc2YThmODQxNTNlODU4ZmI5YTA0ZGFiZTJjODRkMjAyNzdmMDBmNzFhNjI2MDRkZjc4MjE5OGMzIiwidGFnIjoiIn0=\",\"two_factor_recovery_codes\":\"eyJpdiI6IkJ0SDBWQ1kzS0tqNm0zNGtIRHlMZFE9PSIsInZhbHVlIjoiQ0RibjF3N2x3STZNWnR0aXdoTWw3ak5OUk0zVlpjZk1kTE1VRGt4aTE2NXZXUkhYTlRZMEJBdnZSb1JQbXNJNGJKeStlaGlWeGNMeWJwSVpjazhoNFMzVXM1dTdPV095bldORUpXNnVjOTE1R1J5Y1VUYnlLUlVNRUZueVBxYnZzenp2cklDVGxScy9zMGVaUTZyVDdoTXplcU5sc2JDNkE2VVorZHQ1RkV2SjNTUGxVcDRXOU50ZXM2ZWovdzNlZVMvckVMaGo2Tnl2VUI3UDVlWDg5TGtLV3JzcmRoaVZ5NDRyaWM3UEZWVjlyRFQ3QjZqRE0zczVEM2lSc1ZDVDQrTHBYM3VzNTZ4UEFQUDNWVTRZOVE9PSIsIm1hYyI6IjMzYWJkZDJhNmJmMzE5ZGI3MDlhN2JjNzdlMDY4YWFmYzlhMTc5MzU4MGIzMTJkNjNmN2ZlMDM2OGZmNmE3MWIiLCJ0YWciOiIifQ==\",\"updated_at\":\"2026-03-17T09:19:30.000000Z\"},\"old\":{\"two_factor_secret\":null,\"two_factor_recovery_codes\":null,\"updated_at\":\"2026-03-17T09:04:20.000000Z\"}}',NULL,'2026-03-17 09:19:30','2026-03-17 09:19:30'),
(8,'default','updated','App\\Models\\User','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"two_factor_confirmed_at\":\"2026-03-17 09:20:00\",\"updated_at\":\"2026-03-17T09:20:00.000000Z\"},\"old\":{\"two_factor_confirmed_at\":null,\"updated_at\":\"2026-03-17T09:19:30.000000Z\"}}',NULL,'2026-03-17 09:20:00','2026-03-17 09:20:00'),
(9,'default','updated','App\\Models\\User','updated',2,'App\\Models\\User',2,'{\"attributes\":{\"two_factor_secret\":\"eyJpdiI6IlR2aXpLVVhWcmIzeU1aMjhScVU0Nnc9PSIsInZhbHVlIjoiSjEvS05tbmF0SGNBRHRjZlNUZmw5THB2cDdmbkJjNUtYbDJBZWhJTFUyaz0iLCJtYWMiOiI5MzYxNTJkY2JmZGE0YTZkZDRhZjRiMWRhNTgwYTQyZjczZWI2YzUyMWNiMTJkNzlkOTkyNjM3YmI1NjFkNWVkIiwidGFnIjoiIn0=\",\"two_factor_recovery_codes\":\"eyJpdiI6ImJBYlZzYWYyQ290bDBadkpxWW9VVEE9PSIsInZhbHVlIjoiN05IT2VFN3dxcWtLTk5mczRiUExnMTdoRS85NlVaVjN1R1dCM0lob0F3ajV4NVp0NGZNaU9URTRDVjh3ektHY3h1cUw0QlF5VXBnRU9pbGV5WWNwOWF4SmZlYmpzYjBzZEwrOTFFWHJSeWRja01neG91SGs3eDd4N0RvUW9aQTdTR1lvYXlPMWZJemhXaTNMQ0N3Zk05aWFVMElDMmdFWXVYdTJzY2M5MlVLQU9NVHNCeUlXQkNyMXNCS2Q2Z2lFUVJyZkFob0hxSXRoN1FUQXlGS2hyNEFrcS9BVTVuQ1RKUFF4eTBNdkQwMzFKNTI2Tms3dzlCS21EZk1VeGVmSnRNNFJxNEhtc0RCTFhRUTBYYU9NTWc9PSIsIm1hYyI6IjBkZDRkMDc5ZmNmNGM1NmJhZjBlODQwNGZmZGFlYmU0NDRjNmE5ODVhNDQ2OWMwZjkwYTQxMDg5ZGE3ZmQyOTkiLCJ0YWciOiIifQ==\",\"updated_at\":\"2026-03-17T09:38:33.000000Z\"},\"old\":{\"two_factor_secret\":null,\"two_factor_recovery_codes\":null,\"updated_at\":\"2026-03-10T09:36:49.000000Z\"}}',NULL,'2026-03-17 09:38:33','2026-03-17 09:38:33'),
(10,'default','updated','App\\Models\\User','updated',2,'App\\Models\\User',2,'{\"attributes\":{\"two_factor_secret\":null,\"two_factor_recovery_codes\":null,\"updated_at\":\"2026-03-17T09:39:04.000000Z\"},\"old\":{\"two_factor_secret\":\"eyJpdiI6IlR2aXpLVVhWcmIzeU1aMjhScVU0Nnc9PSIsInZhbHVlIjoiSjEvS05tbmF0SGNBRHRjZlNUZmw5THB2cDdmbkJjNUtYbDJBZWhJTFUyaz0iLCJtYWMiOiI5MzYxNTJkY2JmZGE0YTZkZDRhZjRiMWRhNTgwYTQyZjczZWI2YzUyMWNiMTJkNzlkOTkyNjM3YmI1NjFkNWVkIiwidGFnIjoiIn0=\",\"two_factor_recovery_codes\":\"eyJpdiI6ImJBYlZzYWYyQ290bDBadkpxWW9VVEE9PSIsInZhbHVlIjoiN05IT2VFN3dxcWtLTk5mczRiUExnMTdoRS85NlVaVjN1R1dCM0lob0F3ajV4NVp0NGZNaU9URTRDVjh3ektHY3h1cUw0QlF5VXBnRU9pbGV5WWNwOWF4SmZlYmpzYjBzZEwrOTFFWHJSeWRja01neG91SGs3eDd4N0RvUW9aQTdTR1lvYXlPMWZJemhXaTNMQ0N3Zk05aWFVMElDMmdFWXVYdTJzY2M5MlVLQU9NVHNCeUlXQkNyMXNCS2Q2Z2lFUVJyZkFob0hxSXRoN1FUQXlGS2hyNEFrcS9BVTVuQ1RKUFF4eTBNdkQwMzFKNTI2Tms3dzlCS21EZk1VeGVmSnRNNFJxNEhtc0RCTFhRUTBYYU9NTWc9PSIsIm1hYyI6IjBkZDRkMDc5ZmNmNGM1NmJhZjBlODQwNGZmZGFlYmU0NDRjNmE5ODVhNDQ2OWMwZjkwYTQxMDg5ZGE3ZmQyOTkiLCJ0YWciOiIifQ==\",\"updated_at\":\"2026-03-17T09:38:33.000000Z\"}}',NULL,'2026-03-17 09:39:04','2026-03-17 09:39:04');

-- cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
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

-- job_batches
DROP TABLE IF EXISTS `job_batches`;
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

-- jobs
DROP TABLE IF EXISTS `jobs`;
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

-- migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_02_23_093851_create_organizations_table',1),
(5,'2026_02_23_093901_create_drones_table',1),
(6,'2026_02_25_104440_create_personal_access_tokens_table',1),
(7,'2026_02_25_115214_create_flights_table',1),
(8,'2026_02_26_080049_create_pilots_table',1),
(9,'2026_02_26_080818_add_organization_id_to_users_table',1),
(10,'2026_02_26_101832_add_enaire_compliance_to_flights_table',1),
(11,'2026_02_26_104238_add_operator_fields_to_organizations_table',1),
(12,'2026_02_26_104543_create_permission_tables',1),
(13,'2026_02_26_115347_add_advanced_fields_to_drones_table',1),
(14,'2026_02_26_120451_create_licenses_table',1),
(15,'2026_02_27_075538_add_planning_fields_to_flights_table',1),
(16,'2026_02_27_083242_add_enaire_intelligence_to_flights_table',1),
(17,'2026_02_27_093306_make_pilot_name_nullable_in_flights_table',1),
(18,'2026_02_27_093700_make_location_nullable_in_flights_table',1),
(19,'2026_02_27_122508_add_role_to_users_table',1),
(20,'2026_03_03_073752_add_status_to_drones_table',1),
(21,'2026_03_03_073753_create_maintenances_table',1),
(22,'2026_03_03_083631_create_teams_table',1),
(23,'2026_03_03_083632_create_team_user_table',1),
(24,'2026_03_03_083633_add_team_id_to_resources_tables',1),
(25,'2026_03_04_125249_add_mo_compliance_fields_to_users_table',2),
(26,'2026_03_04_125532_add_issue_date_to_licenses_table',3),
(27,'2026_03_05_115846_add_aesa_compliance_fields_to_drones_table',4),
(28,'2026_03_05_121909_create_batteries_table',5),
(29,'2026_03_05_124936_add_aesa_fields_to_flights_table',6),
(30,'2026_03_09_075508_create_operator_documents_table',7),
(31,'2026_03_09_082502_drop_organizations_and_pilots_tables',8),
(32,'2026_03_09_082503_add_team_id_to_batteries_and_licenses_tables',8),
(33,'2026_03_09_103003_add_safety_checklist_to_flights_table',9),
(34,'2026_03_09_110023_make_flight_execution_fields_nullable',10),
(35,'2026_03_09_124415_add_is_active_to_operator_documents_table',11),
(36,'2026_03_10_092646_add_team_id_to_licenses_table',12),
(37,'2026_03_10_114814_create_incidents_table',13),
(38,'2026_03_10_124020_add_operation_name_to_flights_table',14),
(39,'2026_03_10_125631_force_add_operation_name_to_flights',15),
(40,'2026_03_16_114612_normalize_aesa_enums_values',16),
(41,'2026_03_16_121535_create_activity_log_table',17),
(42,'2026_03_16_121536_add_event_column_to_activity_log_table',17),
(43,'2026_03_16_121537_add_batch_uuid_column_to_activity_log_table',17),
(44,'2026_03_17_082436_add_two_factor_authentication_columns',18);

-- password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- sessions
DROP TABLE IF EXISTS `sessions`;
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

INSERT INTO `sessions` VALUES
('1n0t6ESYkZGG4BJCz2ZDpk7z18YdQyDVWf4Bi8BV',1,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTnZnS1hIcm1SSXM0aGFnSkJVOTBaMXY2dWU4ekRKcGpHQ05DV0hreiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4vMyI7czo1OiJyb3V0ZSI7czozMDoiZmlsYW1lbnQuYWRtaW4ucGFnZXMuZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiOGY4NzJhZDgyY2QwODY0MWIyNWEyYmViZTI4NDc1ZGQzNzdmOGNhYzQ2NzUyYTg0ZTk5MWQxY2EyZDI4NzZhMyI7czoyODoibG9naW5fMmZhX2NoYWxsZW5nZV9wYXNzZWRfMSI7czo2MDoiJDJ5JDEyJEM2OHNScDBaVll0UXRwdW5XWUpxcU9KNjFjVGp4ZjZ4Yk1FaTFJQWZSUVQzdWJIdlNTZ0dLIjtzOjg6ImZpbGFtZW50IjthOjA6e319',1773750577);

-- teams
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `teams` VALUES
(1,'Policía Local de Córdoba (UAS Unit)','2026-03-03 09:45:03','2026-03-03 09:45:03'),
(2,'Ayuntamiento de Sevilla','2026-03-03 10:03:19','2026-03-03 10:03:19'),
(3,'AICOR UAS','2026-03-03 10:09:16','2026-03-04 12:33:41');

-- users (sin FK a organizations ya que esa tabla fue eliminada en migraciones)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `mo_read_at` timestamp NULL DEFAULT NULL,
  `mo_version` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'piloto',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES
(1,'Jefe de Policía','jefe@cordoba.es',NULL,'$2y$12$LAZSXf0vrjqUflIX6DRkcOznUt4.sZ44WqqhPLAty6ThjKchSuB3G','eyJpdiI6IkVGelkvQlQ3bXlmbDFYdkZYRWVNWlE9PSIsInZhbHVlIjoiNHNlUzFWSjFjZ3JYeXNld3lSRkd4amhYSFpScm93V3ZXSDJsaCs0SEpzMD0iLCJtYWMiOiJhMmM3NDhjNTc2YThmODQxNTNlODU4ZmI5YTA0ZGFiZTJjODRkMjAyNzdmMDBmNzFhNjI2MDRkZjc4MjE5OGMzIiwidGFnIjoiIn0=','eyJpdiI6IkJ0SDBWQ1kzS0tqNm0zNGtIRHlMZFE9PSIsInZhbHVlIjoiQ0RibjF3N2x3STZNWnR0aXdoTWw3ak5OUk0zVlpjZk1kTE1VRGt4aTE2NXZXUkhYTlRZMEJBdnZSb1JQbXNJNGJKeStlaGlWeGNMeWJwSVpjazhoNFMzVXM1dTdPV095bldORUpXNnVjOTE1R1J5Y1VUYnlLUlVNRUZueVBxYnZzenp2cklDVGxScy9zMGVaUTZyVDdoTXplcU5sc2JDNkE2VVorZHQ1RkV2SjNTUGxVcDRXOU50ZXM2ZWovdzNlZVMvckVMaGo2Tnl2VUI3UDVlWDg5TGtLV3JzcmRoaVZ5NDRyaWM3UEZWVjlyRFQ3QjZqRE0zczVEM2lSc1ZDVDQrTHBYM3VzNTZ4UEFQUDNWVTRZOVE9PSIsIm1hYyI6IjMzYWJkZDJhNmJmMzE5ZGI3MDlhN2JjNzdlMDY4YWFmYzlhMTc5MzU4MGIzMTJkNjNmN2ZlMDM2OGZmNmE3MWIiLCJ0YWciOiIifQ==','2026-03-17 09:20:00',NULL,NULL,NULL,'2026-03-03 09:45:03','2026-03-17 09:20:00',1,'piloto'),
(2,'Agente Piloto 01','piloto1@cordoba.es',NULL,'$2y$12$.EvC8t6z.h8T9WnTFMhl9eGT6F.W4QUp./udtFPW9HH7Ei9Zk.wGW',NULL,NULL,NULL,NULL,'2026-03-10 09:36:49',NULL,'2026-03-03 09:45:03','2026-03-17 09:39:04',1,'piloto'),
(3,'funcionario1 sevilla','funcionario1@sevilla.es',NULL,'$2y$12$T4wmQQtJ4kl8k/EN7gzHrOUZk0UdFqhZdKxqMdSuSxNI7FZpEhtHS',NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-03 12:12:02','2026-03-03 12:16:44',1,'piloto'),
(4,'David Lopez','davi@cordoba.es',NULL,'$2y$12$oDUn3ENubilvh/JbWZ6.NeRUSjSaN1vOQZhtQF9DjJ8t.4.wmPibm',NULL,NULL,NULL,NULL,'2026-03-16 12:14:00',NULL,'2026-03-16 12:14:00','2026-03-16 12:14:00',NULL,'piloto');

-- permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` VALUES
(1,'create-flights','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(2,'view-own-flights','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(3,'view-all-flights','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(4,'manage-drones','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(5,'manage-pilots','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(6,'manage-operator-data','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(7,'view-orator-reports','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(8,'view_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(9,'view_any_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(10,'create_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(11,'update_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(12,'restore_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(13,'restore_any_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(14,'replicate_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(15,'reorder_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(16,'delete_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(17,'delete_any_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(18,'force_delete_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(19,'force_delete_any_drone','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(20,'view_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(21,'view_any_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(22,'create_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(23,'update_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(24,'restore_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(25,'restore_any_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(26,'replicate_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(27,'reorder_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(28,'delete_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(29,'delete_any_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(30,'force_delete_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(31,'force_delete_any_flight','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(32,'view_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(33,'view_any_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(34,'create_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(35,'update_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(36,'restore_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(37,'restore_any_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(38,'replicate_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(39,'reorder_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(40,'delete_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(41,'delete_any_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(42,'force_delete_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(43,'force_delete_any_maintenance','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(44,'view_role','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(45,'view_any_role','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(46,'create_role','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(47,'update_role','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(48,'delete_role','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(49,'delete_any_role','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(50,'view_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(51,'view_any_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(52,'create_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(53,'update_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(54,'restore_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(55,'restore_any_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(56,'replicate_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(57,'reorder_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(58,'delete_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(59,'delete_any_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(60,'force_delete_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(61,'force_delete_any_user','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(62,'widget_LatestFlights','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(63,'widget_StatsOverview','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(64,'view_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(65,'view_any_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(66,'create_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(67,'update_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(68,'restore_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(69,'restore_any_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(70,'replicate_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(71,'reorder_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(72,'delete_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(73,'delete_any_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(74,'force_delete_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(75,'force_delete_any_incident','web','2026-03-10 11:55:34','2026-03-10 11:55:34'),
(76,'view_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(77,'view_any_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(78,'create_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(79,'update_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(80,'restore_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(81,'restore_any_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(82,'replicate_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(83,'reorder_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(84,'delete_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(85,'delete_any_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(86,'force_delete_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(87,'force_delete_any_operator::document','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(88,'page_AesaAuditPage','web','2026-03-10 12:01:56','2026-03-10 12:01:56'),
(89,'widget_ComplianceMonitorWidget','web','2026-03-10 12:01:56','2026-03-10 12:01:56');

-- roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_team_id_name_guard_name_unique` (`team_id`,`name`,`guard_name`),
  KEY `roles_team_foreign_key_index` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` VALUES
(1,1,'piloto','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(2,1,'jefe-operaciones','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(3,1,'representante-operador','web','2026-03-03 09:45:02','2026-03-03 09:45:02'),
(5,1,'super_admin','web','2026-03-03 09:47:02','2026-03-03 09:47:02'),
(6,1,'panel_user','web','2026-03-03 09:59:50','2026-03-03 09:59:50'),
(7,NULL,'panel_user','web','2026-03-03 11:24:02','2026-03-03 11:24:02'),
(8,1,'admin_local','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(9,1,'mecanico','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(10,2,'super_admin','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(11,2,'admin_local','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(12,2,'piloto','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(13,2,'mecanico','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(14,3,'super_admin','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(15,3,'admin_local','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(16,3,'piloto','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(17,3,'mecanico','web','2026-03-03 11:36:59','2026-03-03 11:36:59'),
(18,NULL,'super_admin','web','2026-03-03 11:42:17','2026-03-03 11:42:17');

-- model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`team_id`,`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_permissions_permission_id_foreign` (`permission_id`),
  KEY `model_has_permissions_team_foreign_key_index` (`team_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`team_id`,`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_roles_role_id_foreign` (`role_id`),
  KEY `model_has_roles_team_foreign_key_index` (`team_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` VALUES
(1,'App\\Models\\User',2,1),
(1,'App\\Models\\User',4,1),
(3,'App\\Models\\User',1,1),
(5,'App\\Models\\User',1,1),
(5,'App\\Models\\User',1,2),
(5,'App\\Models\\User',1,3),
(8,'App\\Models\\User',2,1),
(9,'App\\Models\\User',2,1),
(9,'App\\Models\\User',4,1),
(10,'App\\Models\\User',1,2),
(11,'App\\Models\\User',3,2),
(14,'App\\Models\\User',1,3);

-- role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` VALUES
(1,1),(2,1),
(1,2),(2,2),(3,2),(4,2),(5,2),
(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),
(8,5),(9,5),(10,5),(11,5),(12,5),(13,5),(14,5),(15,5),(16,5),(17,5),(18,5),(19,5),
(20,5),(21,5),(22,5),(23,5),(24,5),(25,5),(26,5),(27,5),(28,5),(29,5),(30,5),(31,5),
(32,5),(33,5),(34,5),(35,5),(36,5),(37,5),(38,5),(39,5),(40,5),(41,5),(42,5),(43,5),
(44,5),(45,5),(46,5),(47,5),(48,5),(49,5),(50,5),(51,5),(52,5),(53,5),(54,5),(55,5),
(56,5),(57,5),(58,5),(59,5),(60,5),(61,5),(62,5),(63,5),(64,5),(65,5),(66,5),(67,5),
(68,5),(69,5),(70,5),(71,5),(72,5),(73,5),(74,5),(75,5),(76,5),(77,5),(78,5),(79,5),
(80,5),(81,5),(82,5),(83,5),(84,5),(85,5),(86,5),(87,5),(88,5),(89,5),
(1,10),(2,10),(3,10),(4,10),(5,10),(6,10),(7,10),(8,10),(9,10),(10,10),(11,10),(12,10),
(13,10),(14,10),(15,10),(16,10),(17,10),(18,10),(19,10),(20,10),(21,10),(22,10),(23,10),
(24,10),(25,10),(26,10),(27,10),(28,10),(29,10),(30,10),(31,10),(32,10),(33,10),(34,10),
(35,10),(36,10),(37,10),(38,10),(39,10),(40,10),(41,10),(42,10),(43,10),(44,10),(45,10),
(46,10),(47,10),(48,10),(49,10),(50,10),(51,10),(52,10),(53,10),(54,10),(55,10),(56,10),
(57,10),(58,10),(59,10),(60,10),(61,10),(62,10),(63,10),
(8,11),(9,11),(10,11),(11,11),(12,11),(13,11),(14,11),(15,11),(16,11),(17,11),(18,11),(19,11),
(20,11),(21,11),(22,11),(23,11),(24,11),(25,11),(26,11),(27,11),(28,11),(29,11),(30,11),(31,11),
(32,11),(33,11),(34,11),(35,11),(36,11),(37,11),(38,11),(39,11),(40,11),(41,11),(42,11),(43,11),
(44,11),(45,11),(46,11),(47,11),(48,11),(49,11),(50,11),(51,11),(52,11),(53,11),(54,11),(55,11),
(56,11),(57,11),(58,11),(59,11),(60,11),(61,11),(62,11),(63,11),
(1,14),(2,14),(3,14),(4,14),(5,14),(6,14),(7,14),(8,14),(9,14),(10,14),(11,14),(12,14),
(13,14),(14,14),(15,14),(16,14),(17,14),(18,14),(19,14),(20,14),(21,14),(22,14),(23,14),
(24,14),(25,14),(26,14),(27,14),(28,14),(29,14),(30,14),(31,14),(32,14),(33,14),(34,14),
(35,14),(36,14),(37,14),(38,14),(39,14),(40,14),(41,14),(42,14),(43,14),(44,14),(45,14),
(46,14),(47,14),(48,14),(49,14),(50,14),(51,14),(52,14),(53,14),(54,14),(55,14),(56,14),
(57,14),(58,14),(59,14),(60,14),(61,14),(62,14),(63,14),
(8,18),(9,18),(10,18),(11,18),(12,18),(13,18),(14,18),(15,18),(16,18),(17,18),(18,18),(19,18),
(20,18),(21,18),(22,18),(23,18),(24,18),(25,18),(26,18),(27,18),(28,18),(29,18),(30,18),(31,18),
(32,18),(33,18),(34,18),(35,18),(36,18),(37,18),(38,18),(39,18),(40,18),(41,18),(42,18),(43,18),
(44,18),(45,18),(46,18),(47,18),(48,18),(49,18),(50,18),(51,18),(52,18),(53,18),(54,18),(55,18),
(56,18),(57,18),(58,18),(59,18),(60,18),(61,18),(62,18),(63,18),(64,18),(65,18),(66,18),(67,18),
(68,18),(69,18),(70,18),(71,18),(72,18),(73,18),(74,18),(75,18),(76,18),(77,18),(78,18),(79,18),
(80,18),(81,18),(82,18),(83,18),(84,18),(85,18),(86,18),(87,18),(88,18),(89,18);

-- team_user
DROP TABLE IF EXISTS `team_user`;
CREATE TABLE `team_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_user_team_id_foreign` (`team_id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `team_user` VALUES
(1,1,1,NULL,NULL),
(2,1,2,NULL,NULL),
(3,2,1,NULL,NULL),
(4,3,1,NULL,NULL),
(5,2,3,NULL,NULL),
(6,1,4,NULL,NULL),
(7,1,4,NULL,NULL);

-- drones
DROP TABLE IF EXISTS `drones`;
CREATE TABLE `drones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned NOT NULL,
  `model` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `registration_mark` varchar(255) DEFAULT NULL,
  `weight_grams` int(11) NOT NULL,
  `class_mark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'operativo',
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `aesa_registration_number` varchar(255) DEFAULT NULL,
  `insurance_policy_number` varchar(255) DEFAULT NULL,
  `insurance_expiration_date` date DEFAULT NULL,
  `insurance_document_path` varchar(255) DEFAULT NULL,
  `firmware_version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drones_serial_number_unique` (`serial_number`),
  KEY `drones_organization_id_foreign` (`organization_id`),
  KEY `drones_team_id_foreign` (`team_id`),
  CONSTRAINT `drones_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drones` VALUES
(1,1,'Matrice 30T','DJI','158CF4567890',NULL,3770,'C2','2026-03-03 09:45:02','2026-03-09 10:41:58','operativo',1,'123123123123','sdfsdfsdfsdf','2027-03-07','drones/insurances/01KK92SDB3RE8VWC85AYMJMR2S.pdf','123123'),
(2,1,'Mavic 3 Enterprise','DJI','158DG1234567',NULL,915,'C1','2026-03-03 09:45:02','20-03-03 10:42:08','operativo',1,NULL,NULL,NULL,NULL,NULL),
(3,1,'mt','dji','12312312334A',NULL,249,'C0','2026-03-03 10:53:52','2026-03-03 10:53:52','operativo',2,NULL,NULL,NULL,NULL,NULL),
(4,1,'hermes','dji','123123123123','123123123123',3444,'C6','2026-03-03 13:06:19','2026-03-03 13:06:19','operativo',1,NULL,NULL,NULL,NULL,NULL);

-- batteries
DROP TABLE IF EXISTS `batteries`;
CREATE TABLE `batteries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `drone_id` bigint(20) unsigned DEFAULT NULL,
  `internal_id` varchar(255) NOT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `charge_cycles` int(11) NOT NULL DEFAULT 0,
  `purchase_date` date DEFAULT NULL,
  `status` enum('Activa','Mantenimiento','Retirada') NOT NULL DEFAULT 'Activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batteries_drone_id_foreign` (`drone_id`),
  CONSTRAINT `batteries_drone_id_foreign` FOREIGN KEY (`drone_id`) REFERENCES `drones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `batteries` VALUES
(1,1,'123123123','2b',21,'2024-12-22','Activa','2026-03-05 12:59:28','2026-03-05 13:08:06');

-- licenses
DROP TABLE IF EXISTS `licenses`;
CREATE TABLE `licenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `licenses_user_id_foreign` (`user_id`),
  KEY `licenses_team_id_foreign` (`team_id`),
  CONSTRAINT `licenses_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `licenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `licenses` VALUES
(1,3,'STS-01','12312312312312231231',NULL,'2028-12-22',NULL,'2026-03-03 12:17:12','2026-03-03 12:17:12',NULL),
(5,2,'A1/A3','ESP-RP-000000149063','2026-02-16','2031-02-16','licenses/01KJYVJGP7Z07FQBDG2KX444KR.pdf','2026-03-05 11:19:21','2026-03-05 11:19:21',NULL),
(6,2,'STS-01','123123213213123','2026-03-09','2037-03-01','licenses/01KKBHPC3WST3WT3D5D23G96PD.pdf','2026-03-10 09:36:49','2026-03-10 09:36:49',1),
(7,4,'A1/A3','ESP-RP-000000149063','2026-02-16','2031-02-16','licenses/01KKV92GA6SXF4T5G4YE25EETC.pdf','2026-03-16 12:14:00','2026-03-16 12:14:00',1);

-- maintenances
DROP TABLE IF EXISTS `maintenances`;
CREATE TABLE `maintenances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `drone_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `technician` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenances_drone_id_foreign` (`drone_id`),
  KEY `maintenances_team_id_foreign` (`team_id`),
  CONSTRAINT `maintenances_drone_id_foreign` FOREIGN KEY (`drone_id`) REFERENCES `drones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenances_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `maintenances` VALUES
(2,1,'2025-12-12','rotor','hecho','luis','2026-03-05 13:19:42','2026-03-05 13:19:42',NULL),
(3,1,'2026-01-15','revision 50h','Puesta a punto','Luis','2026-03-06 08:36:32','2026-03-06 08:36:32',1),
(4,4,'2026-03-06','firmware','asdasdasdf','Jefe Policia','2026-03-06 11:11:35','2026-03-06 11:11:35',1),
(5,4,'2026-03-06','correctivo','asdasd','Jefe Policia','2026-03-06 11:19:02','2026-03-06 11:19:02',1);

-- operator_documents
DROP TABLE IF EXISTS `operator_documents`;
CREATE TABLE `operator_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operator_documents_team_id_foreign` (`team_id`),
  CONSTRAINT `operator_documents_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `operator_documents` VALUES
(3,1,'SEGURO-RC','mapfre','operator-documents/01KK8Y46NEFM7H7Z45CQVR0ED0.pdf','2026-04-02',1,'2026-03-09 09:16:22','2026-03-09 09:16:22'),
(5,1,'STS-01','123123123123123','operator-documents/01KKBJXHCZ62HEBCAX7YMMS539.pdf','2032-03-01',1,'2026-03-10 09:58:12','2026-03-10 09:58:12');

-- flights
DROP TABLE IF EXISTS `flights`;
CREATE TABLE `flights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `drone_id` bigint(20) unsigned NOT NULL,
  `operation_name` varchar(255) DEFAULT NULL,
  `pilot_name` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `mission_type` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `enaire_check_passed` tinyint(1) NOT NULL DEFAULT 0,
  `pre_flight_checklist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pre_flight_checklist`)),
  `enaire_report_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'planned',
  `location_name` varchar(255) DEFAULT NULL,
  `enaire_pdf_path` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `enaire_json_path` varchar(255) DEFAULT NULL,
  `enaire_warnings` text DEFAULT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `battery_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flights_drone_id_foreign` (`drone_id`),
  KEY `flights_user_id_foreign` (`user_id`),
  KEY `flights_team_id_foreign` (`team_id`),
  KEY `flights_battery_id_foreign` (`battery_id`),
  CONSTRAINT `flights_battery_id_foreign` FOREIGN KEY (`battery_id`) REFERENCES `batteries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `flights_drone_id_foreign` FOREIGN KEY (`drone_id`) REFERENCES `drones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flights_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flights_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `flights` (id, drone_id, operation_name, pilot_name, start_time, end_time, location, duration_minutes, mission_type, created_at, updated_at, latitude, longitude, enaire_check_passed, pre_flight_checklist, enaire_report_path, status, location_name, enaire_pdf_path, user_id, enaire_json_path, team_id, observations, battery_id) VALUES
(1,1,NULL,NULL,'2026-03-03 12:00:00','2026-03-03 13:11:00',NULL,71,'STS-01','2026-03-03 12:37:55','2026-03-06 07:38:30',NULL,NULL,1,NULL,NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KJSV8YPTGPX1Y55DVDK5SSJ7.pdf',2,'enaire-jsons/01KJSV8YPX36CEE6TW68NN0MFK.json',1,'todo ok',1),
(2,2,NULL,NULL,'2026-03-03 12:12:00','2026-03-03 13:00:00',NULL,48,'STS-01','2026-03-03 12:43:55','2026-03-05 13:18:42',NULL,NULL,1,NULL,NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KJSVKYMEPF77T0YBQ1SK5DMY.pdf',2,'enaire-jsons/01KJSVKYMHJPZ857GJCHG5PCM4.json',1,'todo ok',1),
(3,4,NULL,NULL,'2026-03-09 13:13:00','2026-03-09 13:40:00',NULL,27,'STS-01','2026-03-09 08:45:18','2026-03-09 08:45:18',NULL,NULL,1,NULL,NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KK8WBB61BRZYFMTQ1PZZQ73N.pdf',2,'enaire-jsons/01KK8WBB64HCXBFWFVZZZ2C7FW.json',1,'Todo normal',1),
(4,1,NULL,NULL,'2026-03-27 13:13:00','2026-03-27 13:44:00',NULL,31,'SORA','2026-03-09 11:14:32','2026-03-09 11:15:35',NULL,NULL,1,'{\"sora\":[\"sail_level\",\"autorizacion_aesa\",\"mitigations\"]}',NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KK94WJF84JMCX41W53QHXECS.pdf',2,'enaire-jsons/01KK94WJFBCT468YGVEFK7SBVB.json',1,NULL,1),
(5,1,NULL,NULL,'2026-03-27 13:13:00','2026-03-27 13:33:00',NULL,20,'OPEN-CATEGORY','2026-03-10 10:13:57','2026-03-10 10:22:01',NULL,NULL,1,'{\"open_category\":[\"vmc_ok\",\"zonas_enaire\",\"vlos_120m\",\"tecnica\"]}',NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KKBKTCEW2BC33W0M1K2N6DBA.pdf',2,'enaire-jsons/01KKBKTCEZHXEW1K22DR63DVWG.json',1,NULL,1),
(6,1,NULL,NULL,'2026-03-26 14:41:00','2026-03-26 14:47:00',NULL,6,'STS-01','2026-03-10 10:15:16','2026-03-10 10:22:46',NULL,NULL,1,'{\"sts_01\":[\"zonas_earo\",\"c5_fts\",\"zona_terrestre\",\"erp_ok\",\"buffer_zone\"]}',NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KKBKWS0PBV06EFX70D7ZNNVF.pdf',2,'enaire-jsons/01KKBKWS0TMJVQ21XE600PT0M9.json',1,NULL,1),
(7,1,NULL,NULL,'2026-03-28 11:00:00','2026-03-28 11:30:00',NULL,30,'STS-01','2026-03-10 10:23:33','2026-03-10 10:24:13',NULL,NULL,1,'{\"sts_01\":[\"zonas_earo\",\"c5_fts\",\"zona_terrestre\",\"erp_ok\",\"buffer_zone\"]}',NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KKBMBYER542NAQFYVF1YE1A1.pdf',2,'enaire-jsons/01KKBMBYEVG6KJ1WMXY23YM7KY.json',1,NULL,1),
(8,1,'prueba nombre',NULL,'2026-03-26 14:14:00','2026-03-26 14:50:00',NULL,36,'OPEN-CATEGORY','2026-03-10 12:59:18','2026-03-10 12:59:18',NULL,NULL,1,'{\"open_category\":[\"vmc_ok\",\"zonas_enaire\",\"vlos_120m\",\"tecnica\"]}',NULL,'completed','375858.615770N 0041655.387671W','enaire-evidences/01KKBX94VHDKVQ8V5JR32XJJTT.pdf',2,'enaire-jsons/01KKBX94VMSHKPQGQRREX1NHF7.json',1,'todo ok',1),
(9,1,'adsadsadsds',NULL,NULL,NULL,NULL,NULL,'OPEN-CATEGORY','2026-03-13 12:37:45','2026-03-13 12:37:45',NULL,NULL,1,'{\"open_category\":[\"vmc_ok\",\"zonas_enaire\",\"vlos_120m\",\"tecnica\"]}',NULL,'planned','375858.615770N 0041655.387671W','enaire-evidences/01KKKK7TYKQ8PP2JFM9VMP5ZRS.pdf',2,'enaire-jsons/01KKKK7TYNGPDY0JBF3CMKRPV6.json',1,NULL,NULL);

-- incidents
DROP TABLE IF EXISTS `incidents`;
CREATE TABLE `incidents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `drone_id` bigint(20) unsigned DEFAULT NULL,
  `flight_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `category` enum('accident','incident','near_miss','hazard') NOT NULL DEFAULT 'incident',
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'low',
  `status` enum('open','investigating','closed') NOT NULL DEFAULT 'open',
  `description` text DEFAULT NULL,
  `root_cause` text DEFAULT NULL,
  `mitigation_actions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incidents_team_id_foreign` (`team_id`),
  KEY `incidents_user_id_foreign` (`user_id`),
  KEY `incidents_drone_id_foreign` (`drone_id`),
  KEY `incidents_flight_id_foreign` (`flight_id`),
  CONSTRAINT `incidents_drone_id_foreign` FOREIGN KEY (`drone_id`) REFERENCES `drones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `incidents_flight_id_foreign` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id`) ON DELETE SET NULL,
  CONSTRAINT `incidents_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `incidents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `incidents` VALUES
(1,1,1,2,NULL,'prueba incidente','2026-03-10','incident','medium','open','sdasdfsdfa',NULL,NULL,'2026-03-10 12:45:33','2026-03-10 12:45:33',NULL);

SET FOREIGN_KEY_CHECKS=1;
