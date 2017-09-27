<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Leadership
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Leadership', JPATH_COMPONENT);
JLoader::register('LeadershipController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Leadership');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
