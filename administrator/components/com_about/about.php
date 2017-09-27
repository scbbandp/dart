<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_About
 * @author     Simon Cruise <simon.cruise@hotmail.co.uk>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_about'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('About', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('AboutHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'about.php');

$controller = JControllerLegacy::getInstance('About');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
