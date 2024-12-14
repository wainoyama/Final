-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 01:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calabarzon_harvest_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `calabarzon_topography`
--

CREATE TABLE `calabarzon_topography` (
  `id` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `location` text NOT NULL,
  `topography` text NOT NULL,
  `soil_types` text NOT NULL,
  `agricultural_relevance` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calabarzon_topography`
--

INSERT INTO `calabarzon_topography` (`id`, `province`, `location`, `topography`, `soil_types`, `agricultural_relevance`) VALUES
(1, 'Cavite', 'Western part of CALABARZON, bordered by Manila Bay, Laguna, and Batangas.', 'Lowlands along Manila Bay; central plains and rolling hills; Tagaytay Ridge as highland (~600-700m elevation).', 'Volcanic loam in highlands; sandy soil in coastal areas.', 'Cash crops like coffee and pineapples; urban farming near Metro Manila.'),
(2, 'Laguna', 'Encircles Laguna de Bay; bordered by Rizal, Cavite, Batangas, and Quezon.', 'Laguna de Bay basin for flatlands; rolling hills and Mount Makiling (~1,090m elevation).', 'Alluvial soil in lowlands; volcanic loam in uplands.', 'Rice in lowlands; coconuts and lanzones in uplands; aquaculture around Laguna de Bay.'),
(3, 'Batangas', 'Southwestern CALABARZON with rugged coastline along West Philippine Sea.', 'Coastal plains near Batangas Bay; rolling hills; Taal Volcano area (~311m elevation).', 'Volcanic soil near Taal; sandy loam in coastal areas.', 'Kapeng Barako coffee, mangoes, and sugarcane; aquaculture in coastal zones.'),
(4, 'Rizal', 'Northeast of Metro Manila, bordered by Sierra Madre and Laguna.', 'Lowlands in the west; Sierra Madre range (~500-1,500m elevation) in the east.', 'Clay loam in lowlands; forest soil in uplands.', 'Bananas, coconuts, and root crops in uplands; horticulture in lowlands.'),
(5, 'Quezon', 'Largest CALABARZON province, stretching from coastal bays to Mount Banahaw.', 'Coastal plains along Lamon and Tayabas Bays; uplands and Mount Banahaw (~2,170m elevation).', 'Alluvial soil in coastal plains; volcanic loam near Mount Banahaw.', 'Coconut production, root crops, and rice in lowlands; fishing in coastal areas.');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

CREATE TABLE `group_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_post_comments`
--

CREATE TABLE `group_post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_post_likes`
--

CREATE TABLE `group_post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_stocks`
--

CREATE TABLE `market_stocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_stocks`
--

INSERT INTO `market_stocks` (`id`, `product_id`, `price`, `region_id`) VALUES
(1, 1, 40.00, 1),
(2, 2, 35.00, 1),
(3, 3, 50.00, 1),
(4, 4, 30.00, 1),
(5, 5, 45.00, 1),
(6, 6, 60.00, 1),
(7, 7, 80.00, 1),
(8, 8, 25.00, 1),
(9, 9, 40.00, 1),
(10, 10, 100.00, 1),
(11, 11, 60.00, 1),
(12, 12, 100.00, 1),
(13, 13, 200.00, 1),
(14, 14, 150.00, 1),
(15, 1, 45.00, 2),
(16, 2, 38.00, 2),
(17, 3, 55.00, 2),
(18, 4, 40.00, 2),
(19, 6, 65.00, 2),
(20, 7, 90.00, 2),
(21, 15, 75.00, 2),
(22, 8, 30.00, 2),
(23, 9, 40.00, 2),
(24, 16, 70.00, 2),
(25, 11, 65.00, 2),
(26, 12, 120.00, 2),
(27, 13, 250.00, 2),
(28, 14, 180.00, 2),
(29, 1, 48.00, 3),
(30, 2, 40.00, 3),
(31, 3, 58.00, 3),
(32, 4, 42.00, 3),
(33, 5, 47.00, 3),
(34, 6, 70.00, 3),
(35, 7, 95.00, 3),
(36, 17, 65.00, 3),
(37, 15, 80.00, 3),
(38, 8, 33.00, 3),
(39, 9, 45.00, 3),
(40, 16, 72.00, 3),
(41, 10, 85.00, 3),
(42, 11, 68.00, 3),
(43, 12, 110.00, 3),
(44, 13, 220.00, 3),
(45, 14, 160.00, 3),
(46, 1, 50.00, 4),
(47, 2, 42.00, 4),
(48, 3, 55.00, 4),
(49, 6, 67.00, 4),
(50, 8, 28.00, 4),
(51, 9, 38.00, 4),
(52, 16, 69.00, 4),
(53, 11, 65.00, 4),
(54, 12, 110.00, 4),
(55, 13, 210.00, 4),
(56, 14, 155.00, 4),
(57, 1, 52.00, 5),
(58, 2, 45.00, 5),
(59, 3, 58.00, 5),
(60, 4, 43.00, 5),
(61, 5, 48.00, 5),
(62, 6, 72.00, 5),
(63, 7, 95.00, 5),
(64, 15, 77.00, 5),
(65, 17, 68.00, 5),
(66, 8, 30.00, 5),
(67, 9, 40.00, 5),
(68, 16, 74.00, 5),
(69, 10, 90.00, 5),
(70, 11, 70.00, 5),
(71, 12, 130.00, 5),
(72, 13, 240.00, 5),
(73, 14, 170.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `created_at`, `is_read`) VALUES
(309, 7, 'liked your post!', '2024-12-10 01:20:31', 0),
(310, 7, 'commented on your post!', '2024-12-10 01:20:37', 0),
(311, 7, 'commented on your post!', '2024-12-10 01:28:23', 0),
(312, 7, 'liked your post!', '2024-12-10 01:28:55', 0),
(313, 7, 'created a new post', '2024-12-12 04:25:07', 0),
(314, 8, 'liked your post!', '2024-12-12 04:25:11', 0),
(315, 7, 'created a new post', '2024-12-12 04:39:13', 0),
(316, 7, 'created a new post', '2024-12-12 04:49:57', 0),
(317, 7, 'created a new post', '2024-12-12 04:50:00', 0),
(318, 9, 'created a new post', '2024-12-12 05:08:05', 0),
(319, 7, 'created a new post', '2024-12-12 05:08:05', 0),
(320, 8, 'has placed an order for your product.', '2024-12-12 05:08:15', 0),
(321, 9, 'created a new post', '2024-12-12 05:08:23', 0),
(322, 7, 'created a new post', '2024-12-12 05:08:23', 0),
(323, 8, 'liked your post!', '2024-12-12 05:11:07', 0),
(324, 8, 'liked your post!', '2024-12-14 06:17:59', 0),
(325, 9, 'created a new post', '2024-12-14 08:43:55', 0),
(326, 7, 'created a new post', '2024-12-14 08:43:55', 0),
(327, 10, 'created a new post', '2024-12-14 08:43:55', 0),
(328, 9, 'created a new post', '2024-12-14 08:44:15', 0),
(329, 7, 'created a new post', '2024-12-14 08:44:15', 0),
(330, 10, 'created a new post', '2024-12-14 08:44:15', 0),
(331, 9, 'created a new post', '2024-12-14 08:44:35', 0),
(332, 7, 'created a new post', '2024-12-14 08:44:35', 0),
(333, 10, 'created a new post', '2024-12-14 08:44:35', 0),
(334, 8, 'liked your post!', '2024-12-14 08:44:37', 0),
(335, 8, 'commented on your post!', '2024-12-14 08:44:41', 0),
(336, 9, 'created a new post', '2024-12-14 11:28:30', 0),
(337, 8, 'created a new post', '2024-12-14 11:28:30', 0),
(338, 7, 'created a new post', '2024-12-14 11:28:30', 0),
(339, 10, 'created a new post', '2024-12-14 11:28:30', 0),
(340, 9, 'created a new post', '2024-12-14 11:29:48', 0),
(341, 8, 'created a new post', '2024-12-14 11:29:48', 0),
(342, 7, 'created a new post', '2024-12-14 11:29:48', 0),
(343, 10, 'created a new post', '2024-12-14 11:29:48', 0),
(344, 9, 'created a new post', '2024-12-14 11:32:14', 0),
(345, 8, 'created a new post', '2024-12-14 11:32:14', 0),
(346, 7, 'created a new post', '2024-12-14 11:32:14', 0),
(347, 10, 'created a new post', '2024-12-14 11:32:14', 0),
(348, 9, 'created a new post', '2024-12-14 11:32:36', 0),
(349, 8, 'created a new post', '2024-12-14 11:32:36', 0),
(350, 7, 'created a new post', '2024-12-14 11:32:36', 0),
(351, 10, 'created a new post', '2024-12-14 11:32:36', 0),
(352, 9, 'created a new post', '2024-12-14 11:35:37', 0),
(353, 8, 'created a new post', '2024-12-14 11:35:37', 0),
(354, 7, 'created a new post', '2024-12-14 11:35:37', 0),
(355, 10, 'created a new post', '2024-12-14 11:35:37', 0),
(356, 9, 'created a new post', '2024-12-14 11:36:50', 0),
(357, 8, 'created a new post', '2024-12-14 11:36:50', 0),
(358, 7, 'created a new post', '2024-12-14 11:36:50', 0),
(359, 10, 'created a new post', '2024-12-14 11:36:50', 0),
(360, 9, 'created a new post', '2024-12-14 11:38:01', 0),
(361, 8, 'created a new post', '2024-12-14 11:38:01', 0),
(362, 7, 'created a new post', '2024-12-14 11:38:01', 0),
(363, 10, 'created a new post', '2024-12-14 11:38:01', 0),
(364, 9, 'created a new post', '2024-12-14 11:40:20', 0),
(365, 8, 'created a new post', '2024-12-14 11:40:20', 0),
(366, 7, 'created a new post', '2024-12-14 11:40:20', 0),
(367, 10, 'created a new post', '2024-12-14 11:40:20', 0),
(368, 9, 'created a new post', '2024-12-14 11:40:41', 0),
(369, 8, 'created a new post', '2024-12-14 11:40:41', 0),
(370, 7, 'created a new post', '2024-12-14 11:40:41', 0),
(371, 10, 'created a new post', '2024-12-14 11:40:41', 0),
(372, 9, 'created a new post', '2024-12-14 11:40:50', 0),
(373, 8, 'created a new post', '2024-12-14 11:40:50', 0),
(374, 7, 'created a new post', '2024-12-14 11:40:50', 0),
(375, 10, 'created a new post', '2024-12-14 11:40:50', 0),
(376, 9, 'created a new post', '2024-12-14 11:40:57', 0),
(377, 8, 'created a new post', '2024-12-14 11:40:57', 0),
(378, 7, 'created a new post', '2024-12-14 11:40:57', 0),
(379, 10, 'created a new post', '2024-12-14 11:40:57', 0),
(380, 9, 'created a new post', '2024-12-14 11:41:58', 0),
(381, 8, 'created a new post', '2024-12-14 11:41:58', 0),
(382, 7, 'created a new post', '2024-12-14 11:41:58', 0),
(383, 10, 'created a new post', '2024-12-14 11:41:58', 0),
(384, 11, 'liked your post!', '2024-12-14 12:05:21', 0),
(385, 11, 'commented on your post!', '2024-12-14 12:05:26', 0),
(386, 11, 'commented on your post!', '2024-12-14 12:31:12', 0),
(387, 11, 'commented on your post!', '2024-12-14 12:32:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `item_description` text NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `for_sale` tinyint(1) DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(255) DEFAULT 'User',
  `user_id` int(11) DEFAULT NULL,
  `user_photo` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','sold') DEFAULT 'pending',
  `price` decimal(10,2) DEFAULT NULL,
  `item_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `preservation_tips` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `price`, `origin`, `preservation_tips`) VALUES
