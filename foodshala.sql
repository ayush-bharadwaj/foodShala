-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2020 at 07:33 PM
-- Server version: 5.7.24
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodshala`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `itemId` int(10) NOT NULL,
  `itemName` varchar(128) NOT NULL,
  `itemDescription` varchar(255) NOT NULL,
  `itemRestaurantId` int(11) NOT NULL,
  `itemPreference` enum('Veg','NonVeg') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`itemId`, `itemName`, `itemDescription`, `itemRestaurantId`, `itemPreference`) VALUES
(1, 'Veg Burger', 'Burger that is Veg', 3, 'Veg'),
(2, 'Veg Pizza', 'Pizza that is veg', 3, 'Veg'),
(3, 'Veg Pasta', 'Pasta that is veg', 4, 'Veg'),
(4, 'Non-veg Burger', 'Burger that is non-veg', 4, 'NonVeg'),
(5, 'Non Veg Pizza', 'Pizza that is non veg', 4, 'NonVeg'),
(6, 'Non-veg Pasta', 'Pasta that is non Veg', 3, 'NonVeg'),
(7, 'Veg Newie', 'Newie that is veg', 3, 'Veg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(10) NOT NULL,
  `customerId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `customerId`, `itemId`) VALUES
(1, 2, 2),
(2, 7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(10) NOT NULL,
  `userName` varchar(128) NOT NULL,
  `userType` enum('Customer','Restaurant') DEFAULT 'Customer',
  `userEmail` varchar(128) NOT NULL,
  `userPassword` varchar(128) NOT NULL,
  `userPreference` enum('Veg','NonVeg') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `userType`, `userEmail`, `userPassword`, `userPreference`) VALUES
(1, 'Test Customer Veg', 'Customer', 'customerveg@test.com', 'customer', 'Veg'),
(2, 'Test Customer Non-veg', 'Customer', 'customernonveg@test.com', 'customer', 'NonVeg'),
(3, 'Test Restaurant', 'Restaurant', 'restaurant@test.com', 'restaurant', NULL),
(4, 'Test Restaurant 2', 'Restaurant', 'restaurant2@test.com', 'restaurant', NULL),
(5, 'Sign up Test Restaurant', 'Restaurant', 'signuprestaurant@test.com', '12345678', NULL),
(6, 'Sign up Test Customer', 'Customer', 'signupcustomer@test.com', '12345678', 'Veg'),
(7, 'Sign up Test Customer 2', 'Customer', 'signupcustomer2@test.com', '12345678', 'NonVeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `itemRestaurantId` (`itemRestaurantId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `customerId` (`customerId`),
  ADD KEY `itemId` (`itemId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `itemId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`itemRestaurantId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`itemId`) REFERENCES `menu` (`itemId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
