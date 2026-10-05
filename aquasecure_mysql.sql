-- AquaSecure - schema MySQL
-- Generated from database/migrations
-- Compatible with MySQL 8.0+

CREATE DATABASE IF NOT EXISTS `aquasecure`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `aquasecure`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) NOT NULL,
    `batch` int NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar(255) NOT NULL,
    `role` varchar(255) NOT NULL DEFAULT 'citizen',
    `remember_token` varchar(100) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(255) NOT NULL,
    `user_id` bigint unsigned DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `payload` longtext NOT NULL,
    `last_activity` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
    `key` varchar(255) NOT NULL,
    `value` mediumtext NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` varchar(255) NOT NULL,
    `owner` varchar(255) NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar(255) NOT NULL,
    `payload` longtext NOT NULL,
    `attempts` tinyint unsigned NOT NULL,
    `reserved_at` int unsigned DEFAULT NULL,
    `available_at` int unsigned NOT NULL,
    `created_at` int unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `total_jobs` int NOT NULL,
    `pending_jobs` int NOT NULL,
    `failed_jobs` int NOT NULL,
    `failed_job_ids` longtext NOT NULL,
    `options` mediumtext DEFAULT NULL,
    `cancelled_at` int DEFAULT NULL,
    `created_at` int NOT NULL,
    `finished_at` int DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `uuid` varchar(255) NOT NULL,
    `connection` text NOT NULL,
    `queue` text NOT NULL,
    `payload` longtext NOT NULL,
    `exception` longtext NOT NULL,
    `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `zones` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `address` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `risk_level` varchar(255) NOT NULL DEFAULT 'low',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `infrastructures` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `zone_id` bigint unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `status` varchar(255) NOT NULL DEFAULT 'operational',
    `installation_date` date DEFAULT NULL,
    `last_maintenance_date` date DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `infrastructures_zone_id_foreign` (`zone_id`),
    CONSTRAINT `infrastructures_zone_id_foreign`
        FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `incidents` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `zone_id` bigint unsigned NOT NULL,
    `infrastructure_id` bigint unsigned DEFAULT NULL,
    `type` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL DEFAULT 'reported',
    `description` text NOT NULL,
    `location` varchar(255) DEFAULT NULL,
    `photo_path` varchar(255) DEFAULT NULL,
    `reported_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `resolved_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `incidents_zone_id_foreign` (`zone_id`),
    KEY `incidents_infrastructure_id_foreign` (`infrastructure_id`),
    KEY `incidents_type_status_index` (`type`, `status`),
    KEY `incidents_reported_at_index` (`reported_at`),
    CONSTRAINT `incidents_zone_id_foreign`
        FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `incidents_infrastructure_id_foreign`
        FOREIGN KEY (`infrastructure_id`) REFERENCES `infrastructures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `projects` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `budget` decimal(12, 2) NOT NULL DEFAULT 0.00,
    `start_date` date DEFAULT NULL,
    `end_date` date DEFAULT NULL,
    `status` varchar(255) NOT NULL DEFAULT 'planned',
    `progress` tinyint unsigned NOT NULL DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `financements` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `project_id` bigint unsigned NOT NULL,
    `source` varchar(255) NOT NULL,
    `amount` decimal(12, 2) NOT NULL,
    `status` varchar(255) NOT NULL DEFAULT 'planned',
    `funded_at` date DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `financements_project_id_foreign` (`project_id`),
    CONSTRAINT `financements_project_id_foreign`
        FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interventions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `incident_id` bigint unsigned NOT NULL,
    `team` varchar(255) NOT NULL,
    `scheduled_at` timestamp NOT NULL,
    `status` varchar(255) NOT NULL DEFAULT 'planned',
    `result` text DEFAULT NULL,
    `started_at` timestamp NULL DEFAULT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `interventions_incident_id_foreign` (`incident_id`),
    CONSTRAINT `interventions_incident_id_foreign`
        FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `alerts` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `zone_id` bigint unsigned DEFAULT NULL,
    `incident_id` bigint unsigned DEFAULT NULL,
    `type` varchar(255) NOT NULL,
    `severity` varchar(255) NOT NULL DEFAULT 'medium',
    `message` varchar(255) NOT NULL,
    `read_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `alerts_zone_id_foreign` (`zone_id`),
    KEY `alerts_incident_id_foreign` (`incident_id`),
    KEY `alerts_type_severity_index` (`type`, `severity`),
    CONSTRAINT `alerts_zone_id_foreign`
        FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE SET NULL,
    CONSTRAINT `alerts_incident_id_foreign`
        FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '0001_01_01_000000_create_users_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '0001_01_01_000000_create_users_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '0001_01_01_000001_create_cache_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '0001_01_01_000001_create_cache_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '0001_01_01_000002_create_jobs_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '0001_01_01_000002_create_jobs_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210000_add_role_to_users_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210000_add_role_to_users_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210001_create_zones_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210001_create_zones_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210002_create_infrastructures_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210002_create_infrastructures_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210003_create_incidents_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210003_create_incidents_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210004_create_projects_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210004_create_projects_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210005_create_financements_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210005_create_financements_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210006_create_interventions_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210006_create_interventions_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_05_210007_create_alerts_table', 1
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations`
    WHERE `migration` = '2026_10_05_210007_create_alerts_table'
);

SET FOREIGN_KEY_CHECKS = 1;