(1, 'Rice', 'Cereal', 10.00, 'Local', 'Store in a cool, dry place'),
(2, 'Corn', 'Cereal', 10.00, 'Local', 'Keep in a dry and cool place'),
(3, 'Banana', 'Fruit', 10.00, 'Local', 'Keep at room temperature until ripe, then refrigerate'),
(4, 'Coconut', 'Fruit', 10.00, 'Local', 'Store in a cool, dry place'),
(5, 'Sugarcane', 'Vegetable', 10.00, 'Local', 'Keep in a cool and dry place'),
(6, 'Mango', 'Fruit', 10.00, 'Local', 'Store at room temperature until ripe, then refrigerate'),
(7, 'Coffee', 'Fruit', 10.00, 'Local', 'Store in an airtight container in a cool, dark place'),
(8, 'Vegetables', 'Vegetable', 10.00, 'Local', 'Refrigerate after harvesting, wash before use'),
(9, 'Root Crops', 'Vegetable', 10.00, 'Local', 'Store in a cool, dry, and dark place'),
(10, 'Bamboo', 'Plant', 10.00, 'Local', 'Store in a dry place, avoid moisture'),
(11, 'Spices', 'Spice', 10.00, 'Local', 'Store in a dry, dark place in airtight containers'),
(12, 'Poultry', 'Livestock', 10.00, 'Local', 'Keep refrigerated or frozen'),
(13, 'Livestock', 'Livestock', 10.00, 'Local', 'Store at a controlled temperature'),
(14, 'Aquaculture', 'Aquatic', 10.00, 'Local', 'Store in a cool place, use fresh'),
(15, 'Cacao', 'Fruit', 10.00, 'Local', 'Store in a cool, dry place'),
(16, 'Pineapple', 'Fruit', 10.00, 'Local', 'Store at room temperature until ripe'),
(17, 'Fruit Trees', 'Plant', 10.00, 'Local', 'Store in a dry, cool place'),
(18, 'Tilapia', 'Aquatic', 10.00, 'Local', 'Keep fresh in a cool place'),
(19, 'Bangus', 'Aquatic', 10.00, 'Local', 'Refrigerate or freeze after harvest'),
(20, 'Tomatoes', 'Vegetable', 10.00, 'Local', 'Store in a cool, dry place, away from sunlight'),
(21, 'Eggplant', 'Vegetable', 10.00, 'Local', 'Refrigerate for short-term storage'),
(22, 'Okra', 'Vegetable', 10.00, 'Local', 'Store in a cool, dry place'),
(23, 'Sweet Potato', 'Root Crop', 10.00, 'Local', 'Store in a dry, cool place'),
(24, 'Gabi', 'Root Crop', 10.00, 'Local', 'Store in a cool, dry place');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Cavite'),
(2, 'Laguna'),
(3, 'Batangas'),
(4, 'Rizal'),
(5, 'Quezon');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `location`, `photo`, `created_at`) VALUES
(7, 'Paul', 'jpreytapogi@gmail.com', '09553447568', '$2y$10$xTvKivOh0Uo/6wJQ83fd1uz9FNfnEbzB.I3W.//eSl36Q90loadBq', 'Batangas', '', '2024-12-10 01:15:25'),
(8, 'hanami', 'hanami@gmail.com', '0909090909', '$2y$10$XHUOxZXgwfH/MxnZElsCzeHmG/08jhL8Wcmv95EcaX6DOit5OEtKK', 'Batangas', 'harvest_hub_landing_page/uploads/IMG_0389.JPG', '2024-12-12 04:23:34'),
(9, 'Emman', 'Emman@gmail.com', '09090909090', '$2y$10$X0kKrKxnBTz3lkFxNvH4QObUMVqfOhqElEx/QjRPE7m0tyzIVgatG', 'Batangas', '', '2024-12-12 05:07:40'),
(10, 'Paul', 'Paul@gmail.com', '80808012', '$2y$10$wEEfUSQ6CDqhMXQD3qLMIOaPSnjKOcEonN5B7txiet518kQcYZGTm', 'Cavite', '', '2024-12-12 05:10:01'),
(11, 'Winona Isabelle U. Lapuz', 'winonaisabelleumalilapuz@gmail.com', '09553447568', '$2y$10$R6GsK/6lFykNcwSTjTH44OcWQ32mgPn94ZU/mtTuER6E0ZzKfwyb2', 'Batangas', 'harvest_hub_landing_page/uploads/AGRO8836.JPEG', '2024-12-14 11:23:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calabarzon_topography`
--
ALTER TABLE `calabarzon_topography`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comment_id` (`comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `group_posts`
--
ALTER TABLE `group_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `group_post_comments`
--
ALTER TABLE `group_post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `group_post_likes`
--
ALTER TABLE `group_post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `market_stocks`
--
ALTER TABLE `market_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calabarzon_topography`
--
ALTER TABLE `calabarzon_topography`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_posts`
--
ALTER TABLE `group_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `group_post_comments`
--
ALTER TABLE `group_post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_post_likes`
--
ALTER TABLE `group_post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `market_stocks`
--
ALTER TABLE `market_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=388;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD CONSTRAINT `comment_replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_posts`
--
ALTER TABLE `group_posts`
  ADD CONSTRAINT `group_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_post_comments`
--
ALTER TABLE `group_post_comments`
  ADD CONSTRAINT `group_post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `group_posts` (`id`),
  ADD CONSTRAINT `group_post_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_post_likes`
--
ALTER TABLE `group_post_likes`
  ADD CONSTRAINT `group_post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `group_posts` (`id`),
  ADD CONSTRAINT `group_post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `market_stocks`
--
ALTER TABLE `market_stocks`
  ADD CONSTRAINT `market_stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `market_stocks_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
