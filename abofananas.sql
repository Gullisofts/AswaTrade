-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2021 at 08:00 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abofananas`
--

-- --------------------------------------------------------

--
-- Table structure for table `blacklists`
--

CREATE TABLE `blacklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(255) NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coffes`
--

CREATE TABLE `coffes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `invalidlogins` bigint(20) NOT NULL,
  `starttime` bigint(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coffes`
--

INSERT INTO `coffes` (`id`, `username`, `password`, `invalidlogins`, `starttime`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$ibUo0vj.PbvI9F5LXzZCieG/jtTDR/oETsZ8iH2HTzskecilbl/MW', 0, NULL, NULL, '2021-07-31 11:21:45');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `mainsections`
--

CREATE TABLE `mainsections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `msection_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_img` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mainsections`
--

INSERT INTO `mainsections` (`id`, `msection_name`, `section_img`, `description`, `created_at`, `updated_at`) VALUES
(2, 'الأجهزة', '1627481054.jpg', 'هذا القسم مختص بالأجهزة والمعدات', '2021-04-13 23:03:14', '2021-07-28 12:04:14'),
(8, 'الأغذية', '1627471437.jpg', 'هذا القسم خاص بالأغذية', '2021-05-15 16:47:37', '2021-07-28 09:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `governorate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_time` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `block_status` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `ip`, `fullname`, `email`, `phone`, `password`, `address1`, `address2`, `governorate`, `city`, `zip`, `reset_token`, `reset_time`, `verification_token`, `verification_status`, `block_status`, `created_at`, `updated_at`) VALUES
(1, '41.42.228.54', 'Salim Salim Salim', 'test@test.com', '01111111111', '$2y$10$CU0eHGbRYM/v9qE296QVk.KJ9kb/FKlaQq5p/f6D32kMyT/wso/oK', 'قنا', 'قنا', 'قنا', 'قنا', '8888', NULL, NULL, NULL, 'true', NULL, '2021-07-28 18:24:03', '2021-07-29 17:12:45');

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
(11, '2021_03_17_171441_create_products_table', 5),
(13, '2021_04_11_120406_create_comments_table', 7),
(14, '2021_04_13_001537_create_ratings_table', 8),
(16, '2021_03_17_171530_create_sections_table', 9),
(17, '2021_04_13_225948_create_mainsections_table', 9),
(18, '2021_04_03_084916_create_shipments_table', 10),
(19, '2021_05_16_031831_create_suppliers_table', 11),
(21, '2021_03_26_104710_create_members_table', 12),
(22, '2021_06_22_161235_create_blacklist_table', 12),
(23, '2021_07_03_122434_create_pages_table', 13),
(25, '2021_07_12_162427_create_tickets_table', 14),
(27, '2021_03_26_104545_create_coffes_table', 16),
(28, '2021_04_10_010443_create_tags_table', 17),
(30, '2021_07_17_184145_create_settings_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `position`, `created_at`, `updated_at`) VALUES
(1, 'من نحن', '<p style=\"text-align: center; \"><b><span style=\"font-size: 18px;\">بسم الله الرحمن الرحيم</span></b></p><p style=\"margin-bottom: var(--site-spacing); word-break: break-word; line-height: 2;\"><b>تأسس موقع التواصل الاجتماعي ……… في عام …….. ويسعى إلى جعل العالم أكثر انفتاحًا وترابطًا من خلال منح الأشخاص القدرة على التواصل مع بعضهم البعض بشكل مريح دون حواجز أو عقبات، حيث تتجاوز طبيعته العالمية حدود الجغرافيا والأعراق.</b></p><p style=\"margin-bottom: var(--site-spacing); word-break: break-word; line-height: 2;\"><b>يقدم الموقع مجموعة من الخدمات الاجتماعية الفريدة، يتيح لك بسهولة إنشاء حساب شخصي يحتوي على معلوماتك الخاصة، ثم البدء في التواصل مع أصدقائك وإجراء محادثة فورية معهم، فضلاً عن إنشاء مجموعات والعديد من الميزات الرائعة الأخرى.</b></p><p style=\"margin-bottom: var(--site-spacing); word-break: break-word; line-height: 2;\"><b>إنه بمثابة نافذة على العالم يمكنك من خلالها معرفة ما يحدث فيه ولماذا لا! شارك الأحداث الخاصة بك.</b></p>', '2', '2021-07-04 10:33:13', '2021-07-04 14:26:44'),
(6, 'اتصل بنا', '<p>اتصل بنا<br></p>', '3', '2021-07-04 13:53:47', '2021-07-04 14:26:44'),
(7, 'سياسة الخصوصية', '<p>سياسة الخصوصية<br></p>', '1', '2021-07-04 13:56:09', '2021-07-04 14:26:44');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mainsectionid` bigint(150) NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `productname` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `produnit` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodcode` bigint(150) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `spec` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `productimages` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `discount` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `qty` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(150) DEFAULT NULL,
  `sales` int(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `mainsectionid`, `section_id`, `productname`, `produnit`, `prodcode`, `description`, `spec`, `manufacturer`, `productimages`, `price`, `discount`, `qty`, `rating`, `sales`, `created_at`, `updated_at`) VALUES
(40, 2, 5, 'Dell G5 5500 Gaming Laptop', 'قطعة', 971740513138064, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Dell', '/images/products/2/5/main_1627349096aBpbT.jpg__xYY7931YYx__/images/products/2/5/1627470211tnm61.jpg__xYY7931YYx__/images/products/2/5/1627470211RRxW6.jpg', 5780, '5', '20', 5, 0, '2021-07-26 23:24:56', '2021-07-31 15:56:06'),
(41, 2, 5, 'Lenovo IdeaPad Gaming 3 15ARH05 Laptop', 'قطعة', 157400342995644, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Lenovo', '/images/products/2/5/main_1627349233VT9GV.jpg__xYY7931YYx__/images/products/2/5/1627349233CUi3u.jpg__xYY7931YYx__/images/products/2/5/16273492333fvoP.jpg', 6842, '0', '20', 1, 0, '2021-07-26 23:27:13', '2021-07-30 20:04:17'),
(42, 2, 5, 'Lenovo V14 Laptop - Athlon Gold 3150U', 'قطعة', 51048795766961, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Lenovo', '/images/products/2/5/main_1627349331QfFyQ.jpg__xYY7931YYx__/images/products/2/5/1627349331qLGU4.jpg__xYY7931YYx__/images/products/2/5/16273493319v2zZ.jpg', 15700, '7', '20', 5, 0, '2021-07-26 23:28:51', '2021-07-30 20:04:17'),
(43, 2, 5, 'HP 15-dw3048ne Laptop', 'قطعة', 579424344497730, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصة762171578وصف الخاصية597127912719الخاصة762171578وصف الخاصية597127912719الخاصة762171578وصف الخاصية597127912719الخاصة762171578وصف الخاصية597127912719', 'HP', '/images/products/2/5/main_1627349406nHeZH.jpg__xYY7931YYx__/images/products/2/5/1627349406ofBSq.jpg__xYY7931YYx__/images/products/2/5/1627349406khO3K.jpg', 7530, '0', '20', 1, 0, '2021-07-26 23:30:06', '2021-07-30 20:04:17'),
(44, 2, 5, 'Dell Vostro 3500 laptop', 'قطعة', 17690016778419, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Dell', '/images/products/2/5/main_1627349480sVGsN.jpg__xYY7931YYx__/images/products/2/5/1627349480dG0hr.jpg__xYY7931YYx__/images/products/2/5/1627349480ghtYX.jpg', 6842, '6', '20', 1, 0, '2021-07-26 23:31:20', '2021-07-30 20:04:17'),
(45, 2, 3, 'بوش مطرقة دوراة مع اس دي اس-بلس', 'قطعة', 627253793299234, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'بوش', '/images/products/2/3/main_1627349548FTyvX.jpg__xYY7931YYx__/images/products/2/3/1627349548Vuja4.jpg__xYY7931YYx__/images/products/2/3/16273495489Oka8.jpg', 370, '2', '19', 1, 1, '2021-07-26 23:32:28', '2021-07-31 11:50:41'),
(46, 2, 3, 'مطرقة آلية من شركة بوش - موديل GBH 2000', 'قطعة', 375997753969961, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'بوش', '/images/products/2/3/main_16273496043fw3B.jpg__xYY7931YYx__/images/products/2/3/1627349604CDRtL.jpg__xYY7931YYx__/images/products/2/3/1627349604CqcrK.jpg', 560, '0', '20', 1, 0, '2021-07-26 23:33:24', '2021-07-30 20:04:17'),
(47, 2, 3, 'مطرقة دوارة من اي بي تيDw-32Et 2 – 1250 وات', 'قطعة', 285009566761491, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'اي بي تي', '/images/products/2/3/main_1627349664gS7pN.jpg__xYY7931YYx__/images/products/2/3/1627349664nkDve.jpg__xYY7931YYx__/images/products/2/3/1627349664FYTWV.jpg', 350, '10', '20', 5, 0, '2021-07-26 23:34:24', '2021-07-30 20:04:17'),
(48, 2, 3, 'مطرقة دوارة من انجكو RH-150028 - 1500 وات', 'قطعة', 405676401885076, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'انجكو', '/images/products/2/3/main_1627349716S4rBV.jpg__xYY7931YYx__/images/products/2/3/16273497164Ka8d.jpg__xYY7931YYx__/images/products/2/3/1627349716bqfTV.jpg', 890, '0', '20', 1, 0, '2021-07-26 23:35:16', '2021-07-30 20:04:17'),
(49, 2, 5, 'Dell G5 5500 Gaming Laptop', 'قطعة', 971740513138064, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Dell', '/images/products/2/5/main_1627349096aBpbT.jpg__xYY7931YYx__/images/products/2/5/1627470211tnm61.jpg__xYY7931YYx__/images/products/2/5/1627470211RRxW6.jpg', 5780, '5', '20', 1, 0, '2021-07-26 23:24:56', '2021-07-30 20:04:17'),
(50, 2, 5, 'Lenovo IdeaPad Gaming 3 15ARH05 Laptop', 'قطعة', 157400342995644, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Lenovo', '/images/products/2/5/main_1627349233VT9GV.jpg__xYY7931YYx__/images/products/2/5/1627349233CUi3u.jpg__xYY7931YYx__/images/products/2/5/16273492333fvoP.jpg', 6842, '0', '20', 1, 0, '2021-07-26 23:27:13', '2021-07-30 20:04:17'),
(51, 2, 5, 'Lenovo V14 Laptop - Athlon Gold 3150U', 'قطعة', 51048795766961, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Lenovo', '/images/products/2/5/main_1627349331QfFyQ.jpg__xYY7931YYx__/images/products/2/5/1627349331qLGU4.jpg__xYY7931YYx__/images/products/2/5/16273493319v2zZ.jpg', 15700, '7', '20', 5, 0, '2021-07-26 23:28:51', '2021-07-30 20:04:17'),
(52, 2, 5, 'HP 15-dw3048ne Laptop', 'قطعة', 579424344497730, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصة762171578وصف الخاصية597127912719الخاصة762171578وصف الخاصية597127912719الخاصة762171578وصف الخاصية597127912719الخاصة762171578وصف الخاصية597127912719', 'HP', '/images/products/2/5/main_1627349406nHeZH.jpg__xYY7931YYx__/images/products/2/5/1627349406ofBSq.jpg__xYY7931YYx__/images/products/2/5/1627349406khO3K.jpg', 7530, '0', '20', 1, 0, '2021-07-26 23:30:06', '2021-07-30 20:04:17'),
(53, 2, 5, 'Dell Vostro 3500 laptop', 'قطعة', 17690016778419, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'Dell', '/images/products/2/5/main_1627349480sVGsN.jpg__xYY7931YYx__/images/products/2/5/1627349480dG0hr.jpg__xYY7931YYx__/images/products/2/5/1627349480ghtYX.jpg', 6842, '6', '20', 1, 0, '2021-07-26 23:31:20', '2021-07-30 20:04:17'),
(54, 2, 3, 'بوش مطرقة دوراة مع اس دي اس-بلس', 'قطعة', 627253793299234, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'بوش', '/images/products/2/3/main_1627349548FTyvX.jpg__xYY7931YYx__/images/products/2/3/1627349548Vuja4.jpg__xYY7931YYx__/images/products/2/3/16273495489Oka8.jpg', 370, '2', '20', 1, 0, '2021-07-26 23:32:28', '2021-07-30 20:04:17'),
(55, 2, 3, 'مطرقة آلية من شركة بوش - موديل GBH 2000', 'قطعة', 375997753969961, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'بوش', '/images/products/2/3/main_16273496043fw3B.jpg__xYY7931YYx__/images/products/2/3/1627349604CDRtL.jpg__xYY7931YYx__/images/products/2/3/1627349604CqcrK.jpg', 560, '0', '20', 1, 0, '2021-07-26 23:33:24', '2021-07-30 20:04:17'),
(56, 2, 3, 'مطرقة دوارة من اي بي تيDw-32Et 2 – 1250 وات', 'قطعة', 285009566761491, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'اي بي تي', '/images/products/2/3/main_1627349664gS7pN.jpg__xYY7931YYx__/images/products/2/3/1627349664nkDve.jpg__xYY7931YYx__/images/products/2/3/1627349664FYTWV.jpg', 350, '10', '20', 5, 0, '2021-07-26 23:34:24', '2021-07-30 20:04:17'),
(57, 2, 3, 'مطرقة دوارة من انجكو RH-150028 - 1500 وات', 'قطعة', 405676401885076, 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719الخاصية762171578وصف الخاصية597127912719', 'انجكو', '/images/products/2/3/main_1627349716S4rBV.jpg__xYY7931YYx__/images/products/2/3/16273497164Ka8d.jpg__xYY7931YYx__/images/products/2/3/1627349716bqfTV.jpg', 890, '0', '20', 1, 0, '2021-07-26 23:35:16', '2021-07-30 20:04:17');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `product_id`, `rating`, `created_at`, `updated_at`) VALUES
(10, 1, 40, 5, '2021-07-31 15:56:05', '2021-07-31 15:56:05');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mainsection_id` int(10) UNSIGNED NOT NULL,
  `section_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `mainsection_id`, `section_name`, `description`, `created_at`, `updated_at`) VALUES
(3, 2, 'المعدات والأدوات', 'هذا القسم يضم منتجات النجارة', '2021-04-13 23:04:27', '2021-07-28 12:04:14'),
(5, 2, 'اللابتوبات', 'هذا القسم يضم منتجات اللابتوبات', '2021-04-13 23:04:27', '2021-07-28 12:04:14'),
(86, 8, 'اللحوم', 'هذا القسم خاص باللحوم', '2021-05-15 16:47:37', '2021-07-28 09:29:16'),
(87, 8, 'المعلبات', 'هذا القسم خاص بالمعلبات', '2021-05-15 16:47:37', '2021-07-28 09:29:16'),
(96, 2, 'أدوات النجارة', 'هذا القسم يضم أدوات النجارة', '2021-07-28 12:03:51', '2021-07-28 12:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sitename` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sitestatus` smallint(6) NOT NULL,
  `registerstatus` smallint(6) NOT NULL,
  `revisecomments` smallint(150) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sitelink` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `footerdescrip` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `emails` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phones` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonestatus` smallint(6) NOT NULL,
  `emailstatus` smallint(6) NOT NULL,
  `facebooklink` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitterlink` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagramlink` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `sitename`, `sitestatus`, `registerstatus`, `revisecomments`, `currency`, `regions`, `sitelink`, `footerdescrip`, `emails`, `phones`, `phonestatus`, `emailstatus`, `facebooklink`, `twitterlink`, `instagramlink`, `created_at`, `updated_at`) VALUES
