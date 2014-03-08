SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE `a_catalog` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Keywords` varchar(1024) DEFAULT NULL,
  `Image_ID` int(10) unsigned DEFAULT NULL,
  `Priority` int(11) DEFAULT NULL,
  `PublishFrom` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name_UNIQUE` (`Name`),
  KEY `IX_Priority` (`Priority`),
  KEY `IX_Publish` (`PublishFrom`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

CREATE TABLE `a_client` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Created` date NOT NULL,
  `Changed` date NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Phone` varchar(16) DEFAULT NULL,
  `Mail` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IX_Name` (`Name`),
  KEY `IX_Phone` (`Phone`),
  KEY `IX_Mail` (`Mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `a_content` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `Category` varchar(128) NOT NULL,
  `Title` varchar(128) DEFAULT NULL,
  `Brief` tinytext,
  `Text` text NOT NULL,
  `Revision` int(11) DEFAULT NULL,
  `PublishFrom` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE `a_image` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Kind` varchar(45) NOT NULL DEFAULT 'Игрушка',
  `FileName` varchar(128) NOT NULL,
  `Width` int(10) unsigned NOT NULL DEFAULT '0',
  `Height` int(10) unsigned NOT NULL DEFAULT '0',
  `Alt` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

CREATE TABLE `a_order` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Created` date NOT NULL,
  `Changed` date NOT NULL,
  `Client_ID` int(10) unsigned NOT NULL,
  `DeliveryAddress` varchar(256) DEFAULT NULL,
  `DeliveryTime` varchar(128) DEFAULT NULL,
  `Info` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `a_product` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `Catalog_ID` int(10) unsigned NOT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Keywords` varchar(1024) DEFAULT NULL,
  `Priority` int(11) DEFAULT NULL,
  `PublishFrom` date DEFAULT NULL,
  `Price` decimal(19,2) NOT NULL,
  `Quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `Deadline` int(10) unsigned NOT NULL,
  `Manufacture` varchar(128) DEFAULT NULL,
  `Material` varchar(128) DEFAULT NULL,
  `Dimension` varchar(128) DEFAULT NULL,
  `Weight` decimal(5,1) DEFAULT NULL,
  `Popularity` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IX_Name` (`Catalog_ID`),
  KEY `IX_Priority` (`Priority`),
  KEY `IX_Publish` (`PublishFrom`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

CREATE TABLE `j_feedback` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Content` varchar(2048) NOT NULL,
  `PublishFrom` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `l_basket` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Order_ID` int(10) unsigned NOT NULL,
  `Product_ID` int(10) unsigned NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Price` decimal(19,2) NOT NULL,
  `Quantity` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `l_image` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Product_ID` int(10) unsigned NOT NULL,
  `Image_ID` int(10) unsigned NOT NULL,
  `Priority` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
