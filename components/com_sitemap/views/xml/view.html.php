<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sitemap
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!class_exists('ContentHelperRoute')) require_once (JPATH_SITE . '/components/com_content/helpers/route.php'); 

/**
 * HTML View class for the Sitemap Component
 *
 * @since  0.0.1
 */
class SitemapViewXml extends JViewLegacy
{
	/**
	 * Display the Sitemap view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		
		$db = JFactory::getDbo();
		
		$menu = JFactory::getApplication()->getMenu();
		$items = $menu->getMenu();
		
		header("Content-type: text/xml; charset=utf-8");
		
		echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; 
		foreach($items as $item) {
			echo "<url><loc>" . JURI::base() . $item->route . "</loc></url>";
		}
		
		/*
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__content');
		$query->where('state=1')
		->where('(catid="26" OR catid="27" OR catid="30")');
		$query->order('created DESC');
		$db->setQuery((string)$query);
		$res = $db->loadObjectList();
		
		foreach($res as $r){
			
			$url = JRoute::_(ContentHelperRoute::getArticleRoute($r->id, $r->catid));
			//$url = "news/{$r->id}-{$r->alias}"; 
			echo "<url><loc>" . JURI::base() . $url . "</loc></url>";
			
		}*/
		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__leadership_');
		$query->where('state="1"');
		$db->setQuery((string)$query);
		
		$res = $db->loadObjectList();
		
		foreach($res as $item){
			//$url = JRoute::_('index.php?option=com_leadership&view=member&id='.(int) $item->id);
			$url = "about-us/leadership/member/{$item->alias}";
			echo "<url><loc>" . JURI::base() . ltrim($url, '/') . "</loc></url>";
			
		}
		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__people_');
		$query->where('state="1"');
		$db->setQuery((string)$query);
		
		$res = $db->loadObjectList();
		
		foreach($res as $item){
			
			$url = JRoute::_('index.php?option=com_people&view=person&id='.(int) $item->id);
			echo "<url><loc>" . JURI::base() . ltrim($url, '/') . "</loc></url>";
			
		}
		
		echo '</urlset>';
		exit;
		
		// Display the view
		//parent::display($tpl);
	}
}