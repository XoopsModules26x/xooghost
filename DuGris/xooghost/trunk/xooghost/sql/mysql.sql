CREATE TABLE `xooghost` (
  `xooghost_id` int(11) NOT NULL AUTO_INCREMENT,
  `xooghost_url` varchar(54) NOT NULL,
  `xooghost_title` varchar(255) NOT NULL,
  `xooghost_uid` int(8) NOT NULL DEFAULT '0',
  `xooghost_content` text NOT NULL,
  `xooghost_description` text NOT NULL,
  `xooghost_keywords` text NOT NULL,
  `xooghost_image` varchar(100) NOT NULL DEFAULT 'blank.png',
  `xooghost_published` int(10) NOT NULL DEFAULT '0',
  `xooghost_online` tinyint(1) NOT NULL DEFAULT '0',
  `xooghost_hits` int(10) NOT NULL DEFAULT '0',
  `xooghost_rates` float(5,2) NOT NULL DEFAULT '0.00',
  `xooghost_like` int(11) NOT NULL DEFAULT '0',
  `xooghost_dislike` int(11) NOT NULL DEFAULT '0',
  `xooghost_comments` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`xooghost_id`)
) ENGINE=MyISAM;

CREATE TABLE `xooghost_rld` (
  `xooghost_rld_id` int(11) NOT NULL AUTO_INCREMENT,
  `xooghost_rld_page` int(11) NOT NULL DEFAULT '0',
  `xooghost_rld_uid` int(11) NOT NULL DEFAULT '0',
  `xooghost_rld_time` int(10) NOT NULL DEFAULT '0',
  `xooghost_rld_ip` mediumtext NOT NULL,
  `xooghost_rld_rates` tinyint(2) NOT NULL DEFAULT '0',
  `xooghost_rld_like` tinyint(1) NOT NULL DEFAULT '0',
  `xooghost_rld_dislike` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`xooghost_rld_id`)
) ENGINE=MyISAM COMMENT='RLD = Rates / Like / Dislike';