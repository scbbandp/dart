<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );
jimport('joomla.filesystem.folder');
 
class plgSystemBagallery extends JPlugin
{
    public function __construct( &$subject, $config )
    {
        parent::__construct( $subject, $config );
    }

    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        if ($app->isSite()) {
            $path = JPATH_ROOT . '/components/com_bagallery/helpers/bagallery.php';
            $dir = $this->checkOverride();
            if ($dir) {
                $path = $dir;
            }
            JLoader::register('bagalleryHelper', $path);
        }
    }
    
    public function checkOverride()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('template')
            ->from('#__template_styles')
            ->where('`client_id`=0')
            ->where('`home`=1');
        $db->setQuery($query);
        $template = $db->loadResult();
        $path = JPATH_ROOT. '/templates/' .$template. '/html/com_bagallery';
        if (JFolder::exists($path)) {
            if (JFolder::exists($path. '/helpers')) {
                $file = JFolder::files($path. '/helpers', 'bagallery.php');
                if (!empty($file)) {
                    return $path. '/helpers/bagallery.php';
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }
    
    public function onBeforeCompileHead()
    {
        $app = JFactory::getApplication();
        $loaded = JLoader::getClassList( );
        $doc = JFactory::getDocument();
        if (isset($loaded['bagalleryhelper'])) {
            if ($app->isSite() && $doc->getType() == 'html') {
                $a_id = $app->input->get('a_id');
                if (empty($a_id)) {
                    bagalleryHelper::addStyle();
                }
            }
        }
    }
    
    public function onContentBeforeDisplay($context, &$article)
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        if ($app->isSite() && $doc->getType() == 'html') {
            if (!empty($article->introtext)) {
                $regex = '/\[gallery ID=+(.*?)\]/i';
                preg_match_all($regex, $article->introtext, $matches, PREG_SET_ORDER);
                if ($matches) {
                    foreach ($matches as $index => $match) {
                        $gallery = explode(',', $match[1]);
                        $id = $gallery[0];
                        $pos = strpos($id, ' category ID');
                        if ($pos !== false) {
                            $id = substr($id, 0, $pos);
                        }
                        if (isset($id)) {
                            if (bagalleryHelper::checkGallery($id)) {
                                if (!strpos($article->text, $match[0])) {
                                    $article->text = $article->introtext;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function onAfterRender()
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        if ($app->isSite() && $doc->getType() == 'html') {
            $loaded = JLoader::getClassList();
            if (isset($loaded['bagalleryhelper'])) {
                $a_id = $app->input->get('a_id');
                if (empty($a_id)) {
                    $html = $app->getBody();
                    $pos = strpos($html, '</head>');
                    $head = substr($html, 0, $pos);
                    $body = substr($html, $pos);
                    $html = $head.$this->getContent($body);
                    $app->setBody($html);
                    $response = $app->getBody();
                    $searches = array(
                        '<meta name="og:url"',
                        '<meta name="og:title"',
                        '<meta name="og:type"',
                        '<meta name="og:image',
                        '<meta name="og:description"',
                    );
                    $replacements = array(
                        '<meta property="og:url"',
                        '<meta property="og:title"',
                        '<meta property="og:type"',
                        '<meta property="og:image',
                        '<meta property="og:description"',
                    );
                    if (JString::strpos($response, 'prefix="og: http://ogp.me/ns#"') === false) {
                        $searches[] = '<html ';
                        $searches[] = '<html>';
                        $replacements[] = '<html prefix="og: http://ogp.me/ns#" ';
                        $replacements[] = '<html prefix="og: http://ogp.me/ns#">';
                    }
                    $response = JString::str_ireplace($searches, $replacements, $response);
                    $app->setBody($response);
                }
            }
        }
    }
    
    public function getContent($body)
    {
        $regex = '/\[gallery ID=+(.*?)\]/i';
        preg_match_all($regex, $body, $matches, PREG_SET_ORDER);
        if ($matches) {
            foreach ($matches as $index => $match) {
                $gallery = explode(',', $match[1]);
                $id = $gallery[0];
                $pos = strpos($id, ' category ID');
                if ($pos !== false) {
                    $id = substr($id, 0, $pos);
                }
                if (isset($id)) {
                    if (bagalleryHelper::checkGallery($id)) {
                        $doc = JFactory::getDocument();
                        $gallery = bagalleryHelper::drawHTMLPage($match[1]);
                        $about = bagalleryHelper::aboutUs();
                        $v = $about->version;
                        $url = JURI::root() . 'components/com_bagallery/assets/js/ba-gallery.js?'.$v;
                        if (!array_key_exists($url, $doc->_scripts) && strpos($body, 'assets/js/ba-gallery.js') === false) {
                            $gallery = $this->drawScripts($id).$gallery;
                        }
                        $body = @preg_replace("|\[gallery ID=".$match[1]."\]|", addcslashes($gallery, '\\$'), $body, 1);
                    }
                }
            }
        }
        return $body;
    }
    
    public function drawScripts($id)
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
}