CREATE TABLE IF NOT EXISTS `#__home_`(
  `id` int(11) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_main_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_main_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_main_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_first_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_first_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_first_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_second_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_second_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_second_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_third_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_third_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discover_third_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `careers_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `careers_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `companies_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `companies_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_first_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_first_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_first_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_first_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_second_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_second_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_second_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_second_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
