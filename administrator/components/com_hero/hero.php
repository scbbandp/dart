<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Hero
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_hero'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Hero', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('HeroHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'hero.php');

$controller = JControllerLegacy::getInstance('Hero');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
