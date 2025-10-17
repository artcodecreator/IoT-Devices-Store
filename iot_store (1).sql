-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 09:47 PM
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
-- Database: `iot_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$uClWnEf1S2624lPncCyg8uWvA3ma5J6mils1KrEoite5ie4Jdm7sW');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `product_id`, `quantity`, `order_date`, `status`) VALUES
(1, 1, 1, 3, '2025-07-07 21:28:09', 'Shipped');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `category`, `price`, `stock`, `image`) VALUES
(1, 'headphones', 'Description:\r\n\r\n【Sturdy & Elegant 】 This Headset Stand Holder Is Features A Silicone Top Pad And Sturdy Solid Steel Construction Design, Low Center Of Gravity Design And Silicone Pad Grap To Provide A Safe And Stable Foundation For Your Headphones.\r\n【Protect Headphone】The Part Of Headphones Placed Is Made Of Premium Silicone And Has A Curved Surface Design. Protect The Headphone Head Beam Against The Scratch.\r\nP9 wireless Bluetooth has a 400mah battery capacity that allows you to enjoy music all day without any distraction and fear of running out of battery\r\nThe   wireless headphone support call and its high-quality performance provides crystal clear audio. And its amazing speaker never misses even a single beat throughout the communication.\r\nIts unique appearance, sophisticated design, and high quality at an affordable price make it more demanding.\r\nP9 wireless Bluetooth headphones are so comfortable. And lightweight that you can’t feel any irritation or discomfort in your ears.\r\nIts ear cushions are so soft that they provide a relaxing feel and elevate your music experience even if you are working, exercising, or doing any other work.\r\nSpecifications:\r\nControl Button: Yes\r\nCommunication: Wireless\r\nWireless Type: Bluetooth\r\nRechargeable\r\nBattery Timing: 4 to 10 hours depending on usage\r\nBattery Capacity: 400mah\r\nBluetooth version: 5.0', 'electronics', 45.00, 15, '16-600x600.jpg'),
(2, 'Apple Power Bank', 'Apple MagSafe Wireless Power Bank for iPhone 5000Mah\r\nAttaching the MagSafe Battery Pack is a snap. Its compact, intuitive design makes on-the-go charging easy. The perfectly aligned magnets keep it attached to your iPhone 12 and iPhone 12 Pro or iPhone 13 and iPhone 13 Pro — providing safe and reliable wireless charging. And it automatically charges, so there’s no need to turn it on or off. There’s no interference with your credit cards or key fobs either.\r\n\r\nAt your desk and need a charge? Just plug a Lightning cable into the MagSafe Battery Pack for up to 15W of wireless charging. Short on time? With a higher than 20W power adapter, you can charge both the MagSafe Battery Pack and your iPhone even faster. And you can track the status of your charge on the Lock Screen.\r\n\r\nThis portable charger perfectly meets iphone users requests, doesn’t interfere with the camera lens on your iphone. And the metal body with graphene material design of magnetic powerbank helps to improve Heat Dissipation performance a lot, bringing a much safer use during charging.\r\n\r\nCharge In A Snap and Pocket Sized Power: Align Your Iphone And Battery With A Snap. Say Goodbye To Disconnection Issues Caused By Wireless Charging Misalignment; Slim Enough To Snap To Your Phone And Slip Into Your Pack, Purse, Or Pocket.\r\n\r\nStrong Magnetism & Snap to power up: Strong magnetic adsorption, no need wires, put the phone on it to stick to the phone without falling off.\r\n\r\nThe super strong magnet snaps magnetically into place to ensure perfect alignment and an efficient charge.No need to press any buttons, snap on the portable phone charger to your iPhone backplate, the magnet will instantly attach and activate charging automatically.', 'electronics', 45.00, 20, 'apple-magsafe-wireless-power-bank-for-iphone-5000mah - 2.png'),
(3, 'wireless Router Power Bank', 'Stay connected during power outages with this high-performance router power bank. Designed to keep your Wi-Fi router running, it ensures your internet never drops — whether you’re working from home, attending online classes, or streaming your favorite content.\r\n\r\n09 volt & 12 volt WiFi router power bank battery capacity is ( 20000 mAH ).\r\nKey Features:\r\n\r\n🔋 Dual Voltage Output (9V / 12V): Compatible with most popular routers, modems, and ONT devices.\r\n\r\n⚡ Long Backup Time: High-capacity battery provides 4–10 hours* of uninterrupted power.\r\n\r\n🔌 Plug & Play Design: Easy to install with a standard DC output jack (5.5mm) — no technical setup required.\r\n\r\n💡 Smart Protection: Built-in safety features including overcharge, over-discharge, short-circuit, and over-voltage protection.\r\n\r\n👜 Portable & Compact: Lightweight and durable — perfect for home, office, or travel use.', 'electronics', 45.00, 20, 'ChatGPT-Image-Jun-30-2025-01_59_50-PM-1.png');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Active','Blocked') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `address`, `phone`, `status`) VALUES
(1, 'Tauqeer Ahmed', 'tauqeerahmed@gmail.com', '$2y$10$Ltp5QPwCR9jvzmRvr3kDQu7VhnlQMwp6vK8EPc3oKV0ECOH0XFVua', 'GHULSHAN IQBAL COLONY BACK SIDE SAJ CAFE', '0312 8595286', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
