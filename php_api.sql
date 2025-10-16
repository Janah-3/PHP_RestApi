-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 11:27 AM
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
-- Database: `php_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_deleted`) VALUES
(1, 'snacks', '', 0),
(2, 'beverages', 'all types of drinks', 0),
(4, 'beveragess', '', 1),
(6, 'dairy', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `reset_code` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `user_email`, `reset_code`, `expires_at`) VALUES
(8, 10, 'jana.ayoub.004@gmail.com', '207431', '2025-10-16 02:05:28');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_url` longtext NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category_id`, `image_url`, `is_deleted`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'chips ', 'fried potato chips', 20, 1, '', 0, 1, '2025-10-15 23:06:59', '2025-10-16 02:06:59'),
(2, 'cookies', 'chocolate cookies', 20, 1, 'http://localhost/C:\\xampp\\htdocs\\PHP_RestApi\\helpers/../uploads/products/cookiejpeg.jpeg', 0, 6, '2025-10-14 14:41:55', '2025-10-14 17:41:55'),
(3, 'mint candy', 'chocolate cookies', 20, 1, 'localhost/C:\\xampp\\htdocs\\PHP_RestApi\\helpers/../uploads/products/cookiejpeg.jpeg', 0, 6, '2025-10-15 22:59:37', '2025-10-16 01:59:37'),
(4, 'cookies', 'chocolate cookies', 20, 1, 'localhost/C:\\xampp\\htdocs\\PHP_RestApi\\helpers/../uploads/products/cookiejpeg.jpeg', 1, 6, '2025-10-14 02:39:28', '2025-10-14 03:12:42'),
(5, 'bubble_gum', '', 5, 1, 'localhost/C:\\xampp\\htdocs\\PHP_RestApi\\helpers/../uploads/products/bubble_gumjpeg.jpeg', 0, 6, '2025-10-14 10:29:43', '2025-10-14 12:26:24'),
(6, 'bubble_gum', '', 5, 1, 'localhost/C:\\xampp\\htdocs\\PHP_RestApi\\helpers/../uploads/products/bubble_gumjpeg.jpeg', 0, 6, '2025-10-15 21:47:23', '2025-10-16 00:47:23'),
(7, 'gum', '', 5, 1, 'localhost/C:\\xampp\\htdocs\\PHP_RestApi\\helpers/../uploads/products/bubble_gumjpeg.jpeg', 0, 10, '2025-10-15 23:21:08', '2025-10-16 02:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_revoked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refresh_tokens`
--

INSERT INTO `refresh_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`, `is_revoked`) VALUES
(3, 6, '911324a1cb2e2d66e14fd1a00c0fd6547213f1c77893c6fd42a126a0fb402562d57ffc0e503bae05cb7a71f09614cbf28295426e2cd6ca1e85b0eeecc6cc9d50', '2025-10-24 00:10:06', '2025-10-14 00:10:06', 0),
(4, 6, '8989f3c746a0756e9c3397ec9bcc78e5ba5c781adf72b7b843aa43c13816a10537694186cbf997d20f0ed3c84d1211b65ad28f70deca8d29b2fd39a3d1b539a6', '2025-10-24 01:25:54', '2025-10-14 01:25:54', 0),
(5, 6, '89d0868864d203a3b83423a722d7b398e522bde3052a36c5c1226cf26ed273bbf7826e6096bec58556d8087593814020ae7029cfc447951650380e78c9b37a0b', '2025-10-24 08:08:23', '2025-10-14 08:08:23', 0),
(6, 6, '6bee238732020d1b195df3de7d4e34351f72dc05cc7437b45ab94a4db163e62a8adf4186bb22aa4e1fca356697f1be61605b0475dc557ebca013eff2aab40973', '2025-10-24 09:17:33', '2025-10-14 09:17:33', 0),
(7, 6, 'a068277899b19aa16067d50214136e6d09ba67cb827db80274cb7ad5b1752e06e22d3947662de36d3e14598c7e51564f3bed15dd459e1ac757d2df789aca909a', '2025-10-24 14:27:48', '2025-10-14 14:27:48', 0),
(8, 6, 'e40b270649699300db95a2fc87b9086a8e5192262e753d7b577411333601fd1dea1db082e186f04afd351597663cfb38b629f27600c38368bc2ba27cf89dfce9', '2025-10-15 21:50:16', '2025-10-15 20:48:05', 1),
(9, 6, '06521479f7a8d8ec9d6636fad10ac219a25de7d9eda9f7442452628ebad07312084064c12a0ed9bd58435e0d1ca536065acae15b79c4fe6a28a8ec34bcefa620', '2025-10-25 22:06:34', '2025-10-15 22:06:34', 0),
(10, 6, '03cb1e3fd0f0cac2ac4dbc4754b098c3f2cc2219f8cc805a38d834221a61b90044ebbf4cb5ac3cb6c73584502d90cab3cda4975f9d64d0203ca78ab64a470632', '2025-10-25 22:41:39', '2025-10-15 22:41:39', 0),
(11, 6, 'e0b45176ae68e506d595a5d1ed27965390add195727ddc9b1e2e16b0dd64d8b7a4708e4ed43bd4cac29f5a4b846ac20809270c219ae16b94609ebb8f59680ed8', '2025-10-25 22:45:59', '2025-10-15 22:45:59', 0),
(12, 6, '82111086ea5828080e02fa220757026386c2f5092b41eb8d9ce69872ad8d623e7ecf80c0359af27412238bddb612cdf405a64d45adea8c09987edf0459687c8c', '2025-10-25 22:50:27', '2025-10-15 22:50:27', 0),
(13, 10, 'f20b93c0de3c8e7e3d7f2a2510eb9b68deca89322727816409b8b799485dbfff7820f73e8110daf353daf67f81e8220848c595354c01624566e7709df686f6d1', '2025-10-25 22:57:07', '2025-10-15 22:57:07', 0),
(14, 10, 'efed6f81960da8b037d6b045f4239873eff83c5daf2ecd7261836af22336b362d80186b423452805b1156e0ccd5e355915856c95f512499ea99cfd99509936fa', '2025-10-25 23:18:03', '2025-10-15 23:18:03', 0),
(15, 10, '9473f713c01bece77a4972606edb1197e7d583d2300005506db8e7839ed48938b435c9688eabb3071895eda420bd80581009eaaa64f77cd8c31eaec2f86df529', '2025-10-25 23:18:49', '2025-10-15 23:18:49', 0),
(16, 13, '601d9726797380a20e71c31f1fe7df157bbb502fea74a9484e491537cbcf99c9b0a10645860f1b3baf8e9bd58db77331ca4314528a58361c6d2745e211c4710f', '2025-10-26 08:22:28', '2025-10-16 08:22:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','editor') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(1, 'Jana Ayoub', 'jana@example.com', '$2y$10$x3AN51U6Z0/KWsgKfAhmLOJwCgqSWP9lYSg5.Kpr0ir1CvECLnLLS', 'admin'),
(5, 'Jana', 'jojojana233@gmail.com', '$2y$10$6W9R7N5PA/PjekZVsFrnC.N05G9vAs0pjoPVS6KsR3uwQ2qDiTD7O', 'user'),
(6, 'malek', 'malek@gmail.com', '$2y$10$/TNjG91gbmC9BAuaKAq/x./en5qlDrYWtPzUhBQCOHOyJjVZUBCNm', 'admin'),
(7, 'malek', 'malekk@gmail.com', '$2y$10$uSxZz1Ld.LL9BfFfPFgaluYG55yK2gajSXKIB6vxtVqgJmQBpMKYK', 'editor'),
(10, 'jana', 'jana.ayoub.0044@gmail.com', '$2y$10$D9ln7a9zjxqh97J11vDK0ebk/ujzuQt8ZZuNtZRrJckf1/ct3I2Ue', 'admin'),
(11, 'hana', 'hana@gmail.com', '$2y$10$2J2IBGACB1f68ju10BhLI.epi0o/i4fHIlK4.XLxF9BE0MBdnHGXC', 'admin'),
(12, 'hana', 'hanaa@gmail.com', '$2y$10$f1OuZjG9EzbTLgfsH0reHeLYQvhydJYxdkI3OwJRGf/LEwBav1bC6', 'user'),
(13, 'jana ', 'jana.ayoub.004@gmail.com', '$2y$10$qbnUTApFS8Xg45igfCno6OKRNhyDB6VwOM1jWngkgZkThJXToaGYe', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `refresh_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
