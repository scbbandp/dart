CREATE TABLE IF NOT EXISTS `#__companies_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
`logo` VARCHAR(255)  NOT NULL ,
`category` INT(11)  NOT NULL ,
`link` VARCHAR(255)  NOT NULL ,
`address` TEXT NOT NULL ,
`alias` VARCHAR(255) COLLATE utf8_bin NOT NULL ,
`tel` VARCHAR(255)  NOT NULL ,
`hours` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=armscii8_bin;

