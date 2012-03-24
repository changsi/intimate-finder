/*
 * CREATE DATABASE facebook_app DEFAULT CHARACTER SET utf8 CHARACTER SET utf8 COLLATE utf8_bin;
 */


CREATE TABLE `user` (
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `access_token` varchar(1000) NOT NULL DEFAULT '',
  `birth_date` date NOT NULL DEFAULT '0000-00-00',
  `gender` int unsigned NOT NULL DEFAULT '0' COMMENT '0: male 1:female',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `picture_url` varchar(1000) NOT NULL DEFAULT '',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



CREATE TABLE `user_friend` (
  `network_user_id_from` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id_to` bigint unsigned NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`network_user_id_from`,`network_user_id_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `location` (
	`location_id` bigint unsigned NOT NULL DEFAULT '0',
	`name` varchar(1000) NOT NULL DEFAULT '',
	`latitude` int NOT NULL DEFAULT '0',
	`longitude` int NOT NULL DEFAULT '0',
	`picture_url` varchar(1000) NOT NULL DEFAULT '',
	`address` varchar(1000) NOT NULL DEFAULT '',
	`description` varchar(50000) DEFAULT '',
	PRIMARY KEY (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `user_location` (
  `user_id` bigint NOT NULL DEFAULT '0',
  `location_id` bigint unsigned NOT NULL DEFAULT '0',
  `frequency` int unsigned NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`,`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


