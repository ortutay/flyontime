-- MySQL dump 10.13  Distrib 5.1.36, for redhat-linux-gnu (i686)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	5.1.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ontime`
--

DROP TABLE IF EXISTS `ontime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ontime` (
  `origin` char(3) COLLATE ascii_bin NOT NULL DEFAULT '',
  `dest` char(3) COLLATE ascii_bin NOT NULL DEFAULT '',
  `carrier` varchar(3) COLLATE ascii_bin NOT NULL DEFAULT '',
  `flightnum` int(11) NOT NULL DEFAULT '0',
  `dayofweek` tinyint(4) NOT NULL DEFAULT '0',
  `hour` char(2) COLLATE ascii_bin NOT NULL DEFAULT '',
  `holiday` enum('memorial-1','memorial','memorial+1','labor','thanksgiving-1','thanksgiving','thanksgiving+1','thanksgiving+1','thanksgiving+2','thanksgiving+3','christmas-1','christmas','christmas+1') COLLATE ascii_bin NOT NULL DEFAULT 'memorial-1',
  `firstdate` char(10) COLLATE ascii_bin NOT NULL,
  `lastdate` char(10) COLLATE ascii_bin NOT NULL,
  `condition` enum('all','origin_any_no','origin_any_yes','origin_fog_no','origin_fog_yes','origin_snow_no','origin_snow_yes','origin_thunder_no','origin_thunder_yes','origin_hail_no','origin_hail_yes','origin_tornado_no','origin_tornado_yes','dest_any_no','dest_any_yes','dest_fog_no','dest_fog_yes','dest_snow_no','dest_snow_yes','dest_thunder_no','dest_thunder_yes','dest_hail_no','dest_hail_yes','dest_tornado_no','dest_tornado_yes','origin_rain_no','origin_rain_yes','dest_rain_no','dest_rain_yes','either_any_no','either_any_yes') COLLATE ascii_bin NOT NULL,
  `count` int(11) NOT NULL,
  `pct_cancel` float DEFAULT NULL,
  `pct_20mindelay` float DEFAULT NULL,
  `pct_ontime` float DEFAULT NULL,
  `delay_15thpctile` int(11) DEFAULT NULL,
  `delay_median` int(11) DEFAULT NULL,
  `delay_85thpctile` int(11) DEFAULT NULL,
  PRIMARY KEY (`origin`,`dest`,`carrier`,`flightnum`,`dayofweek`,`hour`,`holiday`,`condition`),
  KEY `carrier` (`carrier`,`flightnum`,`dest`,`condition`),
  KEY `carrier_2` (`carrier`,`origin`),
  KEY `carrier_3` (`carrier`,`condition`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-07-15 16:51:28
