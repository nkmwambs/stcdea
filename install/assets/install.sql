-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `access_id` int(100) NOT NULL AUTO_INCREMENT,
  `entitlement_id` int(100) NOT NULL,
  `profile_id` int(10) NOT NULL,
  PRIMARY KEY (`access_id`),
  KEY `privilege_id` (`entitlement_id`),
  CONSTRAINT `access_ibfk_1` FOREIGN KEY (`entitlement_id`) REFERENCES `entitlement` (`entitlement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `access` (`access_id`, `entitlement_id`, `profile_id`) VALUES
(0,	59,	7),
(2,	26,	7),
(3,	1,	7),
(4,	2,	7),
(5,	3,	7),
(6,	4,	7),
(7,	5,	7),
(10,	8,	7),
(11,	9,	7),
(12,	10,	7),
(13,	12,	7),
(14,	13,	7),
(15,	14,	7),
(16,	15,	7),
(17,	16,	7),
(18,	17,	7),
(19,	18,	7),
(20,	19,	7),
(21,	20,	7),
(22,	23,	7),
(23,	24,	7),
(24,	25,	7),
(25,	27,	7),
(26,	28,	7),
(27,	29,	7),
(28,	30,	7),
(29,	31,	7),
(30,	32,	7),
(31,	33,	7),
(32,	34,	7),
(33,	35,	7),
(34,	36,	7),
(35,	37,	7),
(36,	38,	7),
(37,	39,	7),
(38,	40,	7),
(39,	41,	7),
(40,	42,	7),
(41,	43,	7),
(42,	44,	7),
(43,	45,	7),
(44,	46,	7),
(45,	47,	7),
(46,	48,	7),
(47,	49,	7),
(48,	50,	7),
(49,	51,	7),
(50,	52,	7),
(51,	53,	7),
(52,	54,	7),
(53,	6,	7),
(54,	55,	7),
(55,	56,	7),
(56,	57,	7),
(57,	62,	7),
(58,	7,	7),
(59,	58,	7),
(60,	60,	7),
(61,	21,	7),
(647,	63,	7),
(649,	61,	7),
(651,	64,	7);

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `grouping_id` int(100) NOT NULL,
  `visibility` int(100) NOT NULL COMMENT '1=all countries, country_ids',
  `assignment` varchar(20) NOT NULL COMMENT '2=>manager,1=>peer',
  `unit` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0=inactive,1=active',
  `created_date` datetime NOT NULL,
  `created_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_modified_by` int(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `contribution`;
CREATE TABLE `contribution` (
  `contribution_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`contribution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `contribution` (`contribution_id`, `name`) VALUES
(1,	'staff'),
(2,	'manager');

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `country_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(100) NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `country` (`country_id`, `name`, `created_date`, `created_by`, `last_modified_by`, `last_modified_date`) VALUES
(1,	'All',	'0000-00-00 00:00:00',	0,	0,	'2018-05-31 13:38:14'),
(25,	'Kenya',	'0000-00-00 00:00:00',	0,	0,	'2018-05-31 13:38:14');

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `department_id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_by` int(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `department` (`department_id`, `name`, `created_by`, `created_date`, `last_modified_by`, `last_modified_date`) VALUES
(1,	'Program Support',	0,	'0000-00-00 00:00:00',	0,	'2018-05-31 14:12:40');

DROP TABLE IF EXISTS `entitlement`;
CREATE TABLE `entitlement` (
  `entitlement_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `derivative_id` int(10) NOT NULL,
  PRIMARY KEY (`entitlement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `entitlement` (`entitlement_id`, `name`, `derivative_id`) VALUES
(1,	'switch_user',	0),
(2,	'add_user',	20),
(3,	'delete_user',	20),
(4,	'edit_user',	20),
(5,	'manage_language',	7),
(6,	'translate_language',	5),
(7,	'manage_settings',	0),
(8,	'system_settings',	7),
(9,	'sms_settings',	7),
(10,	'manage_surveys',	0),
(12,	'survey_results',	10),
(13,	'manage_setup_parameters',	19),
(14,	'setup_countries',	13),
(15,	'setup_departments',	13),
(16,	'setup_teams',	13),
(17,	'setup_roles',	13),
(18,	'setup_profiles',	13),
(19,	'manage_users',	0),
(20,	'manage_accounts',	19),
(21,	'self_assign_privilege',	0),
(23,	'change_scope',	20),
(24,	'suspend_user',	20),
(25,	'add_language',	5),
(26,	'self_update',	20),
(27,	'manage_grouping',	10),
(28,	'add_grouping',	27),
(29,	'edit_grouping',	27),
(30,	'delete_grouping',	27),
(31,	'manage_category',	10),
(32,	'add_category',	31),
(33,	'edit_category',	31),
(34,	'delete_category',	31),
(35,	'survey_settings',	10),
(36,	'add_survey',	35),
(37,	'edit_survey',	35),
(38,	'delete_survey',	35),
(39,	'add_country',	14),
(40,	'edit_country',	14),
(41,	'delete_country',	14),
(42,	'add_department',	15),
(43,	'edit_department',	15),
(44,	'delete_department',	15),
(45,	'add_team',	16),
(46,	'edit_team',	16),
(47,	'delete_team',	16),
(48,	'add_role',	17),
(49,	'edit_role',	17),
(50,	'delete_role',	17),
(51,	'mail_templates',	10),
(52,	'messages',	0),
(53,	'add_profile',	18),
(54,	'edit_profile',	18),
(55,	'add_vote',	10),
(56,	'edit_vote',	10),
(57,	'delete_vote',	10),
(58,	'delete_profile',	18),
(59,	'dashboard',	0),
(60,	'active_survey',	59),
(61,	'registered_users',	59),
(62,	'cast_votes',	59),
(63,	'voting_days',	59),
(64,	'uncast_votes',	59);

DROP TABLE IF EXISTS `grouping`;
CREATE TABLE `grouping` (
  `grouping_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(100) NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  PRIMARY KEY (`grouping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `phrase_id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` longtext COLLATE utf8_unicode_ci NOT NULL,
  `english` longtext COLLATE utf8_unicode_ci NOT NULL,
  `spanish` longtext COLLATE utf8_unicode_ci NOT NULL,
  `french` longtext COLLATE utf8_unicode_ci NOT NULL,
  `swahili` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`phrase_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `language` (`phrase_id`, `phrase`, `english`, `spanish`, `french`, `swahili`) VALUES
(1,	'login',	'',	'',	'',	''),
(2,	'forgot_your_password',	'',	'',	'',	''),
(3,	'staff_dashboard',	'',	'',	'',	''),
(4,	'dashboard',	'',	'',	'',	'Deshibodi'),
(5,	'account',	'',	'',	'',	''),
(6,	'edit_profile',	'',	'',	'',	''),
(7,	'change_password',	'',	'',	'',	''),
(8,	'event_schedule',	'',	'',	'',	''),
(9,	'users',	'',	'',	'',	''),
(10,	'delete',	'',	'',	'',	''),
(11,	'cancel',	'',	'',	'',	''),
(12,	'Ok',	'',	'',	'',	''),
(13,	'language',	'',	'',	'',	''),
(14,	'administrator',	'',	'',	'',	''),
(15,	'switch_user',	'',	'',	'',	''),
(16,	'reset_password',	'',	'',	'',	''),
(17,	'return_to_login_page',	'',	'',	'',	''),
(18,	'manager_dashboard',	'',	'',	'',	''),
(19,	'manage_profile',	'',	'',	'',	''),
(20,	'firstname',	'',	'',	'',	''),
(21,	'lastname',	'',	'',	'',	''),
(22,	'email',	'',	'',	'',	''),
(23,	'photo',	'',	'',	'',	''),
(24,	'update_profile',	'',	'',	'',	''),
(25,	'current_password',	'',	'',	'',	''),
(26,	'new_password',	'',	'',	'',	''),
(27,	'confirm_new_password',	'',	'',	'',	''),
(28,	'display_settings',	'',	'',	'',	''),
(29,	'general_settings',	'',	'',	'',	''),
(30,	'sms_settings',	'',	'',	'',	''),
(31,	'language_settings',	'',	'',	'',	''),
(32,	'previledges',	'',	'',	'',	''),
(33,	'accounts_setup',	'',	'',	'',	''),
(34,	'settings',	'',	'',	'',	''),
(35,	'manage_language',	'',	'',	'',	''),
(36,	'messages',	'',	'',	'',	''),
(37,	'new_message',	'',	'',	'',	''),
(38,	'setup',	'',	'',	'',	''),
(39,	'countries',	'',	'',	'',	''),
(40,	'roles',	'',	'',	'',	''),
(41,	'departments',	'',	'',	'',	''),
(42,	'teams',	'',	'',	'',	''),
(43,	'profiles',	'',	'',	'',	''),
(44,	'add_country',	'',	'',	'',	''),
(45,	'surveys',	'',	'',	'',	''),
(46,	'manage_surveys',	'',	'',	'',	''),
(47,	'nominate',	'',	'',	'',	''),
(48,	'survey_results',	'',	'',	'',	''),
(49,	'name',	'',	'',	'',	''),
(50,	'staff_count',	'',	'',	'',	''),
(51,	'manager_count',	'',	'',	'',	''),
(52,	'action',	'',	'',	'',	''),
(53,	'country',	'',	'',	'',	''),
(54,	'save',	'',	'',	'',	''),
(55,	'success',	'',	'',	'',	''),
(56,	'failed',	'',	'',	'',	''),
(57,	'edit',	'',	'',	'',	''),
(58,	'edit_country',	'',	'',	'',	''),
(59,	'message',	'',	'',	'',	''),
(60,	'add_department',	'',	'',	'',	''),
(61,	'department',	'',	'',	'',	''),
(62,	'edit_department',	'',	'',	'',	''),
(63,	'add_team',	'',	'',	'',	''),
(64,	'description',	'',	'',	'',	''),
(65,	'team',	'',	'',	'',	''),
(66,	'select',	'',	'',	'',	''),
(67,	'team_title',	'',	'',	'',	''),
(68,	'add_role',	'',	'',	'',	''),
(69,	'contribution',	'',	'',	'',	''),
(70,	'role',	'',	'',	'',	''),
(71,	'role_title',	'',	'',	'',	''),
(72,	'staff',	'',	'',	'',	''),
(73,	'manager',	'',	'',	'',	''),
(74,	'add_profile',	'',	'',	'',	''),
(75,	'profile_name',	'',	'',	'',	''),
(76,	'profile_title',	'',	'',	'',	''),
(77,	'assignment',	'',	'',	'',	''),
(78,	'assign_privileges',	'',	'',	'',	''),
(79,	'assign_previledges',	'',	'',	'',	''),
(80,	'add_user',	'',	'',	'',	''),
(81,	'delete_user',	'',	'',	'',	''),
(82,	'update_user',	'',	'',	'',	''),
(83,	'translate_language',	'',	'',	'',	''),
(84,	'manage_settings',	'',	'',	'',	''),
(85,	'system_settings',	'',	'',	'',	''),
(86,	'Are_you_sure_you_want_to_perform_this_action?',	'',	'',	'',	''),
(87,	'process_aborted',	'',	'',	'',	''),
(88,	'please_wait_until_you_receive_confirmation',	'',	'',	'',	''),
(89,	'edit_role',	'',	'',	'',	''),
(90,	'title',	'',	'',	'',	''),
(91,	'manage_users',	'',	'',	'',	''),
(92,	'cash_journal',	'',	'',	'',	''),
(93,	'budget',	'',	'',	'',	''),
(94,	'budget_limits',	'',	'',	'',	''),
(95,	'budget_summary',	'',	'',	'',	''),
(96,	'budget_schedules',	'',	'',	'',	''),
(97,	'complete_budget',	'',	'',	'',	''),
(98,	'C.I.Vs',	'',	'',	'',	''),
(99,	'accounts_chart',	'',	'',	'',	''),
(100,	'manage_setup_parameters',	'',	'',	'',	''),
(101,	'setup_countries',	'',	'',	'',	''),
(102,	'setup_departments',	'',	'',	'',	''),
(103,	'setup_teams',	'',	'',	'',	''),
(104,	'setup_roles',	'',	'',	'',	''),
(105,	'setup_profiles',	'',	'',	'',	''),
(106,	'manage_accounts',	'',	'',	'',	''),
(107,	'first_name',	'',	'',	'',	''),
(108,	'last_name',	'',	'',	'',	''),
(109,	'status',	'',	'',	'',	''),
(110,	'suspend',	'',	'',	'',	''),
(111,	'active',	'',	'',	'',	''),
(112,	'self_assign_privilege',	'',	'',	'',	''),
(113,	'system_name',	'',	'',	'',	''),
(114,	'system_title',	'',	'',	'',	''),
(115,	'address',	'',	'',	'',	''),
(116,	'phone',	'',	'',	'',	''),
(117,	'paypal_email',	'',	'',	'',	''),
(118,	'currency',	'',	'',	'',	''),
(119,	'system_email',	'',	'',	'',	''),
(120,	'text_align',	'',	'',	'',	''),
(121,	'update_product',	'',	'',	'',	''),
(122,	'file',	'',	'',	'',	''),
(123,	'install_update',	'',	'',	'',	''),
(124,	'theme_settings',	'',	'',	'',	''),
(125,	'default',	'',	'',	'',	''),
(126,	'select_theme',	'',	'',	'',	''),
(127,	'select_a_theme_to_make_changes',	'',	'',	'',	''),
(128,	'upload_logo',	'',	'',	'',	''),
(129,	'upload',	'',	'',	'',	''),
(130,	'profile',	'',	'',	'',	''),
(131,	'select_a_service',	'',	'',	'',	''),
(132,	'not_selected',	'',	'',	'',	''),
(133,	'disabled',	'',	'',	'',	''),
(134,	'clickatell_username',	'',	'',	'',	''),
(135,	'clickatell_password',	'',	'',	'',	''),
(136,	'clickatell_api_id',	'',	'',	'',	''),
(137,	'twilio_account',	'',	'',	'',	''),
(138,	'authentication_token',	'',	'',	'',	''),
(139,	'registered_phone_number',	'',	'',	'',	''),
(140,	'language_list',	'',	'',	'',	''),
(141,	'add_phrase',	'',	'',	'',	''),
(142,	'add_language',	'',	'',	'',	''),
(143,	'option',	'',	'',	'',	''),
(144,	'edit_phrase',	'',	'',	'',	''),
(145,	'delete_language',	'',	'',	'',	''),
(146,	'phrase',	'',	'',	'',	''),
(147,	'value_required',	'',	'',	'',	''),
(148,	'update_phrase',	'',	'',	'',	''),
(149,	'theme_selected',	'',	'',	'',	''),
(150,	'private_messaging',	'',	'',	'',	NULL),
(151,	'write_new_message',	'',	'',	'',	NULL),
(152,	'recipient',	'',	'',	'',	NULL),
(153,	'select_a_user',	'',	'',	'',	NULL),
(154,	'student',	'',	'',	'',	NULL),
(155,	'gender',	'',	'',	'',	NULL),
(156,	'male',	'',	'',	'',	NULL),
(157,	'female',	'',	'',	'',	NULL),
(158,	'254711808075',	'',	'',	'',	NULL),
(159,	'user_created_successfully',	'',	'',	'',	NULL),
(160,	'change_scope',	'',	'',	'',	NULL),
(161,	'edit_user',	'',	'',	'',	NULL),
(162,	'user_deleted',	'',	'',	'',	NULL),
(163,	'suspended',	'',	'',	'',	NULL),
(164,	'view',	'',	'',	'',	NULL),
(165,	'Kenya',	'',	'',	'',	NULL),
(166,	'Uganda',	'',	'',	'',	NULL),
(167,	'Rwanda',	'',	'',	'',	NULL),
(168,	'Tanzania',	'',	'',	'',	NULL),
(169,	'Togo',	'',	'',	'',	NULL),
(170,	'Ethiopia',	'',	'',	'',	NULL),
(171,	'Ghana',	'',	'',	'',	NULL),
(172,	'Burkina Faso',	'',	'',	'',	NULL),
(173,	'scope',	'',	'',	'',	NULL),
(174,	'one_way',	'',	'',	'',	NULL),
(175,	'strict',	'',	'',	'',	NULL),
(176,	'no',	'',	'',	'',	NULL),
(177,	'yes',	'',	'',	'',	NULL),
(178,	'two_way',	'',	'',	'',	NULL),
(179,	'full_name',	'',	'',	'',	NULL),
(180,	'suspend_user',	'',	'',	'',	NULL),
(181,	'type',	'',	'',	'',	NULL),
(182,	'voting',	'',	'',	'',	NULL),
(183,	'administration',	'',	'',	'',	NULL),
(184,	'both',	'',	'',	'',	NULL),
(185,	'category_groups',	'',	'',	'',	NULL),
(186,	'categories',	'',	'',	'',	NULL),
(187,	'survey_setting',	'',	'',	'',	NULL),
(188,	'mail_templates',	'',	'',	'',	NULL),
(189,	'grouping',	'',	'',	'',	NULL),
(190,	'add_grouping',	'',	'',	'',	NULL),
(191,	'inactive',	'',	'',	'',	NULL),
(192,	'edit_grouping',	'',	'',	'',	NULL),
(193,	'self_update',	'',	'',	'',	NULL),
(194,	'manage_grouping',	'',	'',	'',	NULL),
(195,	'delete_grouping',	'',	'',	'',	NULL),
(196,	'category_group',	'',	'',	'',	NULL),
(197,	'created_by',	'',	'',	'',	NULL),
(198,	'created_date',	'',	'',	'',	NULL),
(199,	'last_modified',	'',	'',	'',	NULL),
(200,	'last_modified_by',	'',	'',	'',	NULL),
(201,	'add_categories',	'',	'',	'',	NULL),
(202,	'visibility',	'',	'',	'',	NULL),
(203,	'user_type_assignment',	'',	'',	'',	NULL),
(204,	'peer',	'',	'',	'',	NULL),
(205,	'country_visibility',	'',	'',	'',	NULL),
(206,	'all_countries',	'',	'',	'',	NULL),
(207,	'category',	'',	'',	'',	NULL),
(208,	'manage_category',	'',	'',	'',	NULL),
(209,	'add_category',	'',	'',	'',	NULL),
(210,	'edit_category',	'',	'',	'',	NULL),
(211,	'delete_category',	'',	'',	'',	NULL),
(212,	'category_grouping',	'',	'',	'',	NULL),
(213,	'survey',	'',	'',	'',	NULL),
(214,	'assigned_user_type',	'',	'',	'',	NULL),
(215,	'user',	'',	'',	'',	NULL),
(216,	'nominate_unit',	'',	'',	'',	NULL),
(217,	'submit',	'',	'',	'',	NULL),
(218,	'unit',	'',	'',	'',	NULL),
(219,	'your_voting_privileges',	'',	'',	'',	NULL),
(220,	'position',	'',	'',	'',	NULL),
(221,	'your_country',	'',	'',	'',	NULL),
(222,	'All',	'',	'',	'',	NULL),
(223,	'start_voting',	'',	'',	'',	NULL),
(226,	'there_is_no_active_survey',	'',	'',	'',	NULL),
(225,	'you_have_already_participated_in_voting',	'',	'',	'',	NULL),
(227,	'nominate_team',	'',	'',	'',	NULL),
(228,	'nominate_user',	'',	'',	'',	NULL),
(229,	'nominate_country',	'',	'',	'',	NULL),
(230,	'nominate_department',	'',	'',	'',	NULL),
(231,	'nominate_',	'',	'',	'',	NULL),
(232,	'survey_settings',	'',	'',	'',	NULL),
(233,	'add_survey',	'',	'',	'',	NULL),
(234,	'edit_survey',	'',	'',	'',	NULL),
(235,	'delete_survey',	'',	'',	'',	NULL),
(236,	'results',	'',	'',	'',	NULL),
(237,	'voting_staff',	'',	'',	'',	NULL),
(238,	'vote_status',	'',	'',	'',	NULL),
(239,	'voting_start_date',	'',	'',	'',	NULL),
(240,	'voting_end_date',	'',	'',	'',	NULL),
(241,	'nomination_type',	'',	'',	'',	NULL),
(242,	'voting_unit',	'',	'',	'',	NULL),
(243,	'nominee',	'',	'',	'',	NULL),
(244,	'staff_number',	'',	'',	'',	NULL),
(245,	'completed',	'',	'',	'',	NULL),
(246,	'in_progress',	'',	'',	'',	NULL),
(247,	'no_data_found',	'',	'',	'',	NULL),
(250,	'employee_numner',	'',	'',	'',	NULL),
(249,	'employee_number',	'',	'',	'',	NULL),
(251,	'delete_country',	'',	'',	'',	NULL),
(252,	'add_departments',	'',	'',	'',	NULL),
(253,	'edit_departments',	'',	'',	'',	NULL),
(254,	'delete_departments',	'',	'',	'',	NULL),
(255,	'delete_department',	'',	'',	'',	NULL),
(256,	'edit_team',	'',	'',	'',	NULL),
(257,	'delete_team',	'',	'',	'',	NULL),
(258,	'delete_role',	'',	'',	'',	NULL),
(259,	'comment_here',	'',	'',	'',	NULL),
(260,	'comment',	'',	'',	'',	NULL),
(261,	'you_have_missing_fields',	'',	'',	'',	NULL),
(262,	'submit_successful',	'',	'',	'',	NULL),
(263,	'edit_entitlement',	'',	'',	'',	NULL),
(264,	'votes',	'',	'',	'',	NULL),
(265,	'survey_start_date',	'',	'',	'',	NULL),
(266,	'voter_last_name',	'',	'',	'',	NULL),
(267,	'add_vote',	'',	'',	'',	NULL),
(268,	'edit_vote',	'',	'',	'',	NULL),
(269,	'delete_vote',	'',	'',	'',	NULL),
(270,	'start_date',	'',	'',	'',	NULL),
(271,	'voter_staff_number',	'',	'',	'',	NULL),
(272,	'privileges',	'',	'',	'',	NULL),
(273,	'active_survey',	'',	'',	'',	NULL),
(274,	'so_far_in_our_application',	'',	'',	'',	NULL),
(275,	'active_users',	'',	'',	'',	NULL),
(276,	'so_far_to_close_voting',	'',	'',	'',	NULL);

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_thread_code` longtext NOT NULL,
  `message` longtext NOT NULL,
  `sender` longtext NOT NULL,
  `timestamp` longtext NOT NULL,
  `read_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 unread 1 read',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `message_thread`;
CREATE TABLE `message_thread` (
  `message_thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_thread_code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `sender` longtext COLLATE utf8_unicode_ci NOT NULL,
  `reciever` longtext COLLATE utf8_unicode_ci NOT NULL,
  `last_message_timestamp` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`message_thread_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `noticeboard`;
CREATE TABLE `noticeboard` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `notice` longtext COLLATE utf8_unicode_ci NOT NULL,
  `create_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `profile_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `profile` (`profile_id`, `name`, `description`) VALUES
(7,	'Super Admin',	'<p>\r\n	Has all privileges</p>\r\n');

DROP TABLE IF EXISTS `result`;
CREATE TABLE `result` (
  `result_id` int(100) NOT NULL AUTO_INCREMENT,
  `survey_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-not submitted,1-submitted',
  `created_date` datetime NOT NULL,
  `created_by` int(100) NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `contribution` tinyint(4) NOT NULL,
  `department_id` int(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(100) NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `role` (`role_id`, `name`, `contribution`, `department_id`, `created_date`, `created_by`, `last_modified_by`, `last_modified_date`) VALUES
(1,	'Program Trainer',	1,	1,	'0000-00-00 00:00:00',	0,	0,	'2018-05-31 14:27:45');

DROP TABLE IF EXISTS `scope`;
CREATE TABLE `scope` (
  `scope_id` int(100) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `two_way` tinyint(4) NOT NULL COMMENT '1=yes (Can nominate and be nominated by other FOs),0=no (User can only nominate other FOs)',
  `strict` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=all departments,1=only user department',
  `type` varchar(10) NOT NULL COMMENT 'admin,vote,both',
  PRIMARY KEY (`scope_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `scope` (`scope_id`, `user_id`, `two_way`, `strict`, `type`) VALUES
(1,	1,	1,	0,	'both');

DROP TABLE IF EXISTS `scope_country`;
CREATE TABLE `scope_country` (
  `scope_country_id` int(100) NOT NULL AUTO_INCREMENT,
  `scope_id` tinyint(4) NOT NULL,
  `country_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`scope_country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `scope_country` (`scope_country_id`, `scope_id`, `country_id`) VALUES
(118,	1,	28);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES
(1,	'system_name',	'Staff Recognition System'),
(2,	'system_title',	'Staff Recognition'),
(3,	'address',	'1945 Nairobi'),
(4,	'phone',	'254711808071'),
(7,	'system_email',	'NKarisa@ke.ci.org'),
(8,	'active_sms_service',	'disabled'),
(9,	'language',	'english'),
(10,	'text_align',	'left-to-right'),
(11,	'clickatell_user',	''),
(12,	'clickatell_password',	''),
(13,	'clickatell_api_id',	''),
(14,	'skin_colour',	'default'),
(15,	'twilio_account_sid',	''),
(16,	'twilio_auth_token',	''),
(17,	'twilio_sender_phone_number',	'');

DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey` (
  `survey_id` int(100) NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `country_id` int(100) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL,
  `created_by` int(100) NOT NULL,
  `created_date` date NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tabulate`;
CREATE TABLE `tabulate` (
  `tabulate_id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(100) NOT NULL,
  `category_id` int(100) NOT NULL,
  `nominated_unit` tinyint(4) NOT NULL,
  `nominee_id` int(100) NOT NULL,
  `comment` longtext NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(100) NOT NULL,
  `last_modified_by` int(100) NOT NULL,
  `last_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tabulate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `team_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country_id` int(100) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `teamset`;
CREATE TABLE `teamset` (
  `teamset_id` int(100) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL,
  `team_id` int(50) NOT NULL,
  PRIMARY KEY (`teamset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `template_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `template_trigger` varchar(50) NOT NULL,
  `mail_tags` mediumtext NOT NULL,
  `template_subject` varchar(100) NOT NULL,
  `template_body` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`template_id`, `name`, `template_trigger`, `mail_tags`, `template_subject`, `template_body`) VALUES
(1, 'User Registration', 'user_invite', '{user} = User\'s First and Last Name,\r\n{system_name} = System Name,\r\n{user_email} = User\'s Email,\r\n{user_password} = User\'s Password,\r\n{user_role} = User\'s role,\r\n{user_profile} = User\'s Profile,\r\n{site_url} = Site URL,\r\n{system_admin_email} = Country Admin Email\r\n\r\n ', 'Welcome {user}', '<p>\n	Dear {user}</p>\n<p>\n	Your account has successfully been created in the {system_name}. Below are your new account details:</p>\n<p>\n	Login Email: {user_email}</p>\n<p>\n	Password: {user_password}</p>\n<p>\n	Your Role: {user_role}</p>\n<p>\n	Profile: {user_profile}</p>\n<p>\n	You can proceed logging in at {site_url}. For information contact the administrator at {system_admin_email}&nbsp;or your HRBP in your country.</p>\n<p>\n	&nbsp;</p>\n<p>\n	Regards,</p>\n<p>\n	System Administrator&nbsp; &nbsp;</p>\n');


DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `unit` (`unit_id`, `name`) VALUES
(1,	'country'),
(2,	'department'),
(3,	'team'),
(4,	'user');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(100) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` tinyint(10) NOT NULL,
  `profile_id` tinyint(5) NOT NULL,
  `manager_id` int(100) NOT NULL,
  `auth` tinyint(5) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `email`, `password`, `gender`, `phone`, `employee_id`, `role_id`, `profile_id`, `manager_id`, `auth`, `country_id`) VALUES
(1,	'Super Admin',	'Administrator',	'admin@localhost.com',	'956d2d5467ed373328ee2d165a51c399',	'male',	'254711808071',	'KE136',	1,	7,	2,	1,	25);

-- 2018-06-06 16:39:02