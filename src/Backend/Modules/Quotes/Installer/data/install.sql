CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `modules_extras_id` int(11),
  `language` varchar(2) NOT NULL DEFAULT 'nl',
  `quote` text NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `edited_on` datetime NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