(1, 'سوق ابو كلاوي', 1, 1, 1, 'ج.م', 'الإسكندرية_xXnextarrayelxX_10_xXnextarrayelxX_الإسماعيلية_xXnextarrayelxX_10_xXnextarrayelxX_أسوان_xXnextarrayelxX_10_xXnextarrayelxX_أسيوط_xXnextarrayelxX_10_xXnextarrayelxX_الأقصر_xXnextarrayelxX_10_xXnextarrayelxX_البحر الأحمر_xXnextarrayelxX_10_xXnextarrayelxX_البحيرة_xXnextarrayelxX_10_xXnextarrayelxX_بني سويف_xXnextarrayelxX_10_xXnextarrayelxX_بورسعيد_xXnextarrayelxX_10_xXnextarrayelxX_جنوب سيناء_xXnextarrayelxX_10_xXnextarrayelxX_الجيزة_xXnextarrayelxX_10_xXnextarrayelxX_الدقهلية_xXnextarrayelxX_10_xXnextarrayelxX_دمياط_xXnextarrayelxX_10_xXnextarrayelxX_سوهاج_xXnextarrayelxX_10_xXnextarrayelxX_السويس_xXnextarrayelxX_10_xXnextarrayelxX_الشرقية_xXnextarrayelxX_10_xXnextarrayelxX_شمال سيناء_xXnextarrayelxX_10_xXnextarrayelxX_الغربية_xXnextarrayelxX_10_xXnextarrayelxX_الفيوم_xXnextarrayelxX_10_xXnextarrayelxX_القاهرة_xXnextarrayelxX_10_xXnextarrayelxX_القليوبية_xXnextarrayelxX_10_xXnextarrayelxX_قنا_xXnextarrayelxX_10_xXnextarrayelxX_كفر الشيخ_xXnextarrayelxX_10_xXnextarrayelxX_مطروح_xXnextarrayelxX_10_xXnextarrayelxX_المنوفية_xXnextarrayelxX_10_xXnextarrayelxX_المنيا_xXnextarrayelxX_10_xXnextarrayelxX_الوادي الجديد_xXnextarrayelxX_10_xXnextarrayelxX_', 'www.test.com', 'لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات', 'support@test.com_xDxCv_support2@test.com_xDxCv_support3@test.com', '01111111111_xDxCv_01000000000_xDxCv_01555555555', 1, 1, 'http://www.facebook.com', 'http://www.twitter.com', 'http://www.instagram.com', '2021-07-27 01:12:18', '2021-07-29 13:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `shipmentno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rejectcause` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `member_id`, `shipmentno`, `transaction_id`, `content`, `cost`, `status`, `rejectcause`, `created_at`, `updated_at`) VALUES
(113, 1, '15536859591258567241', 'pod', '40qty_xX913172xX_1', '5501', 'prepare', NULL, '2021-07-31 11:30:11', '2021-07-31 12:16:25'),
(114, 1, '36959893328310018987', 'pod', '45qty_xX913172xX_1', '372.6', 'prepare', NULL, '2021-07-31 11:30:40', '2021-07-31 12:16:25'),
(115, 1, '93710637323813032533', '8ac7a49f7af72a03017afcc3b5a93510', '40qty_xX913172xX_1', '5501', 'prepare', NULL, '2021-07-31 11:32:05', '2021-07-31 12:16:55'),
(116, 1, '22780538621699724235', '8ac7a49f7af72a03017afcc454f735b3', '45qty_xX913172xX_1', '372.6', 'prepare', NULL, '2021-07-31 11:32:46', '2021-07-31 12:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `product_id`, `tag`, `created_at`, `updated_at`) VALUES
(5, 41, 'Lenovo', '2021-07-26 23:27:14', '2021-07-26 23:27:14'),
(6, 41, 'IdeaPad', '2021-07-26 23:27:14', '2021-07-26 23:27:14'),
(7, 41, 'Gaming', '2021-07-26 23:27:14', '2021-07-26 23:27:14'),
(8, 41, '15ARH05', '2021-07-26 23:27:14', '2021-07-26 23:27:14'),
(9, 41, 'Laptop', '2021-07-26 23:27:14', '2021-07-26 23:27:14'),
(16, 43, 'HP', '2021-07-26 23:30:06', '2021-07-26 23:30:06'),
(17, 43, 'Laptop', '2021-07-26 23:30:06', '2021-07-26 23:30:06'),
(18, 44, 'Dell', '2021-07-26 23:31:20', '2021-07-26 23:31:20'),
(19, 44, 'Vostro', '2021-07-26 23:31:20', '2021-07-26 23:31:20'),
(20, 44, '3500', '2021-07-26 23:31:20', '2021-07-26 23:31:20'),
(21, 44, 'laptop', '2021-07-26 23:31:21', '2021-07-26 23:31:21'),
(22, 45, 'بوش', '2021-07-26 23:32:28', '2021-07-26 23:32:28'),
(23, 45, 'مطرقة', '2021-07-26 23:32:28', '2021-07-26 23:32:28'),
(24, 45, 'دوراة', '2021-07-26 23:32:28', '2021-07-26 23:32:28'),
(25, 45, 'مع', '2021-07-26 23:32:29', '2021-07-26 23:32:29'),
(26, 45, 'اس', '2021-07-26 23:32:29', '2021-07-26 23:32:29'),
(27, 45, 'دي', '2021-07-26 23:32:29', '2021-07-26 23:32:29'),
(28, 46, 'مطرقة', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(29, 46, 'آلية', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(30, 46, 'من', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(31, 46, 'شركة', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(32, 46, 'بوش', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(33, 46, 'موديل', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(34, 46, 'GBH', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(35, 46, '2000', '2021-07-26 23:33:24', '2021-07-26 23:33:24'),
(36, 47, 'مطرقة', '2021-07-26 23:34:24', '2021-07-26 23:34:24'),
(37, 47, 'دوارة', '2021-07-26 23:34:24', '2021-07-26 23:34:24'),
(38, 47, 'من', '2021-07-26 23:34:24', '2021-07-26 23:34:24'),
(39, 47, 'اي', '2021-07-26 23:34:24', '2021-07-26 23:34:24'),
(40, 47, 'بي', '2021-07-26 23:34:24', '2021-07-26 23:34:24'),
(41, 47, '1250', '2021-07-26 23:34:24', '2021-07-26 23:34:24'),
(42, 47, 'وات', '2021-07-26 23:34:25', '2021-07-26 23:34:25'),
(43, 48, 'مطرقة', '2021-07-26 23:35:16', '2021-07-26 23:35:16'),
(44, 48, 'دوارة', '2021-07-26 23:35:16', '2021-07-26 23:35:16'),
(45, 48, 'من', '2021-07-26 23:35:16', '2021-07-26 23:35:16'),
(46, 48, '1500', '2021-07-26 23:35:17', '2021-07-26 23:35:17'),
(47, 48, 'وات', '2021-07-26 23:35:17', '2021-07-26 23:35:17'),
(48, 40, 'Dell', '2021-07-28 09:03:32', '2021-07-28 09:03:32'),
(49, 40, '5500', '2021-07-28 09:03:32', '2021-07-28 09:03:32'),
(50, 40, 'Gaming', '2021-07-28 09:03:32', '2021-07-28 09:03:32'),
(51, 40, 'Laptop', '2021-07-28 09:03:32', '2021-07-28 09:03:32'),
(52, 42, 'Lenovo', '2021-07-28 09:05:17', '2021-07-28 09:05:17'),
(53, 42, 'V14', '2021-07-28 09:05:17', '2021-07-28 09:05:17'),
(54, 42, 'Laptop', '2021-07-28 09:05:17', '2021-07-28 09:05:17'),
(55, 42, 'Athlon', '2021-07-28 09:05:17', '2021-07-28 09:05:17'),
(56, 42, 'Gold', '2021-07-28 09:05:17', '2021-07-28 09:05:17'),
(57, 42, '3150U', '2021-07-28 09:05:17', '2021-07-28 09:05:17');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `member_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_id`, `member_id`, `subject`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 77882481062, '1', 'test test', 'test test test test test test test test', 'unanswered', '2021-07-31 17:59:00', '2021-07-31 17:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blacklists`
--
ALTER TABLE `blacklists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coffes`
--
ALTER TABLE `coffes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `mainsections`
--
ALTER TABLE `mainsections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blacklists`
--
ALTER TABLE `blacklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `coffes`
--
ALTER TABLE `coffes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mainsections`
--
ALTER TABLE `mainsections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
