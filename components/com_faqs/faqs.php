<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Faqs
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Faqs', JPATH_COMPONENT);
JLoader::register('FaqsController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Faqs');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
