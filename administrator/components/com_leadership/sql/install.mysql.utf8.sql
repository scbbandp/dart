CREATE TABLE IF NOT EXISTS `#__leadership_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`company` VARCHAR(255)  NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`tel` VARCHAR(255)  NOT NULL ,
`bio` TEXT NOT NULL ,
`highlights` TEXT NOT NULL ,
`community` TEXT NOT NULL ,
`tile` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255) COLLATE utf8_bin NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

