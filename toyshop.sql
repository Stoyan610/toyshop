-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Янв 17 2014 г., 00:17
-- Версия сервера: 5.5.25
-- Версия PHP: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `toyshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `a_catalog`
--

CREATE TABLE IF NOT EXISTS `a_catalog` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `a_catalog`
--

INSERT INTO `a_catalog` (`ID`, `Name`, `Description`, `Keywords`, `Image_ID`, `Priority`, `PublishFrom`) VALUES
(1, 'Первый мультфильм', 'Самый первый мультфильм, самый популярный', 'самый', 1, 1, '2014-01-01');

-- --------------------------------------------------------

--
-- Структура таблицы `a_client`
--

CREATE TABLE IF NOT EXISTS `a_client` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Created` datetime NOT NULL,
  `Changed` datetime DEFAULT NULL,
  `Name` varchar(128) NOT NULL,
  `Phone` varchar(16) DEFAULT NULL,
  `Mail` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IX_Name` (`Name`),
  KEY `IX_Phone` (`Phone`),
  KEY `IX_Mail` (`Mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `a_content`
--

CREATE TABLE IF NOT EXISTS `a_content` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `Category` varchar(128) NOT NULL,
  `Title` varchar(128) DEFAULT NULL,
  `Brief` tinytext,
  `Text` text NOT NULL,
  `Revision` int(11) DEFAULT NULL,
  `PublishFrom` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `a_image`
--

CREATE TABLE IF NOT EXISTS `a_image` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FileName` varchar(128) NOT NULL,
  `Width` int(10) unsigned NOT NULL,
  `Height` int(10) unsigned NOT NULL,
  `Alt` varchar(128) DEFAULT NULL,
  `SmallFile` varchar(128) DEFAULT NULL,
  `ThumbnailFile` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `a_image`
--

INSERT INTO `a_image` (`ID`, `FileName`, `Width`, `Height`, `Alt`, `SmallFile`, `ThumbnailFile`) VALUES
(1, 'emptymult', 0, 0, 'пустой', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `a_order`
--

CREATE TABLE IF NOT EXISTS `a_order` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Created` datetime NOT NULL,
  `Changed` datetime DEFAULT NULL,
  `Client_ID` int(10) unsigned NOT NULL,
  `DeliveryAddress` varchar(256) DEFAULT NULL,
  `DeliveryTime` varchar(128) DEFAULT NULL,
  `Info` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `a_product`
--

CREATE TABLE IF NOT EXISTS `a_product` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Catalog_ID` int(10) unsigned NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Description` varchar(1024) DEFAULT NULL,
  `Keywords` varchar(1024) DEFAULT NULL,
  `Image_ID` int(10) unsigned DEFAULT NULL,
  `Priority` int(11) DEFAULT NULL,
  `PublishFrom` date DEFAULT NULL,
  `Price` decimal(19,2) NOT NULL,
  `Quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `Manufacture` varchar(128) DEFAULT NULL,
  `Material` varchar(128) DEFAULT NULL,
  `Dimension` varchar(128) DEFAULT NULL,
  `Weight` decimal(5,1) DEFAULT NULL,
  `Deadline` int(10) unsigned DEFAULT NULL,
  `Popularity` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IX_Name` (`Name`),
  KEY `IX_Priority` (`Priority`),
  KEY `IX_Publish` (`PublishFrom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `j_feedback`
--

CREATE TABLE IF NOT EXISTS `j_feedback` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Content` varchar(2048) NOT NULL,
  `PublishFrom` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `l_basket`
--

CREATE TABLE IF NOT EXISTS `l_basket` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Order_ID` int(10) unsigned NOT NULL,
  `Product_ID` int(10) unsigned NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Price` decimal(19,2) NOT NULL,
  `Quantity` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `l_product_image`
--

CREATE TABLE IF NOT EXISTS `l_product_image` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Product_ID` int(10) unsigned NOT NULL,
  `Image_ID` int(10) unsigned NOT NULL,
  `Priority` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IX_Priority` (`Priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
