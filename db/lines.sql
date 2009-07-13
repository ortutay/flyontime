-- 
-- Table structure for table `lines`
-- 

CREATE TABLE `lines` (
  `id` int(10) NOT NULL auto_increment,
  `userhash` varchar(255) default NULL,
  `linetype` varchar(8) NOT NULL,
  `airportcode` varchar(8) NOT NULL,
  `airlinecode` varchar(8) NOT NULL,
  `linename` varchar(255) NOT NULL,
  `in` datetime NOT NULL default '0000-00-00 00:00:00',
  `out` datetime NOT NULL default '0000-00-00 00:00:00',
  `diff` int(20) default '0',
  `inyear` int(4) NOT NULL default '0',
  `inmonth` int(4) NOT NULL default '0',
  `indayofmonth` int(4) NOT NULL default '0',
  `indayofweek` int(4) NOT NULL default '0',
  `intimeblk15` varchar(9) default NULL,
  `intimeblk30` varchar(9) NOT NULL,
  `intimeblk60` varchar(9) NOT NULL,
  `source` varchar(255) NOT NULL,
  `useragent` varchar(255) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
