-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2024 at 07:34 PM
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
-- Database: `sse`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `phone_number`, `email`, `message`, `submission_date`) VALUES
(1, 'Soham', '9137953314', 'soham@gmail.com', 'best ', '2024-04-15 11:04:17'),
(7, 'Lul', '9137953314', 'bitgamb1@gmail.com', 'best ', '2024-04-15 15:18:38'),
(9, 'Soham', '9137953313', 'bitgamb420@gmail.com', 'best ', '2024-04-15 17:14:42'),
(11, 'Lul', '9137953314', 'bitgamb1@gmail.com', 'best ', '2024-04-15 17:17:27'),
(12, 'Lul', '9137953313', 'bitgamb420@gmail.com', 'best ', '2024-04-15 17:32:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_details` varchar(255) NOT NULL,
  `delivery_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `payment_id`, `total_price`, `created_at`, `product_details`, `delivery_status`) VALUES
(10, 1, 'pay_NyrCgkxa19pyUG', 1550.00, '2024-04-15 09:59:29', '1:1,4:6,9:1,8:9', 'shipped'),
(12, 1, 'pay_NyyPyXugOJb3s8', 580.00, '2024-04-15 17:02:55', '9:3,5:1,4:1', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `image`) VALUES
(1, 'Mirinda', 'Soft Drinks', 'Mirinda\'s great orangey taste and sparkling bubbles encourage you to be carefree, spontaneous and playful!!', 250.00, 'images/f1.png'),
(2, 'Bisleri Water 200ml', 'Mineral Water', 'Bisleri Water is definitely one of the healthiest kinds of bottled water and drinking it can enhance your well-being.', 180.00, 'images/f2.png'),
(3, 'Sprite', 'Soft Drinks', 'Sprite is a clear lime drink truly meant to quench your thirst & refresh you since 1999.  It has true feelings for your thirst.', 300.00, 'images/f3.png'),
(4, 'Sting (Red)', 'Energy Drinks', 'Sting Energy Now in India - It is an energy drink with amazingly refreshing taste. It contains caffeine, tourine & B-vitamins.', 200.00, 'images/f4.png'),
(5, 'Jeera Soda', 'Soda', 'This refreshing drink has a perfect blend of jeera and soda, which makes it more rejuvenating.', 180.00, 'images/f5.png'),
(6, 'Pepsi', 'Soft Drinks', 'Pepsi soft drink offers a ubiquitous flavour to all your ideas. Kick start your party with this ultimate swag drink.', 350.00, 'images/f6.png'),
(7, 'Water 1 liter', 'Mineral Water', 'Minerals add therapeutic value to the water; the minerals generally added are magnesium, sulphate and iron.', 225.00, 'images/f7.png'),
(9, 'Sting (Blue)', 'Energy Drinks', 'Sting Energy Now in India - It is an energy drink with amazingly refreshing taste. It contains caffeine, taurine & B-vitamins.', 200.00, 'images/f9.png');

-- --------------------------------------------------------

--
-- Table structure for table `sse_address`
--

CREATE TABLE `sse_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sse_address`
--

INSERT INTO `sse_address` (`id`, `user_id`, `address`, `city`, `postal_code`, `country`, `created_at`) VALUES
(1, 1, ' Gorai Link Road, Gorai 1, Borivali West, Mumbai, Maharashtra 400092', 'Mumbai', '400091', 'India', '2024-04-15 08:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `sse_users`
--

CREATE TABLE `sse_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sse_users`
--

INSERT INTO `sse_users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'soham', 'bitgamb420@gmail.com', '$2y$10$UMIlmdNX0rJBd1aoqoAMvOYqIrwG9vPSkmST/XpLjURqK5wfvUkbC', '2024-04-14 16:54:02'),
(2, 'sahil', 'sahilbaddade@gmail.com', '$2y$10$uoecJRQY.ybWF3xQPSnZpeFicZYn0ESz9kxuj45iaAG7SgzRx6DNe', '2024-04-15 05:42:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sse_address`
--
ALTER TABLE `sse_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sse_users`
--
ALTER TABLE `sse_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sse_address`
--
ALTER TABLE `sse_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sse_users`
--
ALTER TABLE `sse_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sse_users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sse_users` (`id`);

--
-- Constraints for table `sse_address`
--
ALTER TABLE `sse_address`
  ADD CONSTRAINT `sse_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sse_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
