-- 
-- Table structure for table `enums`
-- 

CREATE TABLE `enums` (
  `id` int(10) NOT NULL auto_increment,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `code_category` (`code`,`category`)
) ENGINE=MyISAM AUTO_INCREMENT=14046 DEFAULT CHARSET=latin1;
