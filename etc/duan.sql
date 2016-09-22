CREATE TABLE `urls` (
  `hash` varchar(40) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `customized` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`hash`),
  KEY `urls_url_index` (`url`),
  KEY `urls_custom_index` (`customized`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
