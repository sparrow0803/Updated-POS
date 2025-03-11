-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2024 at 09:04 AM
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
-- Database: `inventory_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `inbound`
--

CREATE TABLE `inbound` (
  `inbound_id` int(11) NOT NULL,
  `items` varchar(50) NOT NULL,
  `quantity` int(255) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inbound`
--

INSERT INTO `inbound` (`inbound_id`, `items`, `quantity`, `Date`) VALUES
(30, 'Fudgee Barr', 10, '2024-05-27 13:17:22'),
(31, 'Banana', 10, '2024-05-27 13:18:11'),
(32, 'Rebisco', 10, '2024-05-27 13:19:18'),
(33, 'Coca-Cola', 10, '2024-05-27 13:20:13'),
(34, 'Whatta Tops', 10, '2024-05-27 13:21:58'),
(35, 'Fudgee Barr', 10, '2024-05-27 15:54:09'),
(36, 'Fudgee Barr', 5, '2024-05-28 03:36:23'),
(37, 'Fudgee Barr', 5, '2024-05-28 03:39:22'),
(38, 'Fudgee Barr', 10, '2024-05-28 04:14:21'),
(39, 'Fudgee Barr', 5, '2024-05-28 04:19:23'),
(40, 'Fudgee Barr', 10, '2024-05-28 04:24:29'),
(41, 'Rebisco', 10, '2024-05-28 04:24:30'),
(42, 'Whatta Tops', 10, '2024-05-28 04:24:31'),
(43, 'Coca-Cola', 10, '2024-05-28 04:24:31'),
(44, 'Banana', 10, '2024-05-28 04:24:32'),
(45, 'Fudgee Barr', 5, '2024-05-28 04:53:55'),
(46, 'kopiko', 100, '2024-05-28 06:14:17'),
(47, 'bearbrand', 200, '2024-05-28 06:16:02'),
(48, 'safeguard', 300, '2024-05-28 06:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_report`
--

CREATE TABLE `inventory_report` (
  `date` date NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_Id` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_report`
--

INSERT INTO `inventory_report` (`date`, `product_name`, `product_Id`, `category`, `brand`, `quantity`, `unit_price`, `total_value`) VALUES
('2024-05-27', 'Fudgee Barr', '20', 'Cakes', 'FUDGEE BAR', 35, 10, 350),
('2024-05-27', 'Banana', '21', 'Fruits', 'Banana', 20, 20, 400),
('2024-05-27', 'Rebisco', '22', 'Biscuits', 'REBISCO Sandwich', 20, 15, 300),
('2024-05-27', 'Coca-Cola', '23', 'Sparkling Softdrinks', 'Coca-Cola', 20, 25, 500),
('2024-05-27', 'Whatta Tops', '24', 'Cakes', 'Lemon Square', 20, 15, 300),
('2024-05-28', 'Fudgee Barr', '20', 'Cakes', 'FUDGEE BARR', 55, 10, 550),
('2024-05-28', 'Banana', '21', 'Fruits', 'Banana', 30, 20, 600),
('2024-05-28', 'Rebisco', '22', 'Biscuits', 'REBISCO Sandwich', 30, 15, 450),
('2024-05-28', 'Coca-Cola', '23', 'Sparkling Softdrinks', 'Coca-Cola', 30, 25, 750),
('2024-05-28', 'Whatta Tops', '24', 'Cakes', 'Lemon Square', 30, 15, 450),
('2024-05-28', 'Fudgee Barr', '20', 'Cakes', 'FUDGEE BARR', 55, 10, 550),
('2024-05-28', 'Banana', '21', 'Fruits', 'Banana', 30, 20, 600),
('2024-05-28', 'Rebisco', '22', 'Biscuits', 'REBISCO Sandwich', 30, 15, 450),
('2024-05-28', 'Coca-Cola', '23', 'Sparkling Softdrinks', 'Coca-Cola', 30, 25, 750),
('2024-05-28', 'Whatta Tops', '24', 'Cakes', 'Lemon Square', 30, 15, 450),
('2024-05-28', 'Fudgee Barr', '20', 'Cakes', 'FUDGEE BARR', 55, 10, 550),
('2024-05-28', 'Banana', '21', 'Fruits', 'Banana', 30, 20, 600),
('2024-05-28', 'Rebisco', '22', 'Biscuits', 'REBISCO Sandwich', 30, 15, 450),
('2024-05-28', 'Coca-Cola', '23', 'Sparkling Softdrinks', 'Coca-Cola', 30, 25, 750),
('2024-05-28', 'Whatta Tops', '24', 'Cakes', 'Lemon Square', 30, 15, 450),
('2024-05-28', 'Fudgee Barr', '20', 'Cakes', 'FUDGEE BARR', 55, 10, 550),
('2024-05-28', 'Banana', '21', 'Fruits', 'Banana', 30, 20, 600),
('2024-05-28', 'Rebisco', '22', 'Biscuits', 'REBISCO Sandwich', 30, 15, 450),
('2024-05-28', 'Coca-Cola', '23', 'Sparkling Softdrinks', 'Coca-Cola', 30, 25, 750),
('2024-05-28', 'Whatta Tops', '24', 'Cakes', 'Lemon Square', 30, 15, 450),
('2024-05-28', 'kopiko', '27', 'coffee', 'nescafe', 80, 15, 1200),
('2024-05-28', 'bearbrand', '28', 'powder milk', 'bearbrand', 170, 100, 17000),
('2024-05-28', 'safeguard', '29', 'soap', 'safeguard', 300, 20, 6000),
('2024-05-28', 'Fudgee Barr', '20', 'Cakes', 'FUDGEE BARR', 55, 10, 550),
('2024-05-28', 'Banana', '21', 'Fruits', 'Banana', 30, 20, 600),
('2024-05-28', 'Rebisco', '22', 'Biscuits', 'REBISCO Sandwich', 30, 15, 450),
('2024-05-28', 'Coca-Cola', '23', 'Sparkling Softdrinks', 'Coca-Cola', 30, 25, 750),
('2024-05-28', 'Whatta Tops', '24', 'Cakes', 'Lemon Square', 30, 15, 450),
('2024-05-28', 'kopiko', '27', 'coffee', 'nescafe', 80, 15, 1200),
('2024-05-28', 'bearbrand', '28', 'powder milk', 'bearbrand', 170, 100, 17000),
('2024-05-28', 'safeguard', '29', 'soap', 'safeguard', 300, 20, 6000);

-- --------------------------------------------------------

--
-- Table structure for table `outbound`
--

CREATE TABLE `outbound` (
  `outbound_id` int(11) NOT NULL,
  `items` varchar(50) NOT NULL,
  `quantity` int(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outbound`
--

INSERT INTO `outbound` (`outbound_id`, `items`, `quantity`, `destination`, `Date`) VALUES
(22, 'Fudgee Barr', 5, 'Arayat', '2024-05-27 13:26:08'),
(23, 'Fudgee Barr', 10, 'Bacolor', '2024-05-28 03:53:46'),
(24, 'kopiko', 5, 'sales', '2024-05-28 06:14:46'),
(25, 'kopiko', 10, 'sales', '2024-05-28 06:15:07'),
(26, 'bearbrand', 20, 'cashier', '2024-05-28 06:16:53'),
(27, 'bearbrand', 10, 'cashier', '2024-05-28 06:17:13'),
(28, 'kopiko', 5, 'cashier', '2024-05-28 06:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_Id` int(50) NOT NULL,
  `name` varchar(70) NOT NULL,
  `category` varchar(75) NOT NULL,
  `brand` varchar(75) NOT NULL,
  `stocks` int(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `barcode` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_Id`, `name`, `category`, `brand`, `stocks`, `price`, `barcode`) VALUES
(20, 'Fudgee Barr', 'Cakes', 'FUDGEE BARR', 55, '10', 1111),
(21, 'Banana', 'Fruits', 'Banana', 30, '20', 2222),
(22, 'Rebisco', 'Biscuits', 'REBISCO Sandwich', 30, '15', 3333),
(23, 'Coca-Cola', 'Sparkling Softdrinks', 'Coca-Cola', 30, '25', 4444),
(24, 'Whatta Tops', 'Cakes', 'Lemon Square', 30, '15', 5555),
(27, 'kopiko', 'coffee', 'nescafe', 80, '15.00', 123123),
(28, 'bearbrand', 'powder milk', 'bearbrand', 170, '100.00', 123),
(29, 'safeguard', 'soap', 'safeguard', 300, '20.00', 7654);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expected_date` date DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `purchase_cost` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `supplier_name`, `store_name`, `note`, `date`, `expected_date`, `item_name`, `quantity`, `purchase_cost`, `amount`, `status`) VALUES
(30, 'Pabz', '7/11', 'Yehey', '2024-05-28 04:24:29', '2024-05-30', 'Fudgee Barr', 10, 10.00, 100.00, 1),
(31, 'Pabz', '7/11', 'Yehey', '2024-05-28 04:24:30', '2024-05-30', 'Rebisco', 10, 15.00, 150.00, 1),
(32, 'Pabz', '7/11', 'Yehey', '2024-05-28 04:24:31', '2024-05-30', 'Whatta Tops', 10, 15.00, 150.00, 1),
(33, 'Pabz', '7/11', 'Yehey', '2024-05-28 04:24:31', '2024-05-30', 'Coca-Cola', 10, 25.00, 250.00, 1),
(34, 'Pabz', '7/11', 'Yehey', '2024-05-28 04:24:32', '2024-05-30', 'Banana', 10, 20.00, 200.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inbound`
--
ALTER TABLE `inbound`
  ADD PRIMARY KEY (`inbound_id`);

--
-- Indexes for table `outbound`
--
ALTER TABLE `outbound`
  ADD PRIMARY KEY (`outbound_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_Id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inbound`
--
ALTER TABLE `inbound`
  MODIFY `inbound_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `outbound`
--
ALTER TABLE `outbound`
  MODIFY `outbound_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_Id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
