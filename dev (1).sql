-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 02:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(191) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `method` varchar(191) DEFAULT NULL,
  `route` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `event`, `properties`, `causer_id`, `causer_type`, `ip_address`, `user_agent`, `method`, `route`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Rejected and deleted user: Iya Bell (iya@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:12', '2026-03-28 03:30:12'),
(2, NULL, 'Approved user: Lexi Lore (lexi@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:23', '2026-03-28 03:30:23'),
(3, NULL, 'Approved user: Benita Biala (benita@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:32', '2026-03-28 03:30:32'),
(4, NULL, 'Approved user: James Eubra (james@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:40', '2026-03-28 03:30:40'),
(5, NULL, 'Approved user: Niko De Vera (niko@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:44', '2026-03-28 03:30:44'),
(6, NULL, 'Approved user: Mark Caguiao (mark@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:46', '2026-03-28 03:30:46'),
(7, NULL, 'Approved user: Marian Rivera (marian@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:52', '2026-03-28 03:30:52'),
(8, NULL, 'Approved user: Dye Mes (dyeymseubra@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 03:30:54', '2026-03-28 03:30:54'),
(9, NULL, 'Approved user: Shaira Tandoc (shaira@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 05:11:04', '2026-03-28 05:11:04'),
(10, NULL, 'Approved user: Denise Monterola (denise@email.com)', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, NULL, '2026-03-28 05:11:15', '2026-03-28 05:11:15');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user1_id` bigint(20) UNSIGNED NOT NULL,
  `user2_id` bigint(20) UNSIGNED NOT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `unread_count_user1` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `unread_count_user2` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `landlord_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL DEFAULT 'Untitled Listing',
  `type` enum('roommate','room','apartment') NOT NULL,
  `location` varchar(191) NOT NULL,
  `min_price` decimal(10,2) DEFAULT NULL,
  `max_price` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `bedrooms` int(11) NOT NULL DEFAULT 1,
  `bathrooms` int(11) NOT NULL DEFAULT 1,
  `property_type` varchar(191) NOT NULL DEFAULT 'apartment',
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `area_sqft` decimal(10,2) NOT NULL DEFAULT 0.00,
  `furnished` tinyint(1) NOT NULL DEFAULT 0,
  `utilities_included` tinyint(1) NOT NULL DEFAULT 0,
  `available_from` date DEFAULT NULL,
  `lease_duration_months` int(11) NOT NULL DEFAULT 12,
  `security_deposit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `house_rules` text DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listing_images`
--

CREATE TABLE `listing_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(191) NOT NULL,
  `caption` varchar(191) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `matched_user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `user_action` enum('liked','disliked') DEFAULT NULL,
  `is_mutual` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `message_type` varchar(191) NOT NULL DEFAULT 'text',
  `delivery_status` varchar(191) NOT NULL DEFAULT 'sent',
  `is_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `metadata` text DEFAULT NULL,
  `reaction` varchar(191) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000000_create_activity_logs_table', 1),
(5, '2024_01_01_000000_create_matches_table', 1),
(6, '2024_03_15_000000_create_transactions_table', 1),
(7, '2025_02_13_000001_create_activity_logs_table', 1),
(8, '2025_10_31_004202_create_roommate_profiles_table', 1),
(9, '2025_10_31_004207_create_roommate_preferences_table', 1),
(10, '2025_10_31_064532_add_is_admin_to_users_table', 1),
(11, '2025_11_13_090215_add_name_to_users_table', 1),
(12, '2025_11_13_090629_add_role_to_users_table', 1),
(13, '2025_11_14_103116_create_roommate_matches_table', 1),
(14, '2025_11_15_030908_create_messages_table', 1),
(15, '2025_11_19_143248_create_listings_table', 1),
(16, '2025_11_19_150435_create_preferences_table', 1),
(17, '2025_11_19_150553_create_notifications_table', 1),
(18, '2025_11_19_150616_create_admin_logs_table', 1),
(19, '2025_11_19_164009_add_gender_to_users_table', 1),
(20, '2025_11_19_164121_update_users_table_add_profile_fields', 1),
(21, '2025_11_20_110859_add_sleep_pattern_to_roommate_profiles_table', 1),
(22, '2025_11_20_110956_add_remaining_columns_to_roommate_profiles', 1),
(23, '2025_11_20_140628_create_conversations_table', 1),
(24, '2025_11_20_140649_update_messages_table_for_conversations', 1),
(25, '2025_11_20_140822_make_messages_conversation_id_required', 1),
(26, '2025_11_20_143741_add_deleted_at_to_users_table', 1),
(27, '2025_11_20_154713_add_date_of_birth_to_users_table', 1),
(28, '2025_11_21_054722_update_listings_table', 1),
(29, '2025_11_21_062036_add_is_admin_to_users_table', 1),
(30, '2025_11_21_063401_create_listing_images_table', 1),
(31, '2025_11_21_084201_add_user_id_to_listings_table', 2),
(32, '2025_11_21_171320_create_settings_table', 2),
(33, '2025_11_21_183719_update_settings_table', 2),
(34, '2025_11_21_184459_add_is_active_to_users_table', 2),
(35, '2026_01_16_101758_add_price_columns_to_listings_table', 2),
(36, '2026_02_05_204142_add_phone_to_roommate_profiles_table', 2),
(37, '2026_02_12_132214_create_payments_table', 2),
(38, '2026_03_17_100342_add_location_to_listings_table', 2),
(39, '2026_03_17_185527_add_preferred_location_to_preferences_table', 2),
(40, '2026_03_18_062921_add_missing_fields_to_messages_table', 2),
(41, '2026_03_18_063040_add_deleted_at_to_users_table', 2),
(42, '2026_03_28_101619_add_approval_status_to_users_table', 3),
(43, '2026_03_28_103828_create_password_reset_otps_table', 4),
(44, '2026_03_28_113523_create_user_validations_table', 5),
(45, '2026_03_29_221107_add_title_to_notifications_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `read_at`, `data`, `created_at`, `updated_at`) VALUES
(1, 1, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(2, 1, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(3, 1, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(4, 1, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(5, 2, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(6, 2, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(7, 2, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(8, 2, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(9, 3, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(10, 3, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(11, 3, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(12, 3, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(13, 4, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(14, 4, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(15, 4, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(16, 4, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(17, 5, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(18, 5, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(19, 5, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(20, 5, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(21, 6, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(22, 6, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(23, 6, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(24, 6, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(25, 7, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(26, 7, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(27, 7, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(28, 7, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(29, 8, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(30, 8, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(31, 8, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(32, 8, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(33, 9, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(34, 9, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(35, 9, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(36, 9, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(37, 10, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(38, 10, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(39, 10, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(40, 10, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(41, 11, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(42, 11, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(43, 11, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(44, 11, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(45, 12, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(46, 12, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(47, 12, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(48, 12, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(49, 13, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(50, 13, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(51, 13, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(52, 13, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(53, 15, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(54, 15, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(55, 15, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(56, 15, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(57, 16, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(58, 16, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(59, 16, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(60, 16, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(61, 17, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(62, 17, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(63, 17, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(64, 17, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(65, 18, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(66, 18, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(67, 18, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(68, 18, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(69, 19, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(70, 19, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(71, 19, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(72, 19, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(73, 20, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(74, 20, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(75, 20, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(76, 20, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(77, 21, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(78, 21, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(79, 21, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(80, 21, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(81, 22, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(82, 22, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(83, 22, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(84, 22, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(85, 23, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(86, 23, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(87, 23, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(88, 23, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(89, 24, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(90, 24, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(91, 24, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(92, 24, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(93, 25, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(94, 25, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(95, 25, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(96, 25, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(97, 26, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(98, 26, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(99, 26, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(100, 26, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(101, 27, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(102, 27, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(103, 27, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(104, 27, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(105, 28, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(106, 28, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(107, 28, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(108, 28, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(109, 29, 'message', 'New Message', 'You have received a new message from another user', NULL, '{\"sender_id\":1}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(110, 29, 'new_user', 'New User Joined', 'A new user has joined the platform', '2026-03-29 14:11:24', '{\"new_user_id\":2}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(111, 29, 'match', 'New Match', 'You have a new roommate match', NULL, '{\"matched_user_id\":3}', '2026-03-29 14:11:24', '2026-03-29 14:11:24'),
(112, 29, 'profile_view', 'Profile Viewed', 'Someone viewed your profile', '2026-03-29 14:11:24', '{\"viewer_id\":4}', '2026-03-29 14:11:24', '2026-03-29 14:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_otps`
--

CREATE TABLE `password_reset_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `otp_code` varchar(191) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(191) NOT NULL DEFAULT 'manual',
  `payment_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cleanliness_level` enum('very_clean','clean','average','messy','very_messy') NOT NULL,
  `sleep_pattern` enum('early_bird','night_owl','flexible') NOT NULL,
  `study_habit` enum('quiet_environment','music_ok','tv_background','no_preference') NOT NULL,
  `noise_tolerance` enum('quiet','moderate','loud') NOT NULL,
  `min_budget` decimal(10,2) DEFAULT NULL,
  `max_budget` decimal(10,2) DEFAULT NULL,
  `hobbies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hobbies`)),
  `lifestyle_tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lifestyle_tags`)),
  `preferred_location` varchar(191) DEFAULT NULL,
  `smoking` enum('never','sometimes','regularly','only_outside') NOT NULL DEFAULT 'never',
  `pets` enum('none','cats_ok','dogs_ok','all_pets_ok','no_pets') NOT NULL DEFAULT 'none',
  `overnight_visitors` enum('not_allowed','with_notice','anytime') NOT NULL DEFAULT 'with_notice',
  `schedule` enum('morning','evening','night_shift','irregular') NOT NULL DEFAULT 'morning',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` (`id`, `user_id`, `cleanliness_level`, `sleep_pattern`, `study_habit`, `noise_tolerance`, `min_budget`, `max_budget`, `hobbies`, `lifestyle_tags`, `preferred_location`, `smoking`, `pets`, `overnight_visitors`, `schedule`, `created_at`, `updated_at`) VALUES
(1, 1, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:53:26', '2026-03-26 07:53:26'),
(2, 2, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:55:20', '2026-03-26 07:55:20'),
(3, 3, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:55:49', '2026-03-26 07:55:49'),
(4, 4, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:56:59', '2026-03-26 07:56:59'),
(5, 5, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:57:23', '2026-03-26 07:57:23'),
(6, 6, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:58:10', '2026-03-26 07:58:10'),
(7, 7, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:58:31', '2026-03-26 07:58:31'),
(8, 8, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:58:54', '2026-03-26 07:58:54'),
(9, 9, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:59:14', '2026-03-26 07:59:14'),
(10, 10, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 07:59:41', '2026-03-26 07:59:41'),
(11, 11, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:00:28', '2026-03-26 08:00:28'),
(12, 12, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:00:56', '2026-03-26 08:00:56'),
(13, 13, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:01:32', '2026-03-26 08:01:32'),
(14, 14, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:02:10', '2026-03-26 08:02:10'),
(15, 15, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:02:38', '2026-03-26 08:02:38'),
(16, 16, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:03:15', '2026-03-26 08:03:15'),
(17, 17, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:04:04', '2026-03-26 08:04:04'),
(18, 18, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:05:40', '2026-03-26 08:05:40'),
(19, 19, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:06:10', '2026-03-26 08:06:10'),
(20, 20, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:06:29', '2026-03-26 08:06:29'),
(21, 21, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:06:56', '2026-03-26 08:06:56'),
(22, 22, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:07:24', '2026-03-26 08:07:24'),
(23, 23, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:08:54', '2026-03-26 08:08:54'),
(24, 24, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-26 08:11:24', '2026-03-26 08:11:24'),
(25, 25, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-28 02:20:53', '2026-03-28 02:20:53'),
(26, 26, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-28 02:22:06', '2026-03-28 02:22:06'),
(27, 27, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-28 02:43:32', '2026-03-28 02:43:32'),
(28, 28, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-28 02:49:07', '2026-03-28 02:49:07'),
(29, 29, 'average', 'flexible', 'no_preference', 'moderate', 0.00, 0.00, NULL, NULL, NULL, 'never', 'none', 'with_notice', 'morning', '2026-03-28 02:58:19', '2026-03-28 02:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `roommate_matches`
--

CREATE TABLE `roommate_matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `matched_user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `user_action` enum('liked','disliked') NOT NULL,
  `is_mutual` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roommate_matches`
--

INSERT INTO `roommate_matches` (`id`, `user_id`, `matched_user_id`, `status`, `user_action`, `is_mutual`, `created_at`, `updated_at`) VALUES
(1, 23, 19, 'pending', 'liked', 0, '2026-03-26 08:09:38', '2026-03-26 08:09:38'),
(2, 23, 18, 'pending', 'liked', 0, '2026-03-26 08:09:43', '2026-03-26 08:09:43'),
(3, 23, 11, 'pending', 'liked', 0, '2026-03-26 08:09:49', '2026-03-26 08:09:49'),
(4, 8, 29, 'pending', 'liked', 0, '2026-03-28 03:47:42', '2026-03-28 03:47:42'),
(5, 6, 22, 'pending', 'liked', 0, '2026-03-29 15:17:07', '2026-03-29 15:17:07');

-- --------------------------------------------------------

--
-- Table structure for table `roommate_preferences`
--

CREATE TABLE `roommate_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `preferred_gender` enum('male','female','other','no_preference') NOT NULL DEFAULT 'no_preference',
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `preferred_cleanliness` enum('very_messy','somewhat_messy','average','somewhat_clean','very_clean','no_preference') NOT NULL DEFAULT 'no_preference',
  `preferred_noise_level` enum('very_quiet','quiet','moderate','lively','very_loud','no_preference') NOT NULL DEFAULT 'no_preference',
  `preferred_schedule` enum('morning_person','night_owl','flexible','irregular','no_preference') NOT NULL DEFAULT 'no_preference',
  `smoking_ok` tinyint(1) NOT NULL DEFAULT 0,
  `pets_ok` tinyint(1) NOT NULL DEFAULT 0,
  `has_apartment_preferred` tinyint(1) DEFAULT NULL,
  `preferred_location` varchar(191) DEFAULT NULL,
  `min_budget` decimal(10,2) DEFAULT NULL,
  `max_budget` decimal(10,2) DEFAULT NULL,
  `preferred_move_in_date` varchar(191) DEFAULT NULL,
  `preferred_lease_duration` varchar(191) DEFAULT NULL,
  `willing_to_share_room` tinyint(1) NOT NULL DEFAULT 0,
  `furnished_preferred` tinyint(1) DEFAULT NULL,
  `utilities_included_preferred` tinyint(1) DEFAULT NULL,
  `preferred_room_type` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roommate_profiles`
--

CREATE TABLE `roommate_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sleep_pattern` enum('early_bird','night_owl','flexible') DEFAULT NULL,
  `study_habit` enum('intense','moderate','social','quiet') DEFAULT NULL,
  `noise_tolerance` enum('quiet','moderate','loud') DEFAULT NULL,
  `display_name` varchar(191) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','other','prefer_not_to_say') NOT NULL,
  `bio` text DEFAULT NULL,
  `university` varchar(191) DEFAULT NULL,
  `major` varchar(191) DEFAULT NULL,
  `cleanliness_level` enum('very_messy','somewhat_messy','average','somewhat_clean','very_clean') NOT NULL,
  `noise_level` enum('very_quiet','quiet','moderate','lively','very_loud') NOT NULL,
  `schedule` enum('morning_person','night_owl','flexible','irregular') NOT NULL,
  `smoking_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `pets_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `has_apartment` tinyint(1) NOT NULL DEFAULT 0,
  `apartment_location` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `budget_min` decimal(10,2) DEFAULT NULL,
  `budget_max` decimal(10,2) DEFAULT NULL,
  `move_in_date` varchar(191) DEFAULT NULL,
  `lease_duration` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roommate_profiles`
--

INSERT INTO `roommate_profiles` (`id`, `user_id`, `sleep_pattern`, `study_habit`, `noise_tolerance`, `display_name`, `age`, `gender`, `bio`, `university`, `major`, `cleanliness_level`, `noise_level`, `schedule`, `smoking_allowed`, `pets_allowed`, `has_apartment`, `apartment_location`, `phone`, `budget_min`, `budget_max`, `move_in_date`, `lease_duration`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 'moderate', 'moderate', 'Denise Monterola', 22, 'other', 'saasSs', 'Universidad de Dagupan', 'Bachelor of Science in Computer Science', 'average', 'very_quiet', 'morning_person', 0, 0, 0, 'Dagupan City', NULL, 5000.00, 8000.00, NULL, NULL, '2026-03-28 13:00:49', '2026-03-29 12:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('M81uNSNp8NwlDkPyAQHDuL3H5y28ZHeCkmeJzqqk', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOWZvdzNCMElaTld5eE53VEFra2VTYWR0b1MybUtWVGlacTdnbmRVSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMjt9', 1774804921),
('ohT6l4TMURK1eqbJMebXhgmbwrNGGKck36Y9b3ap', 29, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUWlOaE5Ta29mRzRXZTRtYjVmSnhrWUtweVE2dEk1QnJPOWs4S3VwQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi92YWxpZGF0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjk7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI5O30=', 1774797364);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(191) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'PHP',
  `payment_method` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `university` varchar(191) DEFAULT NULL,
  `course` varchar(191) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `department` varchar(191) DEFAULT NULL,
  `budget_min` decimal(10,2) DEFAULT NULL,
  `budget_max` decimal(10,2) DEFAULT NULL,
  `hobbies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hobbies`)),
  `lifestyle_tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lifestyle_tags`)),
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'user',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `name`, `email`, `phone`, `gender`, `age`, `bio`, `avatar`, `university`, `course`, `year_level`, `department`, `budget_min`, `budget_max`, `hobbies`, `lifestyle_tags`, `profile_photo_path`, `role`, `is_approved`, `email_verified_at`, `date_of_birth`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`, `is_active`, `deleted_at`) VALUES
(1, 'Mark', 'Caguiao', 'Mark Caguiao', 'mark@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$FxvxkbOTR5.jeK8HA8dHROcMHa66jf9wZO/KFNa/cNPMB9Xt.c1CS', NULL, '2026-03-26 07:53:26', '2026-03-28 03:30:46', 0, 1, NULL),
(2, 'James', 'Eubra', 'James Eubra', 'james@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$xtK7ElGbtzgartvne0EymezDVO6zeLmJuFE1zuSrvu8azTfCpf/JG', 'wGnPtrx4G4voNV0VjSl278how4gHocpJC2TYtgHUm83vgPDL5Tdk0Tm7NTzw', '2026-03-26 07:55:20', '2026-03-28 03:30:40', 0, 1, NULL),
(3, 'Niko', 'De Vera', 'Niko De Vera', 'niko@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$3p5zUbXdKrOfHDzcqtD4nOBsoqyZIYkMfxum1WkQHLMxqjfCoLnuG', NULL, '2026-03-26 07:55:49', '2026-03-28 03:30:44', 0, 1, NULL),
(4, 'Jerilyn', 'Manuel', 'Jerilyn Manuel', 'jerilyn@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$QA9svwGrxqt91J9pKtgFm.2mrfv5DikdPHTch.6FEXXw/PDTgpMn2', NULL, '2026-03-26 07:56:59', '2026-03-26 07:56:59', 0, 1, NULL),
(5, 'christian', 'ventayen', 'christian ventayen', 'christianventayen@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$E6GCpK7pCAmrSmOvpLgaIOTVPe9we6vVqALuobvKDQ6ZHzULRH/3i', NULL, '2026-03-26 07:57:23', '2026-03-26 07:57:23', 0, 1, NULL),
(6, 'Denise', 'Monterola', 'Denise Monterola', 'denise@example.com', '+639556076938', 'female', 22, 'sdsdsdsd', '1774800532_69c94e941b1c5.jpeg', 'Universidad de Dagupan', 'Bachelor of Science in Computer Science', '2nd Year', NULL, 5000.00, 8000.00, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$wO50hyNVc2S/10JNDad6SOvGNWneSC0OPVghtAiEsJO6Kn3zexG5S', 'eaytSfsB5VPZx2HPVdCEvELiG8vfWC5IkHRjIVoN6ISgaWC1IqON6unDlfcD', '2026-03-26 07:58:10', '2026-03-29 16:08:52', 0, 1, NULL),
(7, 'Seven', 'Evelyn', 'Seven Evelyn', 'seven@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$aUihjS404AHVoA.Z.njKeuTqcSwfeNMl19bwawkMBx3obz4HYOAay', NULL, '2026-03-26 07:58:31', '2026-03-26 07:58:31', 0, 1, NULL),
(8, 'Benita', 'Biala', 'Benita Biala', 'benita@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$pVfi./P8FZVPLMx67LVWrOrd44EATxsGtJXgRzievijLX/64naELe', 'D2MxsfzgSZ7H5HdPe7DtWHEGGeUDRyYM83hICt1qr0qlWGzAplkKjispJcpM', '2026-03-26 07:58:54', '2026-03-28 03:30:32', 0, 1, NULL),
(9, 'John', 'Doe', 'John Doe', 'johndoe@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$E9P/vuPdL8bphkcz/IPSsudrEdrjrMpSk.Zoa4gq44o3gG68mP2My', NULL, '2026-03-26 07:59:14', '2026-03-26 07:59:14', 0, 1, NULL),
(10, 'Angel', 'Locsin', 'Angel Locsin', 'angel@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$YJkZBO8F072z6C.PQgbNqONq3EAHEviq1ITBb4m.Jl2aSI7FMvNDG', NULL, '2026-03-26 07:59:41', '2026-03-26 07:59:41', 0, 1, NULL),
(11, 'Lexi', 'Lore', 'Lexi Lore', 'lexi@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$5RvAjluQ9DxvW3TZbKAxc.bh7jc2zfBQDKCjtWarwHE8XllecMDxi', NULL, '2026-03-26 08:00:28', '2026-03-28 03:30:22', 0, 1, NULL),
(12, 'Sarah', 'Labati', 'Sarah Labati', 'sarah@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$cm9rlkdKAmpxB3o79bb39e8teHF1APftqzoA8cZV7Jv1mPnUUCxdm', NULL, '2026-03-26 08:00:56', '2026-03-26 08:00:56', 0, 1, NULL),
(13, 'Maxine', 'Medina', 'Maxine Medina', 'maxine@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$OkMr3cocslU8uv/mh2/xJ.T6AJYHixWfX9a/k/fZOmbGihGUrBIW6', NULL, '2026-03-26 08:01:32', '2026-03-26 08:01:32', 0, 1, NULL),
(14, 'Iya', 'Bell', 'Iya Bell', 'iya@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$uXO7pk.zvIaKuEzboEg4I.IqnjPH49cw8wMNqRKU5dKLbpG.LRC8q', NULL, '2026-03-26 08:02:10', '2026-03-28 03:30:12', 0, 1, '2026-03-28 03:30:12'),
(15, 'Kevin', 'Durant', 'Kevin Durant', 'kevin@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$wTDpbbxMkGk6jQGfx4XKaOcDj5llrMMMXxywz9w1K6PP8t86HK3oS', NULL, '2026-03-26 08:02:38', '2026-03-26 08:02:38', 0, 1, NULL),
(16, 'Rose', 'Mejia', 'Rose Mejia', 'rose@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$aISAjTMhO7u/cq4aEGar4uBQukecxHRw9BReADR675lqx6upVMTye', NULL, '2026-03-26 08:03:15', '2026-03-26 08:03:15', 0, 1, NULL),
(17, 'Angelica', 'Cervantes', 'Angelica Cervantes', 'angelica@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$66YaoIDCZ5jNjm7ihaEOz.yJRRtU8F.kLcpCTBod20FYQv09yWlp.', NULL, '2026-03-26 08:04:04', '2026-03-26 08:04:04', 0, 1, NULL),
(18, 'Vincent', 'Caalim', 'Vincent Caalim', 'vincent@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$pLks/cQGpZ.SZx6.QHBhqO2etVVvUIOm1F2K0oFZb0O.lRbWjTD.u', '2ztrEEDmgmcmE1MeHQeU8adwMxjyF9KVIzMZP8QtUEILlGkiT25y51eKAZXs', '2026-03-26 08:05:40', '2026-03-26 08:05:40', 0, 1, NULL),
(19, 'Jorb', 'Gabrillo', 'Jorb Gabrillo', 'jorb@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$IVWBzKYkPwO/GdPOTJoGbulu.kqip3gJ6pUA0nbWdjGGfdF5zH39m', NULL, '2026-03-26 08:06:10', '2026-03-26 08:06:10', 0, 1, NULL),
(20, 'Lebron', 'James', 'Lebron James', 'lebron@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$jzJwXjqiFGbctzKYtVY2aeh8SjiY9vG1Rc4JTL.N5NBZr9FBx1HQe', NULL, '2026-03-26 08:06:29', '2026-03-26 08:06:29', 0, 1, NULL),
(21, 'Heartlyn', 'Cariño', 'Heartlyn Cariño', 'heartlyn@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$eE2xH2bSiL471Wc6b9/ItekfkB6UxqvQXulomUKQ5gIBlUZKCN5GS', NULL, '2026-03-26 08:06:56', '2026-03-26 08:06:56', 0, 1, NULL),
(22, 'Shaira', 'Tandoc', 'Shaira Tandoc', 'shaira@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$ThJvfuSyS.7Nve8nFqdrZeKbQavqZwebG0FEQqvnLwjpEvaM7aIJy', 'A7tBAshZfukwDxbsOXfnfB0PjNFVWvV2lbNFvYrLRbukdrHJGw9GS4DdnCkm', '2026-03-26 08:07:24', '2026-03-28 05:11:04', 0, 1, NULL),
(23, 'Rochelle', 'Lanto', 'Rochelle Lanto', 'rochelle@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$DaIa/vsUnn9Dg2Wl8bGP1OZlZcrUKKuealwpCOwfiBE2MSUFVHMSK', NULL, '2026-03-26 08:08:54', '2026-03-26 08:08:54', 0, 1, NULL),
(24, 'Admin', 'User', 'Admin User', 'admin@example.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$JIS0QsdAzo6r/An9vgbzUek3y8czNNzqmoNc2rlHs.jBJYEuSoMca', NULL, '2026-03-26 08:11:24', '2026-03-26 08:11:24', 1, 1, NULL),
(25, 'Edrian', 'Cervantes', 'Edrian Cervantes', 'edrian@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$xxfO3NPf2HzCkpEeAwlMEuvvBcSOwFn7urjP6q5/bF4DbZpgRjW52', NULL, '2026-03-28 02:20:53', '2026-03-28 02:20:53', 0, 1, NULL),
(26, 'Hannah', 'Joy', 'Hannah Joy', 'hannah@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 0, NULL, NULL, '$2y$12$zEhyzNzIteqYFGYPq492xOeeqXtpX4wtRLhst8Q3kEsYUUiPkkE9e', NULL, '2026-03-28 02:22:06', '2026-03-28 02:22:06', 0, 1, NULL),
(27, 'Dye', 'Mes', 'Dye Mes', 'dyeymseubra@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$JbRGwWsQEh/GlHqL3l5kceW4W/nJ61mXrf15QhAySA/ziT4kqCccm', NULL, '2026-03-28 02:43:32', '2026-03-28 03:30:54', 0, 1, NULL),
(28, 'Marian', 'Rivera', 'Marian Rivera', 'marian@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, NULL, '$2y$12$XCSWsLOU6t/VNh.4lubXReU7xlfwaJU5gbW7RVg8UW6f8KvICJCGm', NULL, '2026-03-28 02:49:07', '2026-03-28 03:30:52', 0, 1, NULL),
(29, 'Admin', 'User', 'Admin User', 'admin@findmyroommate.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 1, NULL, NULL, '$2y$12$M88jCe8jLFEKj9m12EoMX.AmXNvv6YPqbfriDo.rIq3Han.fGB04m', 'oVLhEe1WdUcQbe0U5038I0reI8FMbEmagBVkfcK2xRICKIw4nuR1xQuoFke7', '2026-03-28 02:58:19', '2026-03-28 02:58:19', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_validations`
--

CREATE TABLE `user_validations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `id_type` enum('national_id','government_id','umid_id','passport','drivers_license') NOT NULL,
  `id_number` varchar(191) NOT NULL,
  `id_front_image` varchar(191) DEFAULT NULL,
  `id_back_image` varchar(191) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_validations`
--

INSERT INTO `user_validations` (`id`, `user_id`, `id_type`, `id_number`, `id_front_image`, `id_back_image`, `status`, `rejection_reason`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 6, 'national_id', '5045-8652-9642-3728', 'validations/ocd6h0dLsE2Dn8L7liG9Wmv0ZPiruHBVQgEjQ2pj.jpg', 'validations/GV5MumxETuekfaWT9huKorfd5BzfXqAyt7Xix4ax.jpg', 'approved', NULL, '2026-03-29 13:18:28', '2026-03-29 13:07:36', '2026-03-29 13:18:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `activity_logs_causer_id_causer_type_index` (`causer_id`,`causer_type`),
  ADD KEY `activity_logs_log_name_index` (`log_name`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_logs_admin_id_created_at_index` (`admin_id`,`created_at`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_user1_id_user2_id_unique` (`user1_id`,`user2_id`),
  ADD KEY `conversations_user2_id_foreign` (`user2_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listings_user_id_foreign` (`landlord_id`);

--
-- Indexes for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_images_listing_id_index` (`listing_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matches_user_id_matched_user_id_unique` (`user_id`,`matched_user_id`),
  ADD KEY `matches_matched_user_id_foreign` (`matched_user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  ADD KEY `messages_receiver_id_read_at_index` (`receiver_id`,`read_at`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  ADD KEY `messages_created_at_index` (`created_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`);

--
-- Indexes for table `password_reset_otps`
--
ALTER TABLE `password_reset_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_reset_otps_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id_due_date_index` (`user_id`,`due_date`),
  ADD KEY `payments_status_due_date_index` (`status`,`due_date`);

--
-- Indexes for table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `preferences_user_id_unique` (`user_id`);

--
-- Indexes for table `roommate_matches`
--
ALTER TABLE `roommate_matches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roommate_matches_user_id_matched_user_id_unique` (`user_id`,`matched_user_id`),
  ADD KEY `roommate_matches_matched_user_id_foreign` (`matched_user_id`);

--
-- Indexes for table `roommate_preferences`
--
ALTER TABLE `roommate_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roommate_preferences_user_id_foreign` (`user_id`);

--
-- Indexes for table `roommate_profiles`
--
ALTER TABLE `roommate_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roommate_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_transaction_id_unique` (`transaction_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_validations`
--
ALTER TABLE `user_validations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_validations_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listing_images`
--
ALTER TABLE `listing_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `password_reset_otps`
--
ALTER TABLE `password_reset_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preferences`
--
ALTER TABLE `preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `roommate_matches`
--
ALTER TABLE `roommate_matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roommate_preferences`
--
ALTER TABLE `roommate_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roommate_profiles`
--
ALTER TABLE `roommate_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user_validations`
--
ALTER TABLE `user_validations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_causer_id_foreign` FOREIGN KEY (`causer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_user1_id_foreign` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_user2_id_foreign` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_user_id_foreign` FOREIGN KEY (`landlord_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD CONSTRAINT `listing_images_listing_id_foreign` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_matched_user_id_foreign` FOREIGN KEY (`matched_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `preferences`
--
ALTER TABLE `preferences`
  ADD CONSTRAINT `preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roommate_matches`
--
ALTER TABLE `roommate_matches`
  ADD CONSTRAINT `roommate_matches_matched_user_id_foreign` FOREIGN KEY (`matched_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roommate_matches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roommate_preferences`
--
ALTER TABLE `roommate_preferences`
  ADD CONSTRAINT `roommate_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roommate_profiles`
--
ALTER TABLE `roommate_profiles`
  ADD CONSTRAINT `roommate_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_validations`
--
ALTER TABLE `user_validations`
  ADD CONSTRAINT `user_validations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
