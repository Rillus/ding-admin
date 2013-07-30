-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 29, 2013 at 04:06 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `ding-admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE IF NOT EXISTS `content_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `safe_name` varchar(256) NOT NULL,
  `fields` varchar(1600) NOT NULL,
  `description` varchar(1600) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `safe_name` (`safe_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `content_type`
--


-- --------------------------------------------------------

--
-- Table structure for table `field_type`
--

CREATE TABLE IF NOT EXISTS `field_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` varchar(1600) NOT NULL,
  `length` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `field_type`
--

INSERT INTO `field_type` (`id`, `name`, `description`, `length`) VALUES
(1, 'Text', 'A simple text field', 256),
(2, 'Long text', 'A text area for longer textual content', 1600),
(3, 'Date', 'A date field formatted as DD/MM/YYYY', 20),
(4, 'Date/time', 'Date and time formatted as DD/MM/YYYY HH:MM:SS', 20),
(5, 'Drop down selection', 'A drop down form element with multiple values to choose from', 1600),
(6, 'Integer', 'A simple number with no decimal places', 11),
(7, 'Decimal Number', 'A number with decimal places as described by "length" (default 2)', 2),
(8, 'Node reference', 'A link to an existing node', 11),
(9, 'URL', 'A text field for a URL', 256),
(10, 'Email', 'An email', 256),
(11, 'File upload', 'uploaded file', 256);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `safe_title` varchar(256) NOT NULL,
  `content_type` int(11) NOT NULL,
  `content` varchar(1600) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `safe_title` (`safe_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `node`
--


-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `permissions` varchar(1600) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `permissions`) VALUES
(1, 'Admin', 'a:5:{i:0;a:7:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:1:"1";i:5;s:1:"1";i:6;s:1:"1";}i:1;a:7:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:1:"1";i:5;s:1:"1";i:6;s:1:"1";}i:2;a:7:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:1:"1";i:5;s:1:"1";i:6;s:1:"1";}i:3;a:7:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:1:"1";i:5;s:1:"1";i:6;s:1:"1";}i:4;a:7:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:1:"1";i:5;s:1:"1";i:6;s:1:"1";}}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `forename` varchar(256) NOT NULL,
  `surname` varchar(256) NOT NULL,
  `permissions` int(11) NOT NULL,
  `status` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `users`
--


-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE IF NOT EXISTS `views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `safe_name` varchar(256) NOT NULL,
  `description` varchar(1600) NOT NULL,
  `view_type` int(11) NOT NULL,
  `content_type` int(11) NOT NULL,
  `fields` varchar(1600) NOT NULL,
  `settings` varchar(1600) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `views`
--


-- --------------------------------------------------------

--
-- Table structure for table `view_type`
--

CREATE TABLE IF NOT EXISTS `view_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` varchar(1600) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `view_type`
--

INSERT INTO `view_type` (`id`, `name`, `description`) VALUES
(1, 'Table', 'A simple table'),
(2, 'Dragboard', 'A planning board where nodes can be dragged between different states or categories.'),
(3, 'Check list', 'A simple list with a checkbox to mark when a list item is complete');
