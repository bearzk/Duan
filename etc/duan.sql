CREATE TABLE `urls` (

  `hash` VARCHAR(48) NOT NULL,
  `url` VARCHAR(1024) NOT NULL,
  `customized` TINYINT(1) DEFAULT 0,
  `user_id` INT DEFAULT NULL,
  `clicks` INT DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`hash`),
  KEY `urls_url_index` (`url`),
  KEY `urls_custom_index` (`customized`)

) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `tokens` (

  `id` VARCHAR(255) NOT NULL,
  `user_id` INT NOT NULL,
  `name` VARCHAR(127) NOT NULL,
  `revoked` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  `expired_at` DATE,

  PRIMARY KEY (`id`),
  KEY `tokens_user_id` (`user_id`),
  KEY `tokens_revoked` (`revoked`)
)  ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (

  `id` INT NOT NULL AUTO_INCREMENT,
  `alias` VARCHAR(48) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `password` VARCHAR(2048) NOT NULL,
  `first_name` VARCHAR(48),
  `last_name` VARCHAR(48),
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW(),

  PRIMARY KEY (`id`),
  UNIQUE KEY `users_alias` (`alias`),
  UNIQUE KEY `users_email` (`email`)

) ENGINE=INNODB DEFAULT CHARSET=utf8;
