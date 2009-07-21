-- 
-- Table structure for table `tweets`
-- 

CREATE TABLE `tweets` (
  `id` int(10) NOT NULL auto_increment,
  `msgid` varchar(32) default NULL,
  `username` varchar(1024) NOT NULL,
  `created` int(10) default '0',
  `msg` varchar(1024) NOT NULL,
  `matched` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_msgid` (`msgid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
