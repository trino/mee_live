-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2015 at 05:42 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `veritas3`
--

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commodity` varchar(255) NOT NULL COMMENT 'test comment',
  `Percent_of_Revenue` varchar(255) NOT NULL COMMENT '[format=percent]',
  `Average_Load_Value` varchar(255) NOT NULL,
  `Maximum_Load_Value` varchar(255) NOT NULL,
  `Times_per_Month_Value_Exceeds_Average` varchar(255) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `commodity`, `Percent_of_Revenue`, `Average_Load_Value`, `Maximum_Load_Value`, `Times_per_Month_Value_Exceeds_Average`, `Comment`) VALUES
(3, '[colspan=6,format=uppercase,bgcolor=lightblue]Category 1', '0.0000000000', '0', '0', '0', ''),
(4, 'Beer', '0.01', '1', '0', '0', ''),
(5, 'Liquor or wine', '0.02', '0', '0', '0', ''),
(6, 'Metals - high value', '0.03', '2', '0', '0', ''),
(7, 'Electronics', '0.0000000000', '0', '0', '0', ''),
(8, 'Pharmaceuticals', '0.0000000000', '0', '0', '0', ''),
(9, 'Tobacco', '0.0000000000', '0', '0', '0', ''),
(10, 'Tools (hand or power)', '0.0000000000', '0', '0', '0', ''),
(11, 'Explosives, munitions', '0.0000000000', '0', '0', '0', ''),
(12, '[align=right]Total', '=sum(C3:ME) ', '=sum(D3:ME)', '0', '0', ''),
(13, '[colspan=6,format=uppercase,bgcolor=lightblue]Category 2', '', '', '', '', ''),
(14, 'Clothing, textiles', '0.00', '', '', '', ''),
(15, 'Food - Seafood or shellfish', '0.00', '', '', '', ''),
(16, 'Food - Produce', '0.0', '', '', '', ''),
(17, 'Food - Other temperature sensative', '0.0', '', '', '', ''),
(18, 'Flowers, bulbs, nursery stock', '0.0', '', '', '', ''),
(19, 'Liquids in Bulk - Hazardous/regulated', '0.0', '', '', '', ''),
(20, 'Liquids in Bulk - Non-hazardous', '0.0', '', '', '', ''),
(21, 'Tires', '0.0', '', '', '', ''),
(22, '[align=right]Total', '=sum(C14:ME) ', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
