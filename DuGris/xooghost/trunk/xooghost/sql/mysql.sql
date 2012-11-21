CREATE TABLE `xooghost` (
  `xooghost_id` int(11) NOT NULL AUTO_INCREMENT,
  `xooghost_url` varchar(54) NOT NULL,
  `xooghost_title` varchar(255) NOT NULL,
  `xooghost_content` text NOT NULL,
  `xooghost_description` text NOT NULL,
  `xooghost_keywords` text NOT NULL,
  `xooghost_image` varchar(100) NOT NULL DEFAULT 'blank.png',
  `xooghost_published` int(10) NOT NULL DEFAULT '0',
  `xooghost_online` tinyint(1) NOT NULL DEFAULT '0',
  `xooghost_hits` int(10) NOT NULL DEFAULT '0',
  `xooghost_rates` float NOT NULL DEFAULT '0',
  `xooghost_like` int(11) NOT NULL DEFAULT '0',
  `xooghost_dislike` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`xooghost_id`)
) ENGINE=MyISAM;
