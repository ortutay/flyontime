-- 
-- Table structure for table `tasks`
-- 

CREATE TABLE `tasks` (
  `id` int(10) NOT NULL auto_increment,
  `Expires` datetime NOT NULL default '0000-00-00 00:00:00',
  `HandlerNumber` int(10) NOT NULL default '0',
  `Completed` int(6) NOT NULL default '0',
  `InProgress` int(5) NOT NULL default '0',
  `ProgressStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `Params` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2697 DEFAULT CHARSET=latin1;
