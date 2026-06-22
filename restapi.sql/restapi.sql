-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2026 at 11:12 AM
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
-- Database: `restapi`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `sp_check_email`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_check_email` (IN `p_email` VARCHAR(100))   BEGIN
    SELECT * FROM users WHERE email = p_email;
END$$

DROP PROCEDURE IF EXISTS `sp_delete_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_user` (IN `p_id` INT)   BEGIN
    DELETE FROM users WHERE id = p_id;
    SELECT ROW_COUNT() AS rows_affected;
END$$

DROP PROCEDURE IF EXISTS `sp_get_branches`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_branches` ()   BEGIN
    SELECT * FROM branches;
END$$

DROP PROCEDURE IF EXISTS `sp_get_departments`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_departments` ()   BEGIN
    SELECT * FROM departments;
END$$

DROP PROCEDURE IF EXISTS `sp_get_users`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_users` ()   BEGIN
    SELECT
        u.id,
        u.first_name,
        u.last_name,
        u.email,
        u.address,
        u.phone_number,
        u.status,
        u.created_at,
        d.name AS department,
        b.name AS branch,
        u.last_login
    FROM users u
    LEFT JOIN departments d ON u.dept_id = d.id
    LEFT JOIN branches b ON u.branch_id = b.id;
END$$

DROP PROCEDURE IF EXISTS `sp_get_user_by_id`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_user_by_id` (IN `p_id` INT)   BEGIN
    SELECT
        u.id,
        u.first_name,
        u.last_name,
        u.email,
        u.address,
        u.phone_number,
        u.status,
        u.created_at,
        d.name AS department,
        b.name AS branch,
        u.last_login
    FROM users u
    LEFT JOIN departments d ON u.dept_id = d.id
    LEFT JOIN branches b ON u.branch_id = b.id
    WHERE u.id = p_id;
END$$

DROP PROCEDURE IF EXISTS `sp_login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_login` (IN `p_email` VARCHAR(100))   BEGIN
    SELECT * FROM users WHERE email = p_email;
END$$

DROP PROCEDURE IF EXISTS `sp_register`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_register` (IN `p_data` JSON)   BEGIN
    INSERT INTO users (first_name, last_name, email, password, address, phone_number, terms_accepted, dept_id, branch_id, status)
    VALUES (
        JSON_UNQUOTE(JSON_EXTRACT(p_data, '$.first_name')),
        JSON_UNQUOTE(JSON_EXTRACT(p_data, '$.last_name')),
        JSON_UNQUOTE(JSON_EXTRACT(p_data, '$.email')),
        JSON_UNQUOTE(JSON_EXTRACT(p_data, '$.password')),
        JSON_UNQUOTE(JSON_EXTRACT(p_data, '$.address')),
        JSON_UNQUOTE(JSON_EXTRACT(p_data, '$.phone_number')),
        JSON_EXTRACT(p_data, '$.terms_accepted'),
        JSON_EXTRACT(p_data, '$.dept_id'),
        JSON_EXTRACT(p_data, '$.branch_id'),
        'Active'
    );

    SELECT LAST_INSERT_ID() AS id;
END$$

DROP PROCEDURE IF EXISTS `sp_save_token`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_save_token` (IN `p_user_id` INT, IN `p_token` VARCHAR(64))   BEGIN
    INSERT INTO auth_tokens (user_id, token)
    VALUES (p_user_id, p_token);
END$$

DROP PROCEDURE IF EXISTS `sp_update_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_user` (IN `p_id` INT, IN `p_first_name` VARCHAR(100), IN `p_last_name` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_address` VARCHAR(255), IN `p_phone_number` VARCHAR(20), IN `p_dept_id` INT, IN `p_branch_id` INT, IN `p_status` VARCHAR(20))   BEGIN
    UPDATE users SET
        first_name = p_first_name,
        last_name = p_last_name,
        email = p_email,
        address = p_address,
        phone_number = p_phone_number,
        dept_id = p_dept_id,
        branch_id = p_branch_id,
        status = p_status
    WHERE id = p_id;

    SELECT ROW_COUNT() AS rows_affected;
END$$

DROP PROCEDURE IF EXISTS `sp_verify_token`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_verify_token` (IN `p_token` VARCHAR(64))   BEGIN
    SELECT user_id
    FROM auth_tokens
    WHERE token = p_token
    LIMIT 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_tokens`
--

INSERT INTO `auth_tokens` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 7, 'dc4578486981d1b3ea8db3b1b394381d910ea123', '2026-06-15 08:46:18'),
(2, 7, '44d8f9f37aea021441aeba2d40299613fe11c4b5', '2026-06-15 08:54:21'),
(3, 8, '4bde10ccce7f7422e0dec34a188da12dc5c934ac', '2026-06-16 02:27:13'),
(4, 9, 'f9536bab6e30b6cc3d3b8a7834cdf121c288b5dd', '2026-06-17 02:11:40'),
(5, 10, '2758dcf0628caff44713623b272aa08cb7eb290e', '2026-06-18 11:33:30'),
(6, 1, '1960b221514b9a1dd2e5437eac4b7c958d93ebe5', '2026-06-21 18:19:00'),
(7, 1, 'd86ce2355b9362df83b3bc6ba88d7f59499cb1a4', '2026-06-21 18:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `created_at`) VALUES
(1, 'Taguig HQ', 'Taguig City', '2026-06-21 18:04:09'),
(2, 'Makati Branch', 'Ayala Ave, Makati', '2026-06-21 18:04:09'),
(3, 'Quezon City Branch', 'Commonwealth Ave, QC', '2026-06-21 18:04:09'),
(4, 'Pasig Branch', 'Ortigas Center, Pasig', '2026-06-21 18:04:09'),
(5, 'Mandaluyong Branch', 'Shaw Blvd, Mandaluyong', '2026-06-21 18:04:09'),
(6, 'Caloocan Branch', 'Rizal Ave Ext, Caloocan', '2026-06-21 18:04:09'),
(7, 'Las Piñas Branch', 'Alabang-Zapote Rd, Las Piñas', '2026-06-21 18:04:09'),
(8, 'Marikina Branch', 'Shoe Ave, Marikina', '2026-06-21 18:04:09'),
(9, 'Muntinlupa Branch', 'Alabang, Muntinlupa', '2026-06-21 18:04:09'),
(10, 'Parañaque Branch', 'Dr. A. Santos Ave, Parañaque', '2026-06-21 18:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`) VALUES
(1, 'Administration', '2026-06-21 18:04:09'),
(2, 'Cardiology', '2026-06-21 18:04:09'),
(3, 'Emergency Medicine', '2026-06-21 18:04:09'),
(4, 'Finance', '2026-06-21 18:04:09'),
(5, 'Human Resources', '2026-06-21 18:04:09'),
(6, 'ICU', '2026-06-21 18:04:09'),
(7, 'IT Department', '2026-06-21 18:04:09'),
(8, 'Laboratory', '2026-06-21 18:04:09'),
(9, 'Neurology', '2026-06-21 18:04:09'),
(10, 'Radiology', '2026-06-21 18:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `dept_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dept` (`dept_id`),
  ADD KEY `fk_branch` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `fk_dept` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
