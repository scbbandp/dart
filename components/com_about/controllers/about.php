<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_About
 * @author     Simon Cruise <simon.cruise@hotmail.co.uk>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * About list controller class.
 *
 * @since  1.6
 */
class AboutControllerAbout extends AboutController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'About', $prefix = 'AboutModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
