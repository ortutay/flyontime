-- 
-- Table structure for table `counters`
-- 

CREATE TABLE `counters` (
  `id` varchar(255) NOT NULL,
  `countsincereset` int(10) NOT NULL default '0',
  `resetdate` int(10) NOT NULL default '0',
  `lastdate` int(10) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
