<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Bbandp.Module
 * @subpackage mod_hero
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModHeroHelper
{
     public static function getItems(&$params)
    {
        // init db
        // ===========================================================================
        $db     = JFactory::getDbo();
        $q      = $db->getQuery(true) ;
        
        
        // get Joomla! API
        // ===========================================================================
		/*
        $app     = JFactory::getApplication() ;
        $user    = JFactory::getUser() ;
        $date    = JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
        $uri     = JFactory::getURI() ;
        $doc     = JFactory::getDocument();
        */
        
        
        // Load Data
        // ===========================================================================
        $items = array() ;
        
        $q->select("a.*")
            ->from('#__hero AS a')
            ->order("ordering ASC")
            ;
        $q->setLimit(3);
        $db->setQuery($q);
        
        $items = $db->loadObjectList();
        
        return $items;
    }
}