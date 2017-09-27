<?php
/**
 * @package         Asikart.Module
 * @subpackage      mod_info
 * @copyright       Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

abstract class modInfoHelper
{
    public static function getItems(&$params)
    {
        // init db
        // ===========================================================================
        $db     = JFactory::getDbo();
        $q      = $db->getQuery(true) ;
        
        
        // get Joomla! API
        // ===========================================================================
        $app     = JFactory::getApplication() ;
        $user    = JFactory::getUser() ;
        $date    = JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
        $uri     = JFactory::getURI() ;
        $doc     = JFactory::getDocument();
        
        
        
        // get Params and prepare data.
        // ===========================================================================
        $catid         = $params->get('catid', 1) ;
        $order         = $params->get('orderby', 'a.created') ;
        $dir           = $params->get('order_dir', 'DESC') ;
        
        
        
        // Category
        // =====================================================================================
        // if Choose all category, select ROOT category.
        if(!in_array(1, $catid)) {
            // if is array, implode it.
            if(is_array($catid)) $catid = implode(',', $catid) ;
            
            $q->where("a.catid IN ({$catid})") ;
        }
        
        
        
        // Published
        // =====================================================================================
        $q->where('a.published > 0') ;
        
        $nullDate = $db->Quote($db->getNullDate());
        $nowDate = $db->Quote($date->toSql(true));

        $q->where('(a.publish_up   = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
        $q->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
        
        
        
        // View Level
        // =====================================================================================
        $groups    = implode(',', $user->getAuthorisedViewLevels());
        $q->where('a.access IN ('.$groups.')');
        
        
        
        
        // Load Data
        // ===========================================================================
        $items = array() ;
        
        $q->select("a.*")->from('#__companies_ AS a');
        
        $db->setQuery($q);
        $items = $db->loadObjectList();
        
        
        
        // Handle Data
        // ===========================================================================
        if( $items ):
        
            
       endif;
        
        
        return $items ;
    }
}
