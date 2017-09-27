CREATE TABLE IF NOT EXISTS `#__people_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`job` VARCHAR(255)  NOT NULL ,
`company` VARCHAR(255)  NOT NULL ,
`since` VARCHAR(255)  NOT NULL ,
`specialty` VARCHAR(255)  NOT NULL ,
`about` TEXT NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`mobile` VARCHAR(255)  NOT NULL ,
`hero` VARCHAR(255)  NOT NULL ,
`page_title` VARCHAR(255)  NOT NULL ,
`meta_desc` TEXT NOT NULL ,
`alias` VARCHAR(255) COLLATE utf8_bin NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

