<?php
/**
* @package    Bbandp.Module
* @subpackage mod_hero
* @license    GNU/GPL, see LICENSE.php
* @link       http://bbandp.com
* 
*/

// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$heros = ModHeroHelper::getItems($params);
require JModuleHelper::getLayoutPath('mod_hero');