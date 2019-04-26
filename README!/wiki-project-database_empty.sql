-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2018 at 11:01 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wiki-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `languageId` int(11) NOT NULL,
  `languageName` varchar(256) NOT NULL,
  `languageIcon` varchar(256) NOT NULL,
  `languageDeveloper` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `languagetags`
--

CREATE TABLE `languagetags` (
  `id` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(512) NOT NULL,
  `salt` varchar(512) NOT NULL,
  `userType` varchar(64) NOT NULL DEFAULT 'user',
  `activateSecret` varchar(256) NOT NULL,
  `recoverySecret` varchar(256) NOT NULL,
  `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tagId` int(11) NOT NULL,
  `tagName` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wikipage`
--

CREATE TABLE `wikipage` (
  `wikiPageId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `pageIndex` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wikipagedetails`
--

CREATE TABLE `wikipagedetails` (
  `wikiPageId` int(11) NOT NULL,
  `pageOwner` int(11) NOT NULL,
  `pageTitle` varchar(256) NOT NULL,
  `pageContent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wikipagetags`
--

CREATE TABLE `wikipagetags` (
  `id` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  `wikiPageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`languageId`);

--
-- Indexes for table `languagetags`
--
ALTER TABLE `languagetags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagId`);

--
-- Indexes for table `wikipage`
--
ALTER TABLE `wikipage`
  ADD PRIMARY KEY (`wikiPageId`);

--
-- Indexes for table `wikipagedetails`
--
ALTER TABLE `wikipagedetails`
  ADD PRIMARY KEY (`wikiPageId`);

--
-- Indexes for table `wikipagetags`
--
ALTER TABLE `wikipagetags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `languageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languagetags`
--
ALTER TABLE `languagetags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tagId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wikipage`
--
ALTER TABLE `wikipage`
  MODIFY `wikiPageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wikipagedetails`
--
ALTER TABLE `wikipagedetails`
  MODIFY `wikiPageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wikipagetags`
--
ALTER TABLE `wikipagetags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
