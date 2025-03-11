-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2024 at 04:47 PM
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
-- Database: `hr_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `employee` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `employee`, `role`) VALUES
(5, 'Noelle Martin', 'Owner'),
(7, 'adrian martin', 'Cashier'),
(8, 'dennis rhodman', 'Cashier');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `app_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `des_pos` varchar(60) NOT NULL,
  `status` varchar(50) NOT NULL,
  `resume` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `department` varchar(70) NOT NULL,
  `date` date NOT NULL,
  `time_in` varchar(50) NOT NULL,
  `time_out` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`att_id`, `name`, `department`, `date`, `time_in`, `time_out`) VALUES
(11, 'Noelle martin', 'Human Resources', '2024-04-22', '14:22', '');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `deptproj_id` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `project` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `budget` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `start` varchar(50) NOT NULL,
  `end` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `fullname` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  `department` varchar(70) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `address` varchar(150) NOT NULL,
  `hire_date` date NOT NULL,
  `emp_status` varchar(50) NOT NULL,
  `shift_per_day` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `monthly_salary` int(50) NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `firstname`, `lastname`, `middlename`, `suffix`, `fullname`, `email`, `contact`, `birthday`, `marital_status`, `department`, `gender`, `nationality`, `zip_code`, `address`, `hire_date`, `emp_status`, `shift_per_day`, `position`, `monthly_salary`, `photo`) VALUES
(35, 'Noelle', 'Martin', 'Marquez', 'none', 'Noelle Martin', '2021308143@dhvsu.edu.ph', '09983302906', '2002-06-21', 'Single', 'Human Resources', 'Male', 'Filipino', '2001', 'Blk 56 Lot 14 Calle 34 Xevera Bacolor ', '2024-04-24', 'Hired', '800', 'HR', 150000, ''),
(36, 'adrian', 'martin', 'marquez', '', 'adrian martin', 'jiofjaoisf@gmail.com', '09934234', '2002-06-21', 'single', 'Inventory', 'male', 'Filipino', '2001', 'dokfkosdjfoisdjfoisd', '2200-02-04', 'Hired', '8', 'Cashier', 150000, 'download (1).png');

-- --------------------------------------------------------

--
-- Table structure for table `emp_leave`
--

CREATE TABLE `emp_leave` (
  `leave_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `start` varchar(50) NOT NULL,
  `end` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `whole_salary` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `deduction` int(11) NOT NULL,
  `total_salary` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

CREATE TABLE `performance` (
  `perf_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `department` varchar(70) NOT NULL,
  `date` date NOT NULL,
  `sales` int(255) NOT NULL,
  `qua_work` int(11) NOT NULL,
  `char_rate` int(11) NOT NULL,
  `inter_rel` int(11) NOT NULL,
  `creativity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance`
--

INSERT INTO `performance` (`perf_id`, `name`, `department`, `date`, `sales`, `qua_work`, `char_rate`, `inter_rel`, `creativity`) VALUES
(5, 'Noelle martin', 'Department', '2002-02-22', 0, 5, 5, 5, 5),
(6, 'Noelle Martin', 'Human Resources', '2024-04-20', 100, 5, 5, 5, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`app_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`att_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptproj_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `emp_leave`
--
ALTER TABLE `emp_leave`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`);

--
-- Indexes for table `performance`
--
ALTER TABLE `performance`
  ADD PRIMARY KEY (`perf_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `att_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `deptproj_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `emp_leave`
--
ALTER TABLE `emp_leave`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `performance`
--
ALTER TABLE `performance`
  MODIFY `perf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
