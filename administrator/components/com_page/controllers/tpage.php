<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Page
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Tpage controller class.
 *
 * @since  1.6
 */
class PageControllerTpage extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'tpages';
		parent::__construct();
	}
}
