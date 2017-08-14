-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2017 at 11:24 PM
-- Server version: 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 7.0.18-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timetable`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `batch` int(4) NOT NULL,
  `email` varchar(18) NOT NULL,
  `subjects` varchar(50) NOT NULL,
  `sections` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `short` varchar(50) NOT NULL,
  `code` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `short`, `code`) VALUES
(1, 'Introduction to Computing', 'ITC', 'CS101'),
(2, 'Introduction to Computing Lab', 'ITC Lab', 'CL101'),
(3, 'Calculus - I', 'CAL I', 'MT101'),
(4, 'Basic Electronics', 'BE', 'EE182'),
(5, 'English Language', 'ENG', 'SS102'),
(6, 'English Language Lab', 'ENG', 'SL102'),
(7, 'Islamic and Religious Studies', 'IRS', 'SS111'),
(8, 'Ethics', 'Ethics', 'SS203'),
(9, 'Computer Programming', 'CP', 'CS103'),
(10, 'Computer Programming Lab', 'CP-Lab', 'CL103'),
(11, 'Digital Logic Design', 'DLD', 'EE227'),
(12, 'Digital Logic Design', 'DLD-Lab', 'EL227'),
(13, 'Calculus-II', 'Cal-II', 'MT115'),
(14, 'English Composition', 'Eng Comp.', 'SS122'),
(15, 'Pakistan Studies', 'Pk. Study', 'SS113');

-- --------------------------------------------------------

--
-- Table structure for table `vercode`
--

CREATE TABLE `vercode` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vercode`
--
ALTER TABLE `vercode`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `vercode`
--
ALTER TABLE `vercode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
