/*
 * CREATE DATABASE facebook_app DEFAULT CHARACTER SET utf8 CHARACTER SET utf8 COLLATE utf8_bin;
 */

CREATE TABLE `network` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

insert network (id, name) values (1, 'TWITTER');
insert network (id, name) values (2, 'FACEBOOK');

CREATE TABLE `user_network` (
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `hash_user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `network_user_id` bigint unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `screen_name` varchar(200) NOT NULL DEFAULT '',
  `access_token` varchar(1000) NOT NULL DEFAULT '',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invitation_pending` INT NOT NULL DEFAULT '0' COMMENT "1 if the CP is still running for the user and the user is specially invited",
  PRIMARY KEY (`network_id`,`network_user_id`),
  UNIQUE KEY `idx_uk_user_network` (`network_id`,`network_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `network_post_url` (
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id` bigint NOT NULL DEFAULT '0',
  `network_post_id` bigint unsigned NOT NULL DEFAULT '0',
  `url_id` bigint NOT NULL DEFAULT 0,
  `url` varchar(2048) NOT NULL DEFAULT '',
  `real_url` varchar(2048) NOT NULL DEFAULT '',
  `control_flag` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: not yet in core_platform, 1: already in core_platform',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`network_id`,`network_user_id`,`network_post_id`,`url_id`),
  KEY `index_network_post_url` (`network_post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `network_friend` (
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id_from` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id_to` bigint unsigned NOT NULL DEFAULT '0',
  `control_flag` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: Recently inserted, 1: Old but still present in the system, 2: To be deleted Friends',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`network_id`,`network_user_id_from`,`network_user_id_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `object` (
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_object_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'hash of the book name+author',
  `url` varchar(1000) NOT NULL DEFAULT '',
  `name` varchar(200) NOT NULL DEFAULT '',
  `author` varchar(200) NOT NULL DEFAULT '' COMMENT 'can also be director for a movie',
  `genre` varchar(200) NOT NULL DEFAULT '',
  `genre2` varchar(200) NOT NULL DEFAULT '',
  `release_date` timestamp NULL,
  PRIMARY KEY (`object_type_id`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='generic table storing all kinds of obj (games,movies,books..)';

CREATE TABLE `user_object` (
  `network_user_id` bigint NOT NULL DEFAULT '0',
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`network_user_id`,`object_type_id`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

alter table user_object add column (`date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'represents the date when the user liked the object in FB');

CREATE TABLE `object_type` (
  `id` bigint unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `object_count` (
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `count` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'count of all the users having this object',
  PRIMARY KEY (`object_type_id`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `user_network_data` (
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id` bigint unsigned NOT NULL DEFAULT '0',
  `age` int unsigned NOT NULL DEFAULT '0',
  `gender` int unsigned NOT NULL DEFAULT '0' COMMENT 'Male 1 , female 2 ',
  `education` varchar(10000) NOT NULL DEFAULT '' COMMENT 'json array of fb education',
  `current_location` varchar(200) NOT NULL DEFAULT '' COMMENT 'where the user lives',
  `from_location` varchar(200) NOT NULL DEFAULT '' COMMENT 'where the user is from',
  `relationship_id` int unsigned NOT NULL DEFAULT '0' COMMENT 'list the different types id of the user relationsip status',
  `email` varchar(200) NOT NULL DEFAULT '',
  `control_flag` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: not yet parsed, 1: already parsed in core_platform',
  PRIMARY KEY (`network_id`,`network_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `relationship` (
  `id` int unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

insert into relationship values (0, 'unknown');
insert into relationship values (1, 'single');
insert into relationship values (2, 'couple');
insert into relationship values (3, 'engaged');
insert into relationship values (4, 'married');
insert into relationship values (5, 'complicated');
insert into relationship values (6, 'free');
insert into relationship values (7, 'widow');
insert into relationship values (8, 'separated');
insert into relationship values (9, 'divorced');
insert into relationship values (10, 'civil_union');
insert into relationship values (11, 'domestic_partnership');

insert into object_type values (0, 'unknown');
insert into object_type values (1, 'movie');
insert into object_type values (2, 'book');
insert into object_type values (3, 'music');
insert into object_type values (4, 'game');
insert into object_type values (5, 'television');
insert into object_type values (6, 'actvity');
insert into object_type values (7, 'interest');
insert into object_type values (8, 'other');
insert into object_type values (9,'manual');
insert into object_type values(10, 'link');

CREATE TABLE `object_url` (
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `url_id` bigint NOT NULL DEFAULT 0 COMMENT 'the url_id is a hash for the url',
  `url` varchar(2048) NOT NULL DEFAULT '',
  PRIMARY KEY (`object_id` , `url_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `recommended_user_object` (
  `network_user_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`network_user_id`,`object_type_id`,`object_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `tribe` (
  `id` bigint unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(2048) NOT NULL DEFAULT '',
  `badge` varchar(2048) NOT NULL DEFAULT '',
  `slogan` varchar(2048) NOT NULL DEFAULT '',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `item_of_day` (
  `id` bigint unsigned NOT NULL,
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `tribe_id` bigint,
  `date` date,
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
   `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (tribe_id,date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



CREATE TABLE `user_action` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `other_user_network_id` bigint unsigned,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`network_user_id`, `object_id` , `action_type_id` , `object_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `user_action_type` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

insert into user_action_type (id, name) values (1, 'like');
insert into user_action_type (id, name) values (2, 'post');

CREATE TABLE `user_tribe` (
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id` bigint unsigned NOT NULL DEFAULT '0',
  `tribe_id` bigint,
   `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
   `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (network_user_id,tribe_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `tribe_category` (
  `tribe_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `category_id` bigint(20) unsigned NOT NULL,
  `affinity` decimal(50,4) unsigned NOT NULL DEFAULT '0.0000',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tribe_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `user_user` (
  `hash_user_id_from` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hash_user_id_to` bigint(20) unsigned NOT NULL DEFAULT '0',
  `affinity` decimal(50,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT 'new affinity based on affinity from CP refined with profile categories',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hash_user_id_from`,`hash_user_id_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `bookmark` (
  `network_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_user_id` bigint NOT NULL DEFAULT '0',
  `object_type_id` bigint unsigned NOT NULL DEFAULT '0',
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`network_id`,`network_user_id`,`object_type_id`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



