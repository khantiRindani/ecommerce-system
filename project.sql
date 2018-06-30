-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 20, 2018 at 04:59 AM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--
CREATE DATABASE IF NOT EXISTS `project` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `project`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--



-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `msg` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `subject`, `msg`) VALUES
(1, 'lsdf', '', '', ''),
(2, '', '', '', ''),
(3, 'khanti', 'krindani99@gmail.com', 'complaint', 'work hard');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE IF NOT EXISTS `delivery_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `add1` varchar(50) NOT NULL,
  `add2` varchar(50) NOT NULL,
  `add3` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `form_db`
--

CREATE TABLE IF NOT EXISTS `form_db` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `gender` enum('m','f') DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `pin` int(6) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `hobby` varchar(50) NOT NULL,
  `course` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_db`
--

INSERT INTO `form_db` (`id`, `firstname`, `lastname`, `dob`, `email`, `mobile`, `gender`, `address`, `city`, `pin`, `state`, `country`, `hobby`, `course`) VALUES
(1, 'nishchal', 'rindani', '1972-08-28', 'dsf@hdaf.com', '6565655665', 'f', 'kevallam', 'rajkot', 360005, 'gujarat', 'india', ',sketch', 'bsc'),
(2, 'khanti', 'rindani', '1999-06-03', 'krindani99@gmail.com', '7878787878', 'f', 'kevalam', 'rajkot', 233232, 'gujarat', 'india', ',dance', 'bsc'),
(3, 'test', 'dsgf', '2017-05-11', 'knr9webmail@gmail.com', '5665656565', 'm', 'somewhere', 'surat', 544554, 'gujarat', 'india', ',draw,sketch', 'bcom');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE IF NOT EXISTS `orderitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `registered` int(11) NOT NULL,
  `delivery_add_id` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `session` varchar(100) NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `txnid` varchar(20) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(25) NOT NULL,
  `itemid` varchar(25) NOT NULL,
  `createdtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) NOT NULL,
  `product_img` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `category` enum('electronics','clothing') NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_img`, `description`, `price`, `category`) VALUES
(1, 'Sony Xperia R1', 'images/products/sony1.jpg', 'dgiowr', '9170.00', 'electronics'),
(2, 'Sony Xperia M2', 'images/products/sony2.jpg', 'dsjdpgr', '1000.00', 'electronics'),
(3, 'Sony Alpha a7 III ', 'images/products/sony4.jpg', 'dafkje', '5000.00', 'electronics'),
(4, 'Sony MDR-ZX110', 'images/products/sony3.jpg', 'akleqf', '7000.00', 'electronics'),
(5, 'Sony SRS-D9 2.1 Multimedia Speakers', 'images/products/sony5.jpg', 'elqp4fkc', '8000.00', 'electronics'),
(6, 'Xperia™ Tablet Z', 'images/products/sony6.jpg', 'ajdfwoe', '4000.00', 'electronics'),
(9, 'Silk froke (Kids)', 'images/products/cloth2.jpg', 'pink color shiny party wear', '100.00', 'clothing'),
(10, 'Sweater thinwear', 'images/products/cloth3.jpg', 'soft and thin material, best suitable for your winter collection', '200.00', 'clothing'),
(11, 'Fancy top (Women)', 'images/products/cloth4.jpg', 'Stylish vibrant colored full sleeve top ', '120.00', 'clothing'),
(12, 'Black Fancy Shirt (Men)', 'images/products/cloth5.jpg', 'Most popular plain black shirt, now with trendy style', '150.00', 'clothing'),
(13, 'Woolen jacket (Men)', 'images/products/cloth6.jpg', 'Very comfortable and available in various colors', '200.00', 'clothing'),
(14, 'Party jacket (Men)', 'images/products/cloth7.jpg', 'Ever-trending leather jacket', '220.00', 'clothing'),
(15, 'test_elec', '', '', '0.00', 'electronics'),
(16, 'test_cloth', '', '', '0.00', 'clothing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'test123@mail.com', 'alohamora'),
(2, 'krindani99@gmail.com', 'test'),
(3, 'dummy123@gmail.com', 'whyme2'),
(4, 'admin', 'admin'),
(5, 'bal@mail.com', 'ku6bhi');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
