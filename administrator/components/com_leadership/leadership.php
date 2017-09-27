<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Leadership
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_leadership'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Leadership', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('LeadershipHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'leadership.php');

$controller = JControllerLegacy::getInstance('Leadership');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
