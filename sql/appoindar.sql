-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 06, 2017 at 05:35 PM
-- Server version: 5.6.31
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appoindar`
--

-- --------------------------------------------------------

--
-- Table structure for table `timetable_entry`
--

CREATE TABLE IF NOT EXISTS `timetable_entry` (
  `id` bigint(20) NOT NULL,
  `courseid` bigint(20) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `period_from` bigint(20) DEFAULT NULL,
  `period_to` bigint(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `instructor` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `lessonlink` varchar(255) DEFAULT NULL,
  `timecreated` bigint(20) DEFAULT NULL,
  `timemodified` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timetable_slot`
--

CREATE TABLE IF NOT EXISTS `timetable_slot` (
  `id` bigint(20) NOT NULL,
  `courseid` bigint(20) DEFAULT NULL,
  `period` bigint(20) DEFAULT NULL,
  `active` bigint(20) DEFAULT NULL,
  `start_time` varchar(100) DEFAULT NULL,
  `end_time` varchar(100) DEFAULT NULL,
  `display` varchar(255) DEFAULT NULL,
  `timecreated` bigint(20) DEFAULT NULL,
  `saturday_working` int(11) DEFAULT NULL,
  `saturday_start_period` varchar(100) DEFAULT NULL,
  `saturday_end_period` varchar(100) DEFAULT NULL,
  `sunday_working` int(11) DEFAULT NULL,
  `sunday_start_period` varchar(100) DEFAULT NULL,
  `sunday_end_period` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timetable_type`
--

CREATE TABLE IF NOT EXISTS `timetable_type` (
  `id` bigint(20) NOT NULL,
  `courseid` bigint(20) DEFAULT NULL,
  `activitytype` varchar(100) DEFAULT NULL,
  `activitycolor` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `shortname` varchar(30) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `conflict` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timetable_entry`
--
ALTER TABLE `timetable_entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseid` (`courseid`),
  ADD KEY `type` (`type`),
  ADD KEY `period_from` (`period_from`),
  ADD KEY `period_to` (`period_to`);

--
-- Indexes for table `timetable_slot`
--
ALTER TABLE `timetable_slot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseid` (`courseid`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `timetable_type`
--
ALTER TABLE `timetable_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseid` (`courseid`),
  ADD KEY `activitytype` (`activitytype`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timetable_entry`
--
ALTER TABLE `timetable_entry`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timetable_slot`
--
ALTER TABLE `timetable_slot`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timetable_type`
--
ALTER TABLE `timetable_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
