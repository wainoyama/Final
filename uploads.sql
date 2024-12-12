-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 02:42 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uploads`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(7, 128, 4, 'nasan si hanami', '2024-11-30 13:07:01'),
(8, 128, 6, 'bakit', '2024-11-30 13:07:14'),
(9, 128, 6, 'wala alng', '2024-11-30 14:58:40'),
(10, 128, 4, 'nasan si hanami', '2024-11-30 15:28:05'),
(11, 128, 3, 'Hipo', '2024-12-01 01:02:38');

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

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(14, 128, 4, '2024-11-30 13:06:57'),
(15, 128, 6, '2024-11-30 13:07:07'),
(16, 128, 3, '2024-12-01 01:02:25');

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
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `message`, `photo`, `for_sale`, `timestamp`, `user_name`, `user_id`) VALUES
(128, 'af3weawef', NULL, 0, '2024-11-30 13:06:49', 'User', 4);

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
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `location`, `photo`) VALUES
(1, 'Julience', 'juls@gmail.com', '09123456789', '$2y$10$TULbi/umFBZjZU103Vo1zOdF7dwCBykLHLYxLy8ZPAAm0sG0qvDga', 'Sa puso mo', ''),
(2, 'Winona', 'winonaisabelleumalilapuz@gmail.com', '09123456789', '$2y$10$kQN7CortZueBw8MFEs.S/.qzRuYssndmy92m73taKCn8MMLvr34mm', 'Maraykit san juan batangas', 'uploads/IMG_0389.JPG'),
(3, 'Paul Reyta', 'jpreytapogi@gmail.com', '09123456789', '$2y$10$66gtMCYaVvDb30OJPh9QPeS9XZlgx0nsZ.DSma8QW86AP8E8fC3N6', 'Maraykit san juan batangas', 'uploads/IMG_0389.JPG'),
(4, 'John Paul Bryan D. Reyta', 'jpreytapog@gmail.com', '09553447568', '$2y$10$r43IqKsY8Ci.zt0c53kO1erRTQowXkWj2akJmU9G29WcEbsH2xgOi', 'Batangas', 'uploads/img (13).JPG'),
(5, 'Winona', 'johnpaulbryan.reyta@gmail.com', '09553447568', '$2y$10$oNphGNY0DjMs1JMNw3kt2O7JiMu56766OG3yEejpGVd2B2TTG3NWS', 'Laguna', 'uploads/IMG_0389.JPG'),
(6, 'hanami', 'hanami@gmail.com', '0909090909', '$2y$10$GQkK16ELfOF.YsC2UEoOwe1zCr6GIEdiw6PfrxY1id5zZT32Jbnny', 'Batangas', 'uploads/IMG_0543 - Copy.JPG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
