CREATE TABLE `urls` (
  `h` varchar(40) NOT NULL,
  `u` varchar(1024) NOT NULL,
  `c` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`h`),
  UNIQUE KEY `urls_url_index` (`u`),
  KEY `urls_custom_index` (`c`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;