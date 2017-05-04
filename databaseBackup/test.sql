-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 04, 2017 at 04:26 pm
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `dependencies`
--

CREATE TABLE `dependencies` (
  `ParentId` int(11) NOT NULL,
  `ChildId` int(11) NOT NULL,
  `Taught` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dependencies`
--

INSERT INTO `dependencies` (`ParentId`, `ChildId`, `Taught`) VALUES
(4, 11, 1),
(11, 13, 1),
(11, 12, 1),
(11, 20, 1),
(17, 18, 1),
(20, 21, 1),
(20, 22, 1),
(22, 23, 1),
(22, 24, 1),
(17, 19, 1),
(13, 15, 1),
(17, 15, 1),
(9, 10, 1),
(9, 4, 1),
(9, 17, 1),
(28, 29, 1),
(30, 28, 1),
(1, 9, 1),
(1, 2, 1),
(46, 39, 0),
(31, 33, 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `TopicId` int(11) NOT NULL,
  `Mark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackId`, `UserId`, `TopicId`, `Mark`) VALUES
(1, 2, 1, 5),
(2, 2, 9, 5),
(4, 2, 10, 4),
(6, 3, 1, 3),
(7, 3, 9, 4),
(8, 3, 2, 3),
(9, 3, 10, 4),
(10, 3, 4, 4),
(11, 4, 9, 4),
(12, 4, 2, 5),
(13, 4, 1, 5),
(14, 5, 1, 5),
(15, 5, 2, 4),
(16, 5, 10, 5),
(17, 2, 2, 2),
(18, 2, 12, 4),
(19, 2, 30, 5),
(20, 2, 28, 2),
(21, 2, 29, 1),
(23, 2, 20, 5),
(24, 2, 21, 3),
(27, 2, 13, 5),
(32, 2, 4, 2),
(39, 2, 18, 1),
(40, 2, 19, 2),
(41, 2, 11, 5),
(42, 2, 15, 2),
(43, 2, 22, 2),
(44, 2, 23, 1),
(45, 2, 24, 1),
(46, 2, 31, 5),
(49, 2, 33, 2),
(51, 3, 17, 2),
(52, 3, 11, 3),
(53, 3, 13, 2),
(54, 3, 12, 3),
(55, 3, 20, 4),
(56, 3, 15, 1),
(57, 3, 18, 3),
(58, 3, 19, 4),
(59, 3, 22, 3),
(62, 3, 21, 1),
(63, 3, 23, 3),
(64, 3, 24, 3),
(65, 4, 4, 4),
(66, 4, 12, 3),
(67, 4, 18, 5),
(68, 4, 20, 5),
(69, 5, 9, 4),
(70, 5, 4, 5),
(71, 5, 17, 1),
(72, 5, 18, 1),
(73, 5, 19, 1),
(74, 5, 15, 2),
(75, 5, 11, 5),
(76, 5, 13, 4),
(77, 5, 12, 5),
(78, 5, 20, 4),
(79, 5, 21, 4),
(80, 5, 22, 5),
(81, 5, 23, 4),
(82, 5, 24, 4),
(83, 2, 17, 5),
(84, 6, 17, 5),
(85, 4, 17, 2);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `Code` varchar(20) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`Code`, `Name`) VALUES
('CS101', 'Java Programming');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `TopicId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `ModuleCode` varchar(20) NOT NULL,
  `Taught` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`TopicId`, `Name`, `Description`, `ModuleCode`, `Taught`) VALUES
(1, 'Understanding Programs', 'The big picture idea of what is a computer program.', 'CS101', 1),
(2, 'Compile and run a Java program', 'A simple first \"Hello world!\" Java program.\r\nUnderstanding the concept of computer compilation.', 'CS101', 1),
(4, 'Comparisons', 'Comparing two variables to get a Boolean result. Looks at AND/OR operators.', 'CS101', 1),
(9, 'Variables', 'This covers:\r\n- Booleans\r\n- Strings \r\n- Integers', 'CS101', 1),
(10, 'Processing user input', 'This topic covers handling user input and processing and providing an output.', 'CS101', 1),
(11, 'If/else statements', 'Use conditions to make computations choices in your program.', 'CS101', 1),
(12, 'Switch statement', 'Understand the syntax of a switch statement and how it can be used to check for different types of conditions.', 'CS101', 1),
(13, 'While loop', 'Introduce looping in a program starting with a simple condition.', 'CS101', 1),
(15, 'For loop', 'This topic looks at the syntax and the use case for a For loop.', 'CS101', 1),
(17, 'Arrays', 'Look at how arrays are structured and used to store large amounts of data.', 'CS101', 1),
(18, '2D arrays', 'Look at how to store and use arrays with more than one dimension.', 'CS101', 1),
(19, 'For each loop', 'Look at the process of iterating through an array.', 'CS101', 1),
(20, 'Methods', 'Show how methods can be used to separate code logically and improve maintainability.', 'CS101', 1),
(21, 'Variable scope', 'Understand about the use of variables within methods.', 'CS101', 1),
(22, 'Classes', 'This topic covers the basic idea of a class and how and why it is used.', 'CS101', 1),
(23, 'Interfaces', 'This looks in more detail at the structure of an interface and why interfaces are used.', 'CS101', 1),
(24, 'Encapsulation', 'This topic covers a big picture view of classes and how they help to encapsulate data.', 'CS101', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `HashedPassword` varchar(255) NOT NULL,
  `Role` int(11) NOT NULL,
  `Disability` tinyint(1) NOT NULL,
  `Grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserId`, `Username`, `HashedPassword`, `Role`, `Disability`, `Grade`) VALUES
(1, 'lecturer1', '$2y$10$.m3eD47HkUfrgkWlNrYhuO88cccFgMLSS2M9U9eftNaHaa5TqWPaS', 1, 0, 0),
(2, 'student1', '$2y$10$qlP6PLbcpu.AzQj.JoXx7uKJQ7UMR7iMXu/uPG5Na6m5OXMFcjLcW', 0, 0, 40),
(3, 'student2', '$2y$10$JpToa6PnO74pLIqIPiyXruTpovKh9LHRuZpbdiE0qK2TIM7qcw6Eu', 0, 1, 60),
(4, 'student3', '$2y$10$X3Gmj4OwRqeHb8qyNYvceOlADRvzb1BZgtUlidQFzUFBodmNJWBuK', 0, 0, 80),
(5, 'student4', '$2y$10$XsVfOvkGQOb0oIP8CrM1bOag5mAb/Fg54sTXA/dgZsLh0ZdJbtZdW', 0, 0, 0),
(6, 'student5', '$2y$10$PVSKXMjQCN0RZeTxLJCiTuDWzqYOyePFicWudsEFuniOdqTxBh2xm', 0, 0, 0),
(7, 'student6', '$2y$10$7bfTQj87G.WWVqFZSHdtee78R99eacAp3P8FjGHBeFgvuFrzWKodK', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackId`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`Code`),
  ADD UNIQUE KEY `Code` (`Code`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`TopicId`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `TopicId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
