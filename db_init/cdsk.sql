-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 07, 2014 at 08:25 
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cdsk`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_rewards`
--

DROP TABLE IF EXISTS `t_rewards`;
CREATE TABLE IF NOT EXISTS `t_rewards` (
  `i_rewardid` int(11) NOT NULL AUTO_INCREMENT,
  `i_pointsrequired` int(11) NOT NULL,
  `vch_name` varchar(255) NOT NULL,
  `i_childid` int(11) NOT NULL,
  `b_claimed` tinyint(1) NOT NULL,
  `b_confirmed` tinyint(1) NOT NULL,
  `dt_claimed` datetime NOT NULL,
  `dt_confirmed` datetime NOT NULL,
  PRIMARY KEY (`i_rewardid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `t_rewards`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_taskchild`
--

DROP TABLE IF EXISTS `t_taskchild`;
CREATE TABLE IF NOT EXISTS `t_taskchild` (
  `i_connectionid` int(11) NOT NULL AUTO_INCREMENT,
  `i_taskid` int(11) NOT NULL,
  `i_childid` int(11) NOT NULL,
  `b_claimed` tinyint(1) NOT NULL,
  `b_confirmed` tinyint(1) NOT NULL,
  `dt_claimed` datetime NOT NULL,
  `dt_confirmed` datetime NOT NULL,
  PRIMARY KEY (`i_connectionid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `t_taskchild`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_tasks`
--

DROP TABLE IF EXISTS `t_tasks`;
CREATE TABLE IF NOT EXISTS `t_tasks` (
  `i_taskid` int(11) NOT NULL AUTO_INCREMENT,
  `i_userid` int(11) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_deadline` datetime NOT NULL,
  `i_points` int(11) NOT NULL,
  `txt_description` text NOT NULL,
  PRIMARY KEY (`i_taskid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `t_tasks`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_tasktypes`
--

DROP TABLE IF EXISTS `t_tasktypes`;
CREATE TABLE IF NOT EXISTS `t_tasktypes` (
  `i_tasktypeid` int(11) NOT NULL AUTO_INCREMENT,
  `txt_description` text NOT NULL,
  `i_userid` int(11) NOT NULL,
  `i_points` int(11) NOT NULL,
  PRIMARY KEY (`i_tasktypeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `t_tasktypes`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

DROP TABLE IF EXISTS `t_users`;
CREATE TABLE IF NOT EXISTS `t_users` (
  `i_userid` int(11) NOT NULL AUTO_INCREMENT,
  `vch_email` varchar(255) NOT NULL,
  `vch_password` varchar(255) NOT NULL,
  `vch_firstname` varchar(255) NOT NULL,
  `vch_surname` varchar(255) NOT NULL,
  `d_birthdate` date NOT NULL,
  `dt_registered` datetime NOT NULL,
  `i_parentid` int(11) NOT NULL DEFAULT '0',
  `vch_mobile` varchar(255) NOT NULL,
  `dt_lastvisit` datetime NOT NULL,
  PRIMARY KEY (`i_userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`i_userid`, `vch_email`, `vch_password`, `vch_firstname`, `vch_surname`, `d_birthdate`, `dt_registered`, `i_parentid`, `vch_mobile`, `dt_lastvisit`) VALUES
(1, 'bwieczorkowski@gmail.com', 'c433524b2fc5ad9009bf3f7681babcd9', 'Bart', 'Wieczorkowski', '0000-00-00', '2014-05-03 09:52:24', 0, '', '2014-05-05 11:10:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
