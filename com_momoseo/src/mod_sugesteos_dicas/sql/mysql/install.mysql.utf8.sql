CREATE TABLE IF NOT EXISTS `#__mom_sugestoes` (
	`id` int(10) PRIMARY KEY AUTO_INCREMENT,
	`titulo` varchar(50),
	`texto` varchar(25) NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;