<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_About
 * @author     Simon Cruise <simon.cruise@hotmail.co.uk>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('About', JPATH_COMPONENT);
JLoader::register('AboutController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('About');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
