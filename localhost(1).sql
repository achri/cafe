-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 02, 2011 at 12:03 
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_cafe`
--
CREATE DATABASE `db_cafe` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `db_cafe`;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` text,
  `ip_address` text,
  `user_agent` text,
  `last_activity` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`) VALUES
('351e31183ada95babdd2b4d4ad5e401c', '0.0.0.0', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:2.0b9p', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stok`
--

CREATE TABLE IF NOT EXISTS `inventory_stok` (
  `inv_id` int(11) NOT NULL AUTO_INCREMENT,
  `bumbu_id` int(11) NOT NULL,
  `inv_begin` int(11) NOT NULL,
  `inv_in` int(11) NOT NULL,
  `inv_out` int(11) NOT NULL,
  `inv_end` int(11) NOT NULL,
  `inv_type` int(11) NOT NULL,
  PRIMARY KEY (`inv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `inventory_stok`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_bumbu`
--

CREATE TABLE IF NOT EXISTS `master_bumbu` (
  `bumbu_id` int(11) NOT NULL AUTO_INCREMENT,
  `bumbu_kode` varchar(50) NOT NULL,
  `bumbu_nama` varchar(50) NOT NULL,
  `kat_id` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bumbu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `master_bumbu`
--

INSERT INTO `master_bumbu` (`bumbu_id`, `bumbu_kode`, `bumbu_nama`, `kat_id`, `satuan_id`, `status`) VALUES
(1, '', 'SALMON', 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `master_bumbu_satuan`
--

CREATE TABLE IF NOT EXISTS `master_bumbu_satuan` (
  `bumbu_id` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL,
  `satuan_unit_id` int(11) NOT NULL,
  `volume` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_bumbu_satuan`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_kategori`
--

CREATE TABLE IF NOT EXISTS `master_kategori` (
  `kat_id` int(11) NOT NULL AUTO_INCREMENT,
  `kat_kode` varchar(100) NOT NULL,
  `kat_master` int(11) NOT NULL DEFAULT '0',
  `kat_level` int(11) NOT NULL DEFAULT '1',
  `kat_nama` varchar(255) NOT NULL,
  `kat_tipe` set('menu','bumbu') NOT NULL DEFAULT 'menu',
  PRIMARY KEY (`kat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `master_kategori`
--

INSERT INTO `master_kategori` (`kat_id`, `kat_kode`, `kat_master`, `kat_level`, `kat_nama`, `kat_tipe`) VALUES
(1, '01', 0, 1, 'IMPORT', 'bumbu'),
(2, '01.01', 1, 2, 'IKAN', 'bumbu'),
(3, '01.01.01', 2, 3, 'LAUT', 'bumbu'),
(4, '01.01.02', 2, 3, 'TAWAR', 'bumbu');

-- --------------------------------------------------------

--
-- Table structure for table `master_menu`
--

CREATE TABLE IF NOT EXISTS `master_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_session` text NOT NULL,
  `menu_kode` varchar(50) NOT NULL,
  `menu_nama` varchar(255) NOT NULL,
  `kat_id` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `diskon` int(11) NOT NULL DEFAULT '0',
  `ppn` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `master_menu`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_menu_bumbu`
--

CREATE TABLE IF NOT EXISTS `master_menu_bumbu` (
  `menu_id` int(11) NOT NULL,
  `bumbu_id` int(11) NOT NULL,
  `jml` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_menu_bumbu`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_satuan`
--

CREATE TABLE IF NOT EXISTS `master_satuan` (
  `satuan_id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan_nama` varchar(255) NOT NULL,
  `satuan_format` int(11) NOT NULL,
  PRIMARY KEY (`satuan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `master_satuan`
--

INSERT INTO `master_satuan` (`satuan_id`, `satuan_nama`, `satuan_format`) VALUES
(1, 'KG', 0),
(2, 'LITTER', 0),
(3, 'KARUNG', 0),
(4, 'PCS', 0),
(6, 'BUNGKUS', 0),
(8, 'RACK', 0),
(9, 'BOTOL', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_bill`
--

CREATE TABLE IF NOT EXISTS `order_bill` (
  `bill_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_tgl` datetime NOT NULL,
  `tot_jml` int(11) NOT NULL DEFAULT '0',
  `tot_diskon` int(11) NOT NULL DEFAULT '0',
  `tot_ppn` int(11) NOT NULL DEFAULT '0',
  `tot_harga` int(11) NOT NULL DEFAULT '0',
  `bayar` int(11) NOT NULL DEFAULT '0',
  `sisa` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  `tgl_akses` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pengguna` int(11) NOT NULL,
  PRIMARY KEY (`bill_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `order_bill`
--


-- --------------------------------------------------------

--
-- Table structure for table `order_menu`
--

CREATE TABLE IF NOT EXISTS `order_menu` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) NOT NULL DEFAULT '0',
  `order_session` text NOT NULL,
  `order_tgl` datetime NOT NULL,
  `no_meja` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `notifikasi` int(11) NOT NULL DEFAULT '1',
  `tgl_akses` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pengguna` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `order_menu`
--


-- --------------------------------------------------------

--
-- Table structure for table `order_menu_detail`
--

CREATE TABLE IF NOT EXISTS `order_menu_detail` (
  `order_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_tgl` datetime NOT NULL,
  `jml` int(11) NOT NULL DEFAULT '0',
  `diskon` int(11) NOT NULL DEFAULT '0',
  `total` int(11) NOT NULL DEFAULT '0',
  `done` int(11) NOT NULL DEFAULT '0',
  `tgl_akses` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pengguna` int(11) NOT NULL,
  PRIMARY KEY (`order_detail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `order_menu_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `sys_user`
--

CREATE TABLE IF NOT EXISTS `sys_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(25) NOT NULL,
  `user_pass1` varchar(50) NOT NULL,
  `user_pass2` varchar(50) NOT NULL,
  `jab_id` int(11) NOT NULL,
  `lastTime_log` datetime NOT NULL,
  `lastIP_log` varchar(50) NOT NULL,
  `newTime_log` datetime NOT NULL,
  `newIP_log` varchar(50) NOT NULL,
  `offTime_log` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sys_user`
--

INSERT INTO `sys_user` (`user_id`, `user_login`, `user_pass1`, `user_pass2`, `jab_id`, `lastTime_log`, `lastIP_log`, `newTime_log`, `newIP_log`, `offTime_log`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 'caf1a3dfb505ffed0d024130f58c5cfa', 0, '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');
