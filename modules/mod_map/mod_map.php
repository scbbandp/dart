<?php
/**
 * @package         Bbandp.Module
 * @subpackage      mod_test
 * @copyright       Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// $params
$moduleclass_sfx    = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_map', $params->get('layout', 'default'));

