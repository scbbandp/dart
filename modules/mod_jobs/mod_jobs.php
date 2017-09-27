<?php
/**
* @package    Bbandp.Module
* @subpackage mod_jobs
* @license    GNU/GPL, see LICENSE.php
* @link       http://bbandp.com
* 
*/

// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$cache = JFactory::getCache();

/*
$activeJobs = ModJobsHelper::getActiveCapitalJobs();
$dartJobs = ModJobsHelper::getDartJobs();
*/

$activeJobs = $cache->call( array( 'ModJobsHelper', 'getActiveCapitalJobs' ) );
$dartJobs = $cache->call( array( 'ModJobsHelper', 'getDartJobs' ) );

require JModuleHelper::getLayoutPath('mod_jobs');