-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2022 at 03:21 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kwizapp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

CREATE TABLE `app_users` (
  `app_user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `register_type` int(1) NOT NULL DEFAULT 1 COMMENT '1=Traditional;2=Facebook;3=Google',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `full_name` varchar(256) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `phone` varchar(256) DEFAULT NULL,
  `password` varchar(256) NOT NULL,
  `otp_code` int(6) DEFAULT NULL,
  `profile_pic` varchar(250) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `refer_code` varchar(256) NOT NULL,
  `refer_code_used` tinyint(1) NOT NULL DEFAULT 0,
  `login_timestamp` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app_users`
--

INSERT INTO `app_users` (`app_user_id`, `status_id`, `register_type`, `is_verified`, `full_name`, `email`, `phone`, `password`, `otp_code`, `profile_pic`, `dob`, `refer_code`, `refer_code_used`, `login_timestamp`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Pragati', 'pragati@enginyre.com', '8090565200', '123456789', 971479, 'error_1609317254.png', '1998-01-12', '', 1, '2021-01-21 08:58:53', '2020-12-28 06:13:35', '2021-01-27 06:42:46'),
(2, 1, 1, 0, 'testing', 'testing@demo.com', '1234567899', '', NULL, '', '1980-01-15', '', 0, '2021-01-21 08:58:53', '2020-12-28 08:31:45', '2020-12-29 05:26:48'),
(3, 1, 1, 0, 'Jhon Methew', 'jhon@test.com', '8009900678', '12345678', 891401, 'error_1609567349.png', '1996-01-12', 'Kwiz-rVou3', 0, '2021-01-21 08:58:53', '2021-01-02 00:32:29', '2021-01-02 06:02:29'),
(4, 1, 1, 0, 'Jhon Mark', 'mark@test.com', '8009909888', '12345678', 607575, 'error_1609571609.png', '1996-01-12', 'Kwiz-PeLz4', 0, '2021-01-21 08:58:53', '2021-01-02 01:43:29', '2021-01-02 07:13:29'),
(6, 1, 1, 0, 'Jhon pan', 'pan@test.com', '8009909878', '12345678', 937453, 'error_1609574375.png', '1996-01-12', 'Kwiz-XWJV5', 0, '2021-01-21 08:58:53', '2021-01-02 02:29:35', '2021-01-02 07:59:35'),
(11, 2, 1, 1, 'Jhon pan', 'pragati.rastogi@hotmail.com', NULL, '87654321', 274610, 'error_1609574929.png', '1996-12-01', 'Kwiz-WJkG6', 0, '2021-02-10 00:40:21', '2021-01-02 02:38:49', '2021-02-10 00:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner_img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL,
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `banner_img`, `display`, `updated_by`, `last_updated_at`) VALUES
(1, 'notice_1612338594.png', 0, 1, '2021-02-03 02:20:40');

-- --------------------------------------------------------

--
-- Table structure for table `cash_wallet`
--

CREATE TABLE `cash_wallet` (
  `cash_wallet_id` int(11) NOT NULL,
  `app_user_id` int(11) NOT NULL,
  `cash_wallet_balance` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cash_wallet`
--

INSERT INTO `cash_wallet` (`cash_wallet_id`, `app_user_id`, `cash_wallet_balance`, `created_at`, `updated_at`) VALUES
(1, 4, 4050, '2021-01-05 10:40:31', '2021-01-29 01:09:56'),
(2, 11, 266.32, '2021-01-06 13:07:22', '2021-04-04 00:26:23'),
(5, 3, 12, '2021-02-03 13:45:00', '2021-02-03 13:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `cash_wallet_transaction`
--

CREATE TABLE `cash_wallet_transaction` (
  `cash_wallet_trans_id` int(11) NOT NULL,
  `cash_wallet_id` int(11) NOT NULL,
  `app_user_id` int(11) NOT NULL,
  `cash_wallet_type` int(11) NOT NULL COMMENT '1=Debit,2=Credit,3=Transfer from coin wallet',
  `cash_wallet_trans_status` int(11) NOT NULL DEFAULT 1 COMMENT '1=Pending,2=Approved',
  `cash_wallet_amount` double NOT NULL,
  `cash_wallet_remark` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `cash_wallet_trans_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cash_wallet_transaction`
--

INSERT INTO `cash_wallet_transaction` (`cash_wallet_trans_id`, `cash_wallet_id`, `app_user_id`, `cash_wallet_type`, `cash_wallet_trans_status`, `cash_wallet_amount`, `cash_wallet_remark`, `cash_wallet_trans_at`) VALUES
(3, 1, 4, 2, 2, 200, 'testing', '2021-01-25 07:50:10'),
(4, 2, 11, 2, 2, 50, 'rewarding for testing', '2021-01-28 13:20:02'),
(7, 2, 11, 3, 2, 0.83, 'Coin Coverted.', '2021-01-22 06:58:43'),
(8, 2, 11, 2, 2, 12, 'Winnings of Playing Contest -Test Contest and obtaining position 1', '2021-01-28 12:53:48'),
(9, 1, 4, 1, 2, 150, 'Withdraw Cash Amount Request', '2021-01-29 06:39:56'),
(10, 5, 3, 2, 2, 12, 'Winnings of Playing Contest -Test Contest and obtaining position 1', '2021-02-03 13:45:00'),
(11, 2, 11, 2, 2, 1, 'Winnings of Playing Contest -Test Contest and obtaining position 2', '2021-02-03 13:45:00'),
(13, 2, 11, 3, 2, 0.83, 'Coin Coverted.', '2021-02-04 05:23:57'),
(14, 2, 11, 3, 2, 0.83, 'Coin Coverted.', '2021-02-04 05:25:04'),
(15, 2, 11, 3, 2, 0.83, 'Coin Coverted.', '2021-02-04 05:27:09'),
(17, 1, 4, 1, 1, 1000, 'Withdraw Cash Amount Request', '2021-02-04 05:51:08'),
(19, 2, 11, 2, 2, 200, 'testing', '2021-04-04 05:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `coin_currency`
--

CREATE TABLE `coin_currency` (
  `coin_currency_id` int(11) NOT NULL,
  `coin_currency_value` int(11) NOT NULL,
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `coin_currency`
--

INSERT INTO `coin_currency` (`coin_currency_id`, `coin_currency_value`, `last_updated_at`) VALUES
(1, 120, '2021-01-07 06:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `coin_wallet`
--

CREATE TABLE `coin_wallet` (
  `coin_wallet_id` int(11) NOT NULL,
  `app_user_id` int(11) NOT NULL,
  `coin_wallet_balance` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `coin_wallet`
--

INSERT INTO `coin_wallet` (`coin_wallet_id`, `app_user_id`, `coin_wallet_balance`, `created_at`, `updated_at`) VALUES
(3, 11, 284, '2021-01-25 12:57:16', '2021-04-04 00:26:55'),
(4, 1, 30, '2021-01-25 12:57:16', '2021-01-27 06:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `coin_wallet_transaction`
--

CREATE TABLE `coin_wallet_transaction` (
  `coin_wallet_trans_id` int(11) NOT NULL,
  `coin_wallet_id` int(11) NOT NULL,
  `app_user_id` int(11) NOT NULL,
  `coin_wallet_type` int(11) NOT NULL COMMENT '1=Debit(Redeem),2=Credit(Reward)',
  `coin_wallet_trans_status` int(11) NOT NULL DEFAULT 1 COMMENT '1=Pending,2=Approved',
  `coin_wallet_amount` double NOT NULL,
  `coin_wallet_remark` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `coin_wallet_trans_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `coin_wallet_transaction`
--

INSERT INTO `coin_wallet_transaction` (`coin_wallet_trans_id`, `coin_wallet_id`, `app_user_id`, `coin_wallet_type`, `coin_wallet_trans_status`, `coin_wallet_amount`, `coin_wallet_remark`, `coin_wallet_trans_at`) VALUES
(1, 3, 11, 2, 2, 20, 'Reference Bonus.', '2021-01-27 13:05:52'),
(2, 4, 1, 2, 2, 10, 'Joining Bonus With Reference Code-Kwiz-WJkG6', '2021-01-27 13:06:10'),
(3, 3, 11, 2, 2, 20, 'Reference Bonus.', '2021-01-27 13:06:30'),
(4, 4, 1, 2, 2, 10, 'Joining Bonus With Reference Code-Kwiz-WJkG6', '2021-01-27 13:06:40'),
(5, 3, 11, 2, 2, 10, 'Daily Joining Bonus', '2021-02-03 08:21:26'),
(6, 3, 11, 2, 2, 10, 'Watch Ad Bonus', '2021-01-27 11:02:45'),
(7, 3, 11, 2, 2, 20, 'Reference Bonus.', '2021-01-27 13:07:05'),
(8, 4, 1, 2, 2, 10, 'Joining Bonus With Reference Code-Kwiz-WJkG6', '2021-01-27 13:07:15'),
(9, 3, 11, 2, 2, 100, 'Winnings of Playing Quiz -Hollywood and obtaining position 1', '2021-01-28 12:17:47'),
(10, 3, 11, 2, 2, 4, 'Winnings Coins for Playing Quiz -Hollywood', '2021-02-03 12:07:25'),
(11, 3, 11, 1, 2, 100, 'Coin Coverted To Cash.', '2021-02-04 05:23:58'),
(12, 3, 11, 1, 2, 100, 'Coin Coverted To Cash.', '2021-02-04 05:25:04'),
(13, 3, 11, 1, 2, 100, 'Coin Coverted To Cash.', '2021-02-04 05:27:09'),
(14, 3, 11, 2, 2, 200, NULL, '2021-04-04 05:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `contest`
--

CREATE TABLE `contest` (
  `contest_id` int(10) UNSIGNED NOT NULL,
  `contest_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `user_can_join` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Total User can join',
  `contest_icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contest_fee` int(11) NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contest`
--

INSERT INTO `contest` (`contest_id`, `contest_name`, `start_time`, `end_time`, `user_can_join`, `contest_icon`, `contest_fee`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'Test Contest', '2021-01-15 07:10:00', '2021-01-15 07:55:00', '600', 'default-150x150_1610633074.png', 0, 2, '2021-01-14 06:14:09', '2021-01-14 08:34:34'),
(2, 'Bollywood', '2021-01-27 12:10:00', '2021-01-27 12:50:00', '300', 'kwizz_app_logo_5_1611120930.png', 10, 2, '2021-01-20 00:05:30', '2021-02-06 06:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `contest_question`
--

CREATE TABLE `contest_question` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `contest_id` int(10) UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option1` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option2` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option3` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option4` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_time` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_point` int(11) NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contest_question`
--

INSERT INTO `contest_question` (`question_id`, `contest_id`, `question`, `option1`, `option2`, `option3`, `option4`, `answer`, `question_time`, `question_point`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'sdasd', 'fdgfdg', 're', 'rre', 'sas', 'option2', '00:30', 10, 2, '2021-01-16 07:16:30', NULL),
(2, 1, 'dsff', 'sdfdfd', 'gdf', 'gfhdgw', 'eqw', 'option3', '00:40', 10, 2, '2021-01-16 07:16:30', NULL),
(3, 1, 'dsff11', 'sdfdfd', 'gdf', 'gfhdgw', 'eqw', 'option3', '00:40', 10, 2, '2021-01-16 07:16:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contest_reward`
--

CREATE TABLE `contest_reward` (
  `contest_reward_id` int(10) UNSIGNED NOT NULL,
  `contest_id` int(10) UNSIGNED NOT NULL,
  `position` int(11) NOT NULL,
  `position_amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contest_reward`
--

INSERT INTO `contest_reward` (`contest_reward_id`, `contest_id`, `position`, `position_amount`, `created_at`, `updated_at`) VALUES
(62, 1, 1, 12, '2021-01-19 06:00:27', NULL),
(63, 1, 2, 1, '2021-01-19 06:00:27', NULL),
(64, 1, 3, 1, '2021-01-19 06:00:27', NULL),
(65, 1, 4, 1, '2021-01-19 06:00:27', NULL),
(66, 1, 5, 1, '2021-01-19 06:00:27', NULL),
(67, 1, 6, 1, '2021-01-19 06:00:27', NULL),
(68, 1, 7, 1, '2021-01-19 06:00:27', NULL),
(69, 1, 8, 1, '2021-01-19 06:00:27', NULL),
(70, 1, 9, 1, '2021-01-19 06:00:27', NULL),
(71, 1, 10, 1, '2021-01-19 06:00:27', NULL),
(75, 2, 1, 30, '2021-01-20 00:21:11', NULL),
(76, 2, 2, 20, '2021-01-20 00:21:11', NULL),
(77, 2, 3, 5, '2021-01-20 00:21:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `daily_bonus`
--

CREATE TABLE `daily_bonus` (
  `bonus_id` int(10) UNSIGNED NOT NULL,
  `monday` double NOT NULL,
  `tuesday` double NOT NULL,
  `wednesday` double NOT NULL,
  `thursday` double NOT NULL,
  `friday` double NOT NULL,
  `saturday` double NOT NULL,
  `sunday` double NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `last_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_bonus`
--

INSERT INTO `daily_bonus` (`bonus_id`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `created_by`, `last_updated_at`) VALUES
(1, 10, 0, 0, 0, 0, 0, 0, 1, '2021-01-11 02:11:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_01_06_090842_create_processing_fees', 2),
(5, '2021_01_08_072145_create_quiz_category', 3),
(6, '2021_01_11_062030_create_daily_bonus', 4),
(7, '2021_01_14_072007_create_refer_and_earn', 5),
(8, '2021_01_19_083609_create_prize_distribution', 6),
(9, '2021_01_19_094011_create_quiz_reward', 7),
(10, '2021_01_27_114242_create_user_references', 8),
(11, '2021_01_27_133720_create_submit_answer', 9),
(12, '2021_02_15_104839_create_section_rights', 10);

-- --------------------------------------------------------

--
-- Table structure for table `page_master`
--

CREATE TABLE `page_master` (
  `page_id` int(11) NOT NULL,
  `page_name` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popup_notification`
--

CREATE TABLE `popup_notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT 0,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `popup_notification`
--

INSERT INTO `popup_notification` (`id`, `display`, `updated_by`, `last_updated_at`) VALUES
(1, 0, 1, '2021-02-03 02:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `processing_fees`
--

CREATE TABLE `processing_fees` (
  `processing_fees_id` int(10) UNSIGNED NOT NULL,
  `processing_fees_value` double NOT NULL,
  `processing_fees_type` int(11) NOT NULL COMMENT '1=>Cash Wallet,2=>Coin Wallet',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answer`
--

CREATE TABLE `quiz_answer` (
  `quiz_answer_id` int(11) NOT NULL,
  `quiz_category_id` int(11) NOT NULL,
  `quiz_ques_id` int(11) NOT NULL,
  `quiz_option_id` int(11) NOT NULL COMMENT 'answer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_category`
--

CREATE TABLE `quiz_category` (
  `quiz_category_id` int(10) UNSIGNED NOT NULL,
  `quiz_category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_category_icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_category`
--

INSERT INTO `quiz_category` (`quiz_category_id`, `quiz_category_name`, `quiz_category_icon`, `category_time`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Science & Technologies', 'avatar2_1610094797.png', '', 1, '2021-01-08 08:33:17', '2021-01-08 06:23:10'),
(2, 'Art1', 'kwizz app logo 4 (1)_1610106977.png', '', 0, '2021-01-08 06:26:17', '2021-01-27 01:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_group`
--

CREATE TABLE `quiz_group` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `quiz_category_id` int(10) UNSIGNED NOT NULL,
  `quiz_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_time` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_group`
--

INSERT INTO `quiz_group` (`group_id`, `quiz_category_id`, `quiz_title`, `quiz_time`, `status_id`, `created_at`, `updated_at`) VALUES
(3, 2, 'Hollywood', '', 4, '2021-01-20 02:03:18', '2021-01-20 04:55:33'),
(4, 2, 'Tollywood', '10:00', 2, '2021-01-20 02:04:16', '2021-02-01 06:23:26'),
(6, 2, 'Bollywood', '', 2, '2021-01-20 04:10:19', NULL),
(7, 2, 'Science', '08:30', 2, '2021-01-22 03:37:24', '2021-01-22 04:08:12'),
(8, 2, 'hwewuw', '01:00', 2, '2021-02-01 06:30:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_group_ques`
--

CREATE TABLE `quiz_group_ques` (
  `ques_id` int(11) NOT NULL,
  `quiz_qroup_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(256) NOT NULL,
  `option2` varchar(256) NOT NULL,
  `option3` varchar(256) NOT NULL,
  `option4` varchar(256) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `question_point` int(11) NOT NULL,
  `question_time` varchar(100) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_group_ques`
--

INSERT INTO `quiz_group_ques` (`ques_id`, `quiz_qroup_id`, `question`, `option1`, `option2`, `option3`, `option4`, `answer`, `question_point`, `question_time`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 3, 'level1', '1', '2', '3', '4', 'option1', 2, '00:30', 2, '2021-01-20 02:03:18', '2021-01-20 07:33:19'),
(2, 3, 'level2', '11', '22', '33', '44', 'option4', 2, '01:00', 2, '2021-01-20 02:03:18', '2021-01-20 07:33:19'),
(3, 3, 'level3', '111', '222', '333', '444', 'option2', 5, '01:00', 2, '2021-01-20 02:03:18', '2021-01-20 07:33:19'),
(4, 4, 'level1', '1', '2', '3', '4', 'option1', 2, '01:40', 2, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(5, 4, 'level2', '11', '22', '33', '44', 'option4', 2, '01:40', 2, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(6, 4, 'level3', '111', '222', '333', '444', 'option2', 5, '01:40', 2, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(7, 4, 'slkahd', 'jhks', 'jj', 'jkj', 'mn,', 'option3', 2, '01:40', 2, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(8, 4, '.ksd;jl', 'jkl', 'ji', 'jou', 'jgjk', 'option4', 2, '01:40', 2, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(9, 4, 'slkahd', 'jhks', 'jj', 'jkj', 'mn,', 'option3', 2, '00:30', 4, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(10, 4, '.ksd;jl', 'jkl', 'ji', 'jou', 'jgjk', 'option4', 2, '00:30', 4, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(11, 4, 'msnad', 'kjkj', 'kjk', 'kjlk', 'jj', 'option3', 2, '00:30', 4, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(12, 4, 'msnad', 'kjkj', 'kjk', 'kjlk', 'jj', 'option3', 2, '01:40', 2, '2021-02-01 11:53:26', '2021-02-01 06:23:26'),
(13, 6, 'jhsjh', 'jlkesrk', 'jhsje', 'jje', 'jhj', 'option3', 10, '00:30', 2, '2021-01-20 04:10:19', '2021-01-20 09:40:20'),
(14, 6, 'kjsakl', 'jlkj', 'jjk', 'hji', 'hhh', 'option4', 10, '00:30', 2, '2021-01-20 04:10:19', '2021-01-20 09:40:20'),
(15, 7, 'hkuu', 'yiy', 'iuouo', 'iyi', 'hiyi', 'option2', 10, '02:00', 2, '2021-01-22 09:38:12', '2021-01-22 04:08:12'),
(16, 7, 'mhkjh', 'kjj', 'llk', 'hhk', 'ojo', 'option3', 10, '02:30', 2, '2021-01-22 09:38:12', '2021-01-22 04:08:12'),
(17, 7, 'hjlk', 'kjlkooi', 'iihh', 'iogug', 'iuo', 'option3', 10, '02:00', 2, '2021-01-22 09:38:12', '2021-01-22 04:08:12'),
(18, 7, 'jkk', 'kjljioi', 'oioggukh', 'kjlkkj', 'jhj', 'option3', 10, '02:00', 2, '2021-01-22 09:38:12', '2021-01-22 04:08:12'),
(19, 8, 'jadks', 'jkjlk', 'kjlk', 'kjl', 'lj;', 'option2', 10, '00:30', 2, '2021-02-01 06:30:13', '2021-02-01 12:00:13'),
(20, 8, 'jsdkjas', 'jlkjlkj', 'lkjl', 'jl;', 'jlkj', 'option3', 10, '00:30', 2, '2021-02-01 06:30:13', '2021-02-01 12:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_ques_options`
--

CREATE TABLE `quiz_ques_options` (
  `option_id` int(11) NOT NULL,
  `quiz_category_id` int(11) NOT NULL,
  `ques_id` int(11) NOT NULL,
  `option_value` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_ques_options`
--

INSERT INTO `quiz_ques_options` (`option_id`, `quiz_category_id`, `ques_id`, `option_value`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 'test opt 1', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(2, 2, 5, 'test opt 2', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(3, 2, 5, 'test opt 3', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(4, 2, 5, 'test opt 4', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(5, 2, 6, 'test opt 2.1', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(6, 2, 6, 'test opt 2.2', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(7, 2, 6, 'test opt 2.3', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(8, 2, 6, 'test opt 2.4', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(9, 2, 7, 'test opt 3.1', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(10, 2, 7, 'test opt 3.2', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(11, 2, 7, 'test opt 3.3', '2021-01-12 06:21:33', '2021-01-12 11:51:33'),
(12, 2, 7, 'test opt 3.4', '2021-01-12 06:21:33', '2021-01-12 11:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_reward`
--

CREATE TABLE `quiz_reward` (
  `quiz_reward_id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `position` int(11) NOT NULL,
  `position_amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_reward`
--

INSERT INTO `quiz_reward` (`quiz_reward_id`, `quiz_id`, `position`, `position_amount`, `created_at`) VALUES
(1, 6, 1, 100, '2021-01-20 04:10:19'),
(2, 6, 2, 90, '2021-01-20 04:10:19'),
(3, 6, 3, 80, '2021-01-20 04:10:19'),
(4, 6, 4, 50, '2021-01-20 04:10:19'),
(5, 6, 5, 50, '2021-01-20 04:10:19'),
(6, 6, 6, 50, '2021-01-20 04:10:19'),
(7, 6, 7, 50, '2021-01-20 04:10:19'),
(8, 6, 8, 50, '2021-01-20 04:10:19'),
(9, 6, 9, 50, '2021-01-20 04:10:19'),
(10, 6, 10, 50, '2021-01-20 04:10:19'),
(11, 4, 1, 50, '2021-01-20 04:33:56'),
(12, 4, 2, 30, '2021-01-20 04:33:56'),
(13, 4, 3, 10, '2021-01-20 04:33:56'),
(14, 4, 4, 10, '2021-01-20 04:33:56'),
(15, 3, 3, 10, '2021-01-20 04:33:56'),
(16, 3, 1, 100, '2021-01-22 03:37:24'),
(17, 3, 2, 90, '2021-01-22 03:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `redeem_money`
--

CREATE TABLE `redeem_money` (
  `redeem_money_id` int(10) UNSIGNED NOT NULL,
  `redeem_coin_amt_min` double NOT NULL,
  `redeem_coin_amt_max` double DEFAULT 0,
  `redeem_cash_amt_min` double NOT NULL,
  `redeem_cash_amt_max` double DEFAULT 0,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `redeem_money`
--

INSERT INTO `redeem_money` (`redeem_money_id`, `redeem_coin_amt_min`, `redeem_coin_amt_max`, `redeem_cash_amt_min`, `redeem_cash_amt_max`, `updated_by`, `last_updated_at`) VALUES
(1, 100, 2000, 100, 5000, 1, '2021-02-04 06:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `refer_and_earn`
--

CREATE TABLE `refer_and_earn` (
  `refer_and_earn_id` int(10) UNSIGNED NOT NULL,
  `join_bonus_amount` double NOT NULL DEFAULT 0,
  `refer_bonus_amount` double NOT NULL DEFAULT 0,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `refer_and_earn`
--

INSERT INTO `refer_and_earn` (`refer_and_earn_id`, `join_bonus_amount`, `refer_bonus_amount`, `updated_by`, `last_updated_at`) VALUES
(1, 10, 20, 1, '2021-01-16 01:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `role_section_rights`
--

CREATE TABLE `role_section_rights` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_section_rights`
--

INSERT INTO `role_section_rights` (`id`, `role_id`, `section_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 1, 30),
(31, 1, 31),
(32, 1, 32),
(33, 1, 33),
(34, 1, 34),
(35, 1, 35),
(36, 1, 36),
(37, 1, 37),
(38, 1, 38),
(39, 1, 39),
(40, 1, 40),
(41, 1, 41),
(42, 1, 42),
(43, 1, 43),
(44, 1, 44),
(45, 1, 45),
(46, 1, 46),
(47, 1, 47),
(48, 1, 48),
(49, 1, 49),
(50, 1, 50),
(51, 1, 51),
(52, 1, 52),
(53, 1, 53),
(54, 1, 54),
(55, 1, 55),
(56, 1, 56),
(57, 1, 57),
(58, 1, 58);

-- --------------------------------------------------------

--
-- Table structure for table `section_rights`
--

CREATE TABLE `section_rights` (
  `id` int(10) UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pid` int(11) NOT NULL,
  `permission_pid` int(11) NOT NULL,
  `show_order` int(11) NOT NULL,
  `show_menu` tinyint(1) NOT NULL,
  `show_permission` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_rights`
--

INSERT INTO `section_rights` (`id`, `link`, `name`, `icon`, `pid`, `permission_pid`, `show_order`, `show_menu`, `show_permission`, `created_at`, `updated_at`) VALUES
(1, '/home', 'Dashboard', 'fa-desktop fas', 0, 1, 1, 0, 1, '2021-02-15 11:23:10', '2021-02-16 07:57:12'),
(2, '#', 'Admin Users', 'fas fa-user', 0, 0, 2, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:07'),
(3, '/admin/user/list', 'Manage Admin Users', 'far fa-circle', 2, 2, 2, 1, 1, '2021-02-15 11:23:10', '2021-02-16 07:57:18'),
(4, '/admin/user/create', 'Add Admin User', 'far fa-circle', 2, 2, 2, 1, 1, '2021-02-15 11:23:10', '2021-02-16 08:20:04'),
(5, '/admin/user/list/api', 'Manage Admin Users Api', 'far fa-circle', 2, 3, 2, 0, 0, '2021-02-15 11:23:10', '2021-02-16 07:41:06'),
(6, '/admin/user/update/*', 'Admin Users Update', 'far fa-circle', 2, 3, 2, 0, 0, '2021-02-15 11:23:10', '2021-02-16 07:41:11'),
(7, '/admin/user/delete/*', 'Admin Users Delete', 'far fa-circle', 2, 3, 2, 0, 0, '2021-02-15 11:23:10', '2021-02-16 07:41:23'),
(8, '/admin/user/view/*', 'Admin Users View', 'far fa-circle', 2, 3, 2, 0, 0, '2021-02-15 11:23:10', '2021-02-16 07:41:29'),
(9, '#', 'App Users', 'fas fa-user-friends', 0, 0, 3, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:28'),
(10, '/app/user/management', 'Manage App Users', 'far fa-circle', 9, 9, 3, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:30'),
(11, '/app/user/management/create', 'Add App User', 'far fa-circle', 9, 9, 3, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:33'),
(12, '/app/user/management/list/api', 'App User list Api', 'far fa-circle', 9, 10, 3, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:42:21'),
(13, '/app/user/management/update/*', 'App User update', 'far fa-circle', 9, 10, 3, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:42:27'),
(14, '/app/user/management/delete/*', 'App User delete', 'far fa-circle', 9, 10, 3, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:42:32'),
(15, '/app/user/view/*', 'App User View', 'far fa-circle', 9, 10, 3, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:42:36'),
(16, '#', 'Transactions', 'fas fa-money-bill-alt', 0, 0, 4, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:39'),
(17, '/cash/transaction/list', 'Cash Transaction Summary', 'far fa-circle', 16, 16, 4, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:46'),
(18, '/credit/cash/user', 'Credit Cash', 'far fa-circle', 16, 16, 4, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:49'),
(19, '/coin/transaction/list', 'Coin Transaction Summary', 'far fa-circle', 16, 16, 4, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:51'),
(20, '/credit/coin/user', 'Credit Coin', 'far fa-circle', 16, 16, 4, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:53'),
(21, '/coin/currency', 'Coin Currency', 'far fa-circle', 16, 16, 4, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:57:56'),
(22, '/cash/transaction/list/api', 'Cash Transaction List Api', 'far fa-circle', 16, 17, 4, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:43:58'),
(23, '/coin/transaction/list/api', 'Coin Transaction List Api', 'far fa-circle', 16, 19, 4, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:44:09'),
(24, '#', 'Quiz', 'fas fa-question-circle', 0, 0, 5, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:01'),
(25, '/quiz/category/list', 'Quiz Category', 'far fa-circle', 24, 24, 5, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:04'),
(26, '/quiz/category/question/list', 'Manage Quiz Creation', 'far fa-circle', 24, 24, 5, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:06'),
(27, '/quiz/category', 'Create Quiz category', 'far fa-circle', 24, 25, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:45:31'),
(28, '/quiz/category/list/api', 'Quiz category list Api', 'far fa-circle', 24, 25, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:46:00'),
(29, '/quiz/category/edit/*', 'Quiz category Edit', 'far fa-circle', 24, 25, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:46:05'),
(30, '/quiz/category/delete/*', 'Quiz category Delete', 'far fa-circle', 24, 25, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:46:09'),
(31, '/quiz/category/view/*', 'Quiz category View', 'far fa-circle', 24, 25, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:46:12'),
(32, '/quiz/reward/*', 'Quiz Reward', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:47:57'),
(33, '/quiz/reward/update/*', 'Quiz Reward Update', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:48:02'),
(34, '/quiz/group/delete/*', 'Quiz Reward Delete', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:48:42'),
(35, '/quiz/category/questions/create', 'Quiz Category Question Create', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:48:45'),
(36, '/quiz/category/questions/update/*', 'Quiz Category Question Update', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:48:49'),
(37, '/quiz/category/question/list/api', 'Quiz Category Question List Api', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:48:52'),
(38, '/quiz/category/question/view/*', 'Quiz Category Question View', 'far fa-circle', 24, 26, 5, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:48:56'),
(39, '#', 'Contest', 'fas fa-comments-dollar', 0, 0, 6, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:14'),
(40, '/contest/create', 'Contest Creation', 'far fa-circle', 39, 39, 6, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:17'),
(41, '/contest/summary', 'Manage Contest', 'far fa-circle', 39, 39, 6, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:19'),
(42, '/contest/summary/api', 'Contest Summary Api', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:49:41'),
(43, '/contest/edit/*', 'Contest Edit', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:49:45'),
(44, '/contest/question/edit/*', 'Contest Question Edit', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:49:47'),
(45, '/contest/question/create/*', 'Contest Question Create', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:49:50'),
(46, '/contest/delete/*', 'Contest Delete', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:50:32'),
(47, '/contest/view/*', 'Contest View', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:50:36'),
(48, '/contest/reward/*', 'Contest Reward', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:50:38'),
(49, '/contest/reward/update/*', 'Contest Reward UPdate', 'far fa-circle', 39, 41, 6, 0, 0, '2021-02-15 11:23:27', '2021-02-16 07:50:41'),
(50, '#', 'Configuration', 'fas fa-cogs', 0, 0, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:26'),
(51, '/daily/bonus/setting', 'Daily Bonus', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:28'),
(52, '/setting/watch/add/bonus', 'Watch Ad Bonus', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:31'),
(53, '/refer/and/earn/bonus', 'Refer And Earn', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:36'),
(54, '/redeem/money', 'Redeem Money', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:39'),
(55, '/create/required/page/privacy_policy', 'Required Page Creation', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:41'),
(56, '/setting/banner/popup', 'Banner And Popup', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:45'),
(57, '/admin/role/management', 'Assign Role Permission', 'far fa-circle', 50, 50, 7, 1, 1, '2021-02-15 11:23:27', '2021-02-16 07:58:49'),
(58, '/admin/get/section/list', 'Assign Role Permission Api', 'far fa-circle', 50, 57, 7, 0, 0, '2021-02-15 11:23:27', '2021-02-16 08:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(10) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`) VALUES
(1, 'Unapproved'),
(2, 'Approved'),
(3, 'Suspended'),
(4, 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `submit_answer`
--

CREATE TABLE `submit_answer` (
  `submit_id` int(10) UNSIGNED NOT NULL,
  `app_user_id` int(11) DEFAULT NULL,
  `quiz_ques_id` int(11) DEFAULT NULL,
  `contest_ques_id` int(10) UNSIGNED DEFAULT NULL,
  `result` tinyint(1) NOT NULL,
  `answering_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `submit_answer`
--

INSERT INTO `submit_answer` (`submit_id`, `app_user_id`, `quiz_ques_id`, `contest_ques_id`, `result`, `answering_time`, `points`, `created_at`) VALUES
(1, 11, 1, NULL, 1, NULL, NULL, '2021-01-28 00:50:46'),
(2, 11, 2, NULL, 1, NULL, NULL, '2021-01-28 00:50:46'),
(3, 11, 3, NULL, 0, NULL, NULL, '2021-01-28 00:50:46'),
(4, 11, NULL, 1, 1, '00:00:30', 20, '2021-02-03 13:12:43'),
(5, 11, NULL, 2, 0, '00:00:30', -5, '2021-02-04 06:13:12'),
(6, 3, NULL, 1, 1, '00:00:20', 10, '2021-02-03 07:16:31'),
(7, 3, NULL, 2, 0, '00:00:20', -5, '2021-02-04 06:14:20'),
(8, 3, NULL, 3, 1, '00:00:20', 10, '2021-02-03 07:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` int(6) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT 2,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `otp_code`, `email_verified_at`, `password`, `remember_token`, `status_id`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pragati Rastogi', 'pragati@enginyre.com', '8888888888', NULL, NULL, '$2y$10$TQ70t8xlU3vw9zvehUeyZuqfWZYd2jG2Gji6v.MlECukWH7pWH3jK', '8EkM8tJ4rgRpT6mRubbdVZCkfKl3ATOTcM9ZtFD55a5fNW6WZ02u1nWpl4Jx', 2, 'error_1609428327.png', '2020-12-24 04:19:36', '2021-04-06 09:41:05'),
(2, 1, 'Kajal', 'kajal2@testing.com', '', 0, NULL, '$2y$10$Os2989AttvdrDWxdozUv2etWKD..yVbMNYQ4PKSrktoQjjnGLfkjm', NULL, 2, NULL, '2020-12-31 07:40:23', '2021-01-07 02:27:45'),
(3, 1, 'testingforstatus', 'mail@test.com', '', 0, NULL, '$2y$10$CDzgz.DEw2RRH6pEGGACduB00A/SMoqbBGRQF/mZKhtUs0nq2Xraq', NULL, 2, 'edited_1450952777384_1609505389.jpg', '2021-01-01 06:49:29', '2021-01-01 07:19:49'),
(4, 1, 'kjwaiuwi', 'iuyoi@uyu.cion', '7897675658', 999999, NULL, '$2y$10$C4moF4NXzkWssbkEbZM2UO5UvBsDCRToKToS54PdMMNdNsgAF6IW6', '3oCnpjSM0ra99MGvdTEOxIMzk0PwgrbppktJMUAS8eWrrDA9l7jW4NmwcQPi', 2, '', '2021-01-23 12:51:45', '2021-01-25 01:09:20'),
(7, 1, 'testing2', 'testing@123.com', '9999999999', NULL, NULL, NULL, '3qh0wWfQBlkdpCXf1q39TumA3QEf6P0Y8sHvUbRGEcc6uiST7aQ0lZ1hXY8Q', 2, '', '2021-02-01 06:46:45', '2021-11-15 06:03:29');

-- --------------------------------------------------------

--
-- Table structure for table `user_references`
--

CREATE TABLE `user_references` (
  `reference_id` int(10) UNSIGNED NOT NULL,
  `app_user_id` int(11) NOT NULL,
  `joinee_id` int(11) NOT NULL COMMENT 'app user id who joined using refernce',
  `bonus_amount` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_references`
--

INSERT INTO `user_references` (`reference_id`, `app_user_id`, `joinee_id`, `bonus_amount`, `joined_at`) VALUES
(1, 11, 1, 20, '2021-01-27 12:12:46');

-- --------------------------------------------------------

--
-- Table structure for table `watch_ad_bonus`
--

CREATE TABLE `watch_ad_bonus` (
  `watch_ad_bonus_id` int(11) NOT NULL,
  `bonus_amount` double NOT NULL,
  `updated_by` int(11) NOT NULL,
  `last_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `watch_ad_bonus`
--

INSERT INTO `watch_ad_bonus` (`watch_ad_bonus_id`, `bonus_amount`, `updated_by`, `last_updated_at`) VALUES
(1, 10, 1, '2021-01-11 04:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `winnings`
--

CREATE TABLE `winnings` (
  `winning_id` int(10) UNSIGNED NOT NULL,
  `quiz_group_id` int(11) DEFAULT NULL,
  `contest_id` int(10) UNSIGNED DEFAULT NULL,
  `app_user_id` int(10) UNSIGNED NOT NULL,
  `position` int(11) NOT NULL,
  `rewarding_type` enum('coin','cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_rewarded` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `winnings`
--

INSERT INTO `winnings` (`winning_id`, `quiz_group_id`, `contest_id`, `app_user_id`, `position`, `rewarding_type`, `amount_rewarded`, `created_at`) VALUES
(4, 3, NULL, 11, 0, 'coin', 4, '2021-02-03 06:37:25'),
(7, NULL, 1, 3, 1, 'cash', 12, '2021-02-03 08:15:00'),
(8, NULL, 1, 11, 2, 'cash', 1, '2021-02-03 08:15:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_users`
--
ALTER TABLE `app_users`
  ADD PRIMARY KEY (`app_user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_wallet`
--
ALTER TABLE `cash_wallet`
  ADD PRIMARY KEY (`cash_wallet_id`),
  ADD KEY `app_user_id` (`app_user_id`);

--
-- Indexes for table `cash_wallet_transaction`
--
ALTER TABLE `cash_wallet_transaction`
  ADD PRIMARY KEY (`cash_wallet_trans_id`),
  ADD KEY `app_user_id` (`app_user_id`),
  ADD KEY `cash_wallet_id` (`cash_wallet_id`);

--
-- Indexes for table `coin_currency`
--
ALTER TABLE `coin_currency`
  ADD PRIMARY KEY (`coin_currency_id`);

--
-- Indexes for table `coin_wallet`
--
ALTER TABLE `coin_wallet`
  ADD PRIMARY KEY (`coin_wallet_id`),
  ADD KEY `app_user_id` (`app_user_id`);

--
-- Indexes for table `coin_wallet_transaction`
--
ALTER TABLE `coin_wallet_transaction`
  ADD PRIMARY KEY (`coin_wallet_trans_id`),
  ADD KEY `coin_wallet_id` (`coin_wallet_id`),
  ADD KEY `app_user_id` (`app_user_id`);

--
-- Indexes for table `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`contest_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `contest_question`
--
ALTER TABLE `contest_question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `contest_reward`
--
ALTER TABLE `contest_reward`
  ADD PRIMARY KEY (`contest_reward_id`),
  ADD KEY `prize_distribution_contest_id_foreign` (`contest_id`);

--
-- Indexes for table `daily_bonus`
--
ALTER TABLE `daily_bonus`
  ADD PRIMARY KEY (`bonus_id`),
  ADD KEY `daily_bonus_created_by_foreign` (`created_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_master`
--
ALTER TABLE `page_master`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `popup_notification`
--
ALTER TABLE `popup_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `processing_fees`
--
ALTER TABLE `processing_fees`
  ADD PRIMARY KEY (`processing_fees_id`);

--
-- Indexes for table `quiz_category`
--
ALTER TABLE `quiz_category`
  ADD PRIMARY KEY (`quiz_category_id`);

--
-- Indexes for table `quiz_group`
--
ALTER TABLE `quiz_group`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `quiz_group_ques`
--
ALTER TABLE `quiz_group_ques`
  ADD PRIMARY KEY (`ques_id`);

--
-- Indexes for table `quiz_ques_options`
--
ALTER TABLE `quiz_ques_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `quiz_reward`
--
ALTER TABLE `quiz_reward`
  ADD PRIMARY KEY (`quiz_reward_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `redeem_money`
--
ALTER TABLE `redeem_money`
  ADD PRIMARY KEY (`redeem_money_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `refer_and_earn`
--
ALTER TABLE `refer_and_earn`
  ADD PRIMARY KEY (`refer_and_earn_id`),
  ADD KEY `refer_and_earn_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_section_rights`
--
ALTER TABLE `role_section_rights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `section_rights`
--
ALTER TABLE `section_rights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submit_answer`
--
ALTER TABLE `submit_answer`
  ADD PRIMARY KEY (`submit_id`),
  ADD KEY `submit_answer_app_user_id_foreign` (`app_user_id`),
  ADD KEY `submit_answer_quiz_ques_id_foreign` (`quiz_ques_id`),
  ADD KEY `submit_answer_contest_ques_id_foreign` (`contest_ques_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `user_references`
--
ALTER TABLE `user_references`
  ADD PRIMARY KEY (`reference_id`),
  ADD KEY `user_references_app_user_id_foreign` (`app_user_id`),
  ADD KEY `user_references_joinee_id_foreign` (`joinee_id`);

--
-- Indexes for table `watch_ad_bonus`
--
ALTER TABLE `watch_ad_bonus`
  ADD PRIMARY KEY (`watch_ad_bonus_id`);

--
-- Indexes for table `winnings`
--
ALTER TABLE `winnings`
  ADD PRIMARY KEY (`winning_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_users`
--
ALTER TABLE `app_users`
  MODIFY `app_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cash_wallet`
--
ALTER TABLE `cash_wallet`
  MODIFY `cash_wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cash_wallet_transaction`
--
ALTER TABLE `cash_wallet_transaction`
  MODIFY `cash_wallet_trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `coin_currency`
--
ALTER TABLE `coin_currency`
  MODIFY `coin_currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coin_wallet`
--
ALTER TABLE `coin_wallet`
  MODIFY `coin_wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `coin_wallet_transaction`
--
ALTER TABLE `coin_wallet_transaction`
  MODIFY `coin_wallet_trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contest`
--
ALTER TABLE `contest`
  MODIFY `contest_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contest_question`
--
ALTER TABLE `contest_question`
  MODIFY `question_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contest_reward`
--
ALTER TABLE `contest_reward`
  MODIFY `contest_reward_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `daily_bonus`
--
ALTER TABLE `daily_bonus`
  MODIFY `bonus_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `page_master`
--
ALTER TABLE `page_master`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `popup_notification`
--
ALTER TABLE `popup_notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `processing_fees`
--
ALTER TABLE `processing_fees`
  MODIFY `processing_fees_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_category`
--
ALTER TABLE `quiz_category`
  MODIFY `quiz_category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_group`
--
ALTER TABLE `quiz_group`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quiz_group_ques`
--
ALTER TABLE `quiz_group_ques`
  MODIFY `ques_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `quiz_ques_options`
--
ALTER TABLE `quiz_ques_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `quiz_reward`
--
ALTER TABLE `quiz_reward`
  MODIFY `quiz_reward_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `redeem_money`
--
ALTER TABLE `redeem_money`
  MODIFY `redeem_money_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `refer_and_earn`
--
ALTER TABLE `refer_and_earn`
  MODIFY `refer_and_earn_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role_section_rights`
--
ALTER TABLE `role_section_rights`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `section_rights`
--
ALTER TABLE `section_rights`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `submit_answer`
--
ALTER TABLE `submit_answer`
  MODIFY `submit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_references`
--
ALTER TABLE `user_references`
  MODIFY `reference_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `watch_ad_bonus`
--
ALTER TABLE `watch_ad_bonus`
  MODIFY `watch_ad_bonus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `winnings`
--
ALTER TABLE `winnings`
  MODIFY `winning_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app_users`
--
ALTER TABLE `app_users`
  ADD CONSTRAINT `app_users_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_wallet`
--
ALTER TABLE `cash_wallet`
  ADD CONSTRAINT `cash_wallet_ibfk_1` FOREIGN KEY (`app_user_id`) REFERENCES `app_users` (`app_user_id`);

--
-- Constraints for table `cash_wallet_transaction`
--
ALTER TABLE `cash_wallet_transaction`
  ADD CONSTRAINT `cash_wallet_transaction_ibfk_1` FOREIGN KEY (`cash_wallet_id`) REFERENCES `cash_wallet` (`cash_wallet_id`),
  ADD CONSTRAINT `cash_wallet_transaction_ibfk_2` FOREIGN KEY (`app_user_id`) REFERENCES `app_users` (`app_user_id`) ON DELETE CASCADE;

--
-- Constraints for table `coin_wallet`
--
ALTER TABLE `coin_wallet`
  ADD CONSTRAINT `coin_wallet_ibfk_1` FOREIGN KEY (`app_user_id`) REFERENCES `app_users` (`app_user_id`);

--
-- Constraints for table `coin_wallet_transaction`
--
ALTER TABLE `coin_wallet_transaction`
  ADD CONSTRAINT `coin_wallet_transaction_ibfk_1` FOREIGN KEY (`coin_wallet_id`) REFERENCES `coin_wallet` (`coin_wallet_id`),
  ADD CONSTRAINT `coin_wallet_transaction_ibfk_2` FOREIGN KEY (`app_user_id`) REFERENCES `app_users` (`app_user_id`) ON DELETE CASCADE;

--
-- Constraints for table `contest_reward`
--
ALTER TABLE `contest_reward`
  ADD CONSTRAINT `prize_distribution_contest_id_foreign` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`contest_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `daily_bonus`
--
ALTER TABLE `daily_bonus`
  ADD CONSTRAINT `daily_bonus_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_reward`
--
ALTER TABLE `quiz_reward`
  ADD CONSTRAINT `quiz_reward_quiz_id_foriegn` FOREIGN KEY (`quiz_id`) REFERENCES `quiz_group` (`group_id`);

--
-- Constraints for table `refer_and_earn`
--
ALTER TABLE `refer_and_earn`
  ADD CONSTRAINT `refer_and_earn_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submit_answer`
--
ALTER TABLE `submit_answer`
  ADD CONSTRAINT `submit_answer_app_user_id_foreign` FOREIGN KEY (`app_user_id`) REFERENCES `app_users` (`app_user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submit_answer_contest_ques_id_foreign` FOREIGN KEY (`contest_ques_id`) REFERENCES `contest_question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submit_answer_quiz_ques_id_foreign` FOREIGN KEY (`quiz_ques_id`) REFERENCES `quiz_group_ques` (`ques_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Constraints for table `user_references`
--
ALTER TABLE `user_references`
  ADD CONSTRAINT `user_references_app_user_id_foreign` FOREIGN KEY (`app_user_id`) REFERENCES `app_users` (`app_user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_references_joinee_id_foreign` FOREIGN KEY (`joinee_id`) REFERENCES `app_users` (`app_user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
