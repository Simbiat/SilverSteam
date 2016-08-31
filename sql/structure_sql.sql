-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Aug 31, 2016 at 03:09 AM
-- Server version: 5.5.50-cll
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `simbiatr_simbiat`
--
CREATE DATABASE IF NOT EXISTS `simbiatr_simbiat` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `simbiatr_simbiat`;

-- --------------------------------------------------------

--
-- Table structure for table `ss__dsgamelist`
--

CREATE TABLE IF NOT EXISTS `ss__dsgamelist` (
  `appID` int(20) NOT NULL DEFAULT '0',
  `parentid` int(11) NOT NULL,
  `UpdatedOn` int(11) NOT NULL DEFAULT '1356566220',
  `Addedon` int(11) NOT NULL DEFAULT '1356566220',
  `GameName` text CHARACTER SET utf8,
  `Language` text COLLATE utf8_unicode_ci,
  `gamedesc` longtext COLLATE utf8_unicode_ci NOT NULL,
  `gamefeatures` longtext COLLATE utf8_unicode_ci NOT NULL,
  `reqage` text COLLATE utf8_unicode_ci NOT NULL,
  `offwebsite` text COLLATE utf8_unicode_ci NOT NULL,
  `reqmin` longtext COLLATE utf8_unicode_ci NOT NULL,
  `reqrec` longtext COLLATE utf8_unicode_ci NOT NULL,
  `developers` longtext COLLATE utf8_unicode_ci NOT NULL,
  `publishers` longtext COLLATE utf8_unicode_ci NOT NULL,
  `genres` longtext COLLATE utf8_unicode_ci NOT NULL,
  `screenshots` longtext COLLATE utf8_unicode_ci NOT NULL,
  `releasedate` int(11) NOT NULL DEFAULT '0',
  `is_free` tinyint(1) DEFAULT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `appID` (`appID`),
  KEY `UpdatedOn` (`UpdatedOn`),
  KEY `Addedon` (`Addedon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPRESSED;

-- --------------------------------------------------------

--
-- Table structure for table `ss__dspatchnotes`
--

CREATE TABLE IF NOT EXISTS `ss__dspatchnotes` (
  `id` int(11) NOT NULL,
  `version` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ss__dsstaticvars`
--

CREATE TABLE IF NOT EXISTS `ss__dsstaticvars` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `parameter` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss__faq`
--

CREATE TABLE IF NOT EXISTS `ss__faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss__news`
--

CREATE TABLE IF NOT EXISTS `ss__news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=24 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
