CREATE TABLE IF NOT EXISTS `#__page_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`intro` TEXT NOT NULL ,
`intro_image` VARCHAR(255)  NOT NULL ,
`content` TEXT NOT NULL ,
`meta_title` VARCHAR(255)  NOT NULL ,
`meta_desc` VARCHAR(255)  NOT NULL ,
`meta_key` TEXT NOT NULL ,
`alias` VARCHAR(255) COLLATE utf8_bin NOT NULL ,
PRIMARY KEY (`id`)
);

