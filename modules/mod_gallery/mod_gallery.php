<?php
/**
 * @package         Bbandp.Module
 * @subpackage      mod_test
 * @copyright       Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;


//require JModuleHelper::getLayoutPath('mod_test', $params->get('layout', 'default'));

$id = 1;
if (bagalleryHelper::checkGallery($id)) {
	
	$doc = JFactory::getDocument();
	$gallery = bagalleryHelper::drawHTMLPage($id);
	
	$gallery = '<div class="content-block gallery"><h2>Media Gallery</h2>' . $gallery . '</div>';
	$url = JURI::root() . 'components/com_bagallery/assets/js/ba-gallery.js';

	$gallery = drawScripts($id).$gallery;
	echo $gallery;
}

 function drawScripts($id)
{
	$doc = JFactory::getDocument();
	$scripts = $doc->_scripts;
	$array = array();
	$about = bagalleryHelper::aboutUs();
	$v = $about->version;
	$html = '';
	foreach ($scripts as $key=>$script) {
		$key = explode('/', $key);
		$array[] = end($key);
	}
	if (bagalleryHelper::loadJQuery($id) == 0) {

	} else if (!in_array('jquery.min.js', $array) && !in_array('jquery.js', $array)) {
		$src = JUri::root(true). '/media/jui/js/jquery.min.js';
		$html .= '<script type="text/javascript" src="' .$src. '"></script>';
	}
	$src = JURI::root(). 'components/com_bagallery/libraries/modal/ba_modal.js?'.$v;
	$html .= '<script type="text/javascript" src="' .$src. '"></script>';
	$src = 'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css';
	$html .= '<link rel="stylesheet" href="' .$src. '">';
	$src = '//fonts.googleapis.com/css?family=Roboto:500';
	$html .= '<link rel="stylesheet" href="' .$src. '">';
	$src = JUri::root(). 'components/com_bagallery/assets/css/ba-style.css?'.$v;
	$html .= '<link rel="stylesheet" href="' .$src. '">';
	$src = JUri::root(). 'components/com_bagallery/assets/css/ba-effects.css?'.$v;
	$html .= '<link rel="stylesheet" href="' .$src. '">';
	$src = JURI::root() . 'components/com_bagallery/libraries/ba_isotope/ba_isotope.js?'.$v;
	$html .= '<script type="text/javascript" src="'.$src.'"></script>';
	$src = JURI::root(). 'components/com_bagallery/libraries/lazyload/jquery.lazyload.min.js?'.$v;
	$html .= '<script type="text/javascript" src="' .$src. '"></script>';
	$src = JURI::root(). 'components/com_bagallery/assets/js/ba-gallery.js?'.$v;
	$html .= '<script type="text/javascript" src="' .$src. '"></script>';
	return $html;
}