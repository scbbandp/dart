<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

abstract class bagalleryHelper 
{
    protected static $_currentCat = 0;
    protected static $_activeImage = false;
    protected static $_currentAlias = '';

    public static function addStyle()
    {
        $doc = JFactory::getDocument();
        $url = $_SERVER['REQUEST_URI'];
        $url = urldecode($url);
        $url = explode('?', $url);
        $url = end($url);
        $img = false;
        if (is_numeric($url)) {
            $img = self::getImage($url);
        } else {
            $img = self::checkImage($url);
        }
        if ($img) {
            $image = strpos($img->url, 'images/');
            $image = JPATH_ROOT.'/'.substr($img->url, $image);
            $title = $doc->getTitle();
            if (!empty($img->title)) {
                $title = $img->title;
            }
            if (file_exists($image)) {
                $image = new JImage($image);
                $doc->setMetaData('og:image:width', $image->getWidth());
                $doc->setMetaData('og:image:height', $image->getHeight());
            }
            $pos = strpos($_SERVER['REQUEST_URI'], '?');
            $surl = substr($_SERVER['REQUEST_URI'], $pos);
            $doc->setMetaData('og:title', $title);
            $doc->setMetaData('og:type', "article");
            $doc->setMetaData('og:image:url', $img->url);
            $doc->setMetaData('og:url', $doc->getBase().$surl);
            $doc->setMetaData('og:description', $doc->getDescription());
            self::drawScripts($img->form_id);
            return;
        }
        $buffer = $doc->getBuffer();
        if (!empty($buffer)) {
            foreach ($buffer as $buff) {
                foreach ($buff as $pos) {
                    foreach ($pos as $items) {
                        $regex = '/\[gallery ID=+(.*?)\]/i';
                        preg_match_all($regex, $items, $matches, PREG_SET_ORDER);
                        if ($matches) {
                            foreach ($matches as $index => $match) {
                                $id = $match[1];
                                $pos = strpos($id, ' category ID');
                                if ($pos !== false) {
                                    $id = substr($id, 0, $pos);
                                }
                                if (self::checkGallery($id)) {
                                    self::drawScripts($id);
                                    return;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function loadJQuery($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('load_jquery')
            ->from('#__bagallery_galleries')
            ->where('`id` = '.$id);
        $db->setQuery($query);
        $res = $db->loadResult();
        
        return $res;
    }

    public static function aboutUs()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("manifest_cache");
        $query->from("#__extensions");
        $query->where("type=" .$db->quote('component'))
            ->where('element=' .$db->quote('com_bagallery'));
        $db->setQuery($query);
        $about = $db->loadResult();
        $about = json_decode($about);
        return $about;
    }
    
    public static function drawScripts($id)
    {
        $doc = JFactory::getDocument();
        $scripts = $doc->_scripts;
        $array = array();
        $about = self::aboutUs();
        $v = $about->version;
        foreach ($scripts as $key=>$script) {
            $key = explode('/', $key);
            $array[] = end($key);
        }
        if (self::loadJQuery($id) == 0) {
            
        } else if (!in_array('jquery.min.js', $array) && !in_array('jquery.js', $array)) {
            $doc->addScript(JUri::root() . 'media/jui/js/jquery.min.js');
        }
        $src = 'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css';
        $doc->addScript(JURI::root() . 'components/com_bagallery/libraries/modal/ba_modal.js?'.$v);
        $doc->addStyleSheet($src);
        $doc->addStyleSheet('//fonts.googleapis.com/css?family=Roboto:500');
        $doc->addStyleSheet(JUri::root() . 'components/com_bagallery/assets/css/ba-style.css?'.$v);
        $doc->addStyleSheet(JUri::root() . 'components/com_bagallery/assets/css/ba-effects.css?'.$v);
        $doc->addScript(JURI::root() . 'components/com_bagallery/libraries/ba_isotope/ba_isotope.js?'.$v);
        $doc->addScript(JURI::root() . 'components/com_bagallery/libraries/lazyload/jquery.lazyload.min.js?'.$v);
        $doc->addScript(JURI::root() . 'components/com_bagallery/assets/js/ba-gallery.js?'.$v);
    }

    public static function getThumbnail($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('thumbnail_url')
            ->from('#__bagallery_items')
            ->where('`id` = '.$id);
        $db->setQuery($query);
        $res = $db->loadResult();
        $pos = stripos($res, 'images/');
        if ($pos !== 0) {
            $res = substr($res, $pos);
        }
        return $res;
    }
    
    public static function checkImage($title)
    {
        $imageUrl = '';
        $title = strtolower($title);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("id, url, title, lightboxUrl, form_id");
        $query->from("#__bagallery_items");
        $db->setQuery($query);
        $urls = $db->loadObjectList();
        $imgTitle = '';
        $obj = false;
        foreach ($urls as $url) {
            $search = str_replace(' ', '-', $url->lightboxUrl);
            $search = str_replace('%', '', $search);
            $search = str_replace('?', '', $search);
            $search = strtolower($search);
            if ($search == urldecode($title)) {
                $obj = self::getImage($url->id);
                break;
            }
            $search = str_replace(' ', '-', $url->title);
            $search = str_replace('%', '', $search);
            $search = str_replace('?', '', $search);
            $search = strtolower($search);
            if ($search == urldecode($title)) {
                $obj = self::getImage($url->id);
                break;
            }
        }
        
        return $obj;
    }
    
    public static function getImage($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("form_id, url, watermark_name, id, title, imageId, category");
        $query->from("#__bagallery_items");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $obj = $db->loadObject();
        if (empty($obj)) {
            return false;
        }
        $watermark = self::getWatermark($obj->form_id);
        $pos = stripos($obj->url, 'images/');
        if ($pos !== 0) {
            $obj->url = substr($obj->url, $pos);
        }
        $obj->url = JUri::root().$obj->url;
        if (!empty($watermark->watermark_upload)) {
            $obj->url = JUri::root().'images/bagallery/gallery-' .$obj->form_id.'/watermark/'.$obj->watermark_name;
        }
        if (!empty($obj->url)) {
            return $obj;
        } else {
            return false;
        }
    }
    
    public static function checkGallery($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("published");
        $query->from("#__bagallery_galleries");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $publish = $db->loadResult();
        if (isset($publish)) {
            if ($publish == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function checkShare($lightbox)
    {
        if ($lightbox->twitter_share == 1) {
            return true;
        } else if ($lightbox->facebook_share == 1) {
            return true;
        } else if ($lightbox->google_share == 1) {
            return true;
        } else if ($lightbox->twitter_share == 1) {
            return true;
        } else if ($lightbox->linkedin_share == 1) {
            return true;
        } else if ($lightbox->vkontakte_share == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAlbumAlias($category, $aliasMap, $parentMap)
    {
        $alias = $aliasMap[$category];
        $alias = strtolower($alias);
        $alias = str_replace(' ', '-', $alias);
        $alias = str_replace('%', '', $alias);
        $alias = str_replace('?', '', $alias);
        if (isset($parentMap[$category])) {
            $parent = str_replace('category-', '', $parentMap[$category]);
            if (isset($aliasMap[$parent])) {
                $alias = self::getAlbumAlias($parent, $aliasMap, $parentMap).'&'.$alias;
            }
        }
        
        return $alias;
    }

    public static function getUnpublish($unpublishCats, $parentMap, $value)
    {
        $cat = str_replace('category-', '', $parentMap[$value]);
        if (in_array($cat, $unpublishCats)) {
            $unpublishCats[] = $value;
        } else if (isset($parentMap[$cat])) {
            $unpublishCats = self::getUnpublish($unpublishCats, $parentMap, $cat);
        }

        return $unpublishCats;
    }

    public static function getChildImages($id, $parent, $parentMap)
    {
        $count = 0;
        if (in_array($parent, $parentMap)) {
            foreach ($parentMap as $key => $value) {
                if ($value == $parent) {
                    $count += self::getImageCount($id, 'category-'.$key);
                    $count += self::getChildImages($id, 'category-'.$key, $parentMap);
                }
            }
        }
        return $count;
    }

    public static function checkGalleryUri()
    {
        $doc = JFactory::getDocument();
        $url = $_SERVER['REQUEST_URI'];
        $url = urldecode($url);
        $url = explode('?', $url);
        $url = end($url);
        $img = false;
        if (is_numeric($url)) {
            return self::getImage($url);
        } else {
            return self::checkImage($url);
        }
    }

    public static function getUri($aliasMap, $parentMap)
    {
        if (empty($_SERVER['QUERY_STRING'])) {
            return JUri::current();
        }
        $obj = self::checkGalleryUri();
        if ($obj) {
            $key = str_replace('category-', '', $obj->category);
            self::$_currentCat = $key;
            self::$_activeImage = true;
            return JUri::current();
        }
        $current = JUri::current().'?'.urldecode($_SERVER['QUERY_STRING']);
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);
        $url = end($url);
        $pos = strrpos($current, 'root');
        if ($pos !== false) {
            $prev = $current[$pos - 1];
            $len = $pos + strlen('root');
            $flag = false;
            if (isset($current[$len]) &&
                ($current[$len] == '?' || $current[$len] == '&')) {
                $flag = true;
                if ($current[$len] == '&') {
                    if (isset($current[$len+1]) && isset($current[$len+2]) &&
                        isset($current[$len+3]) && isset($current[$len+4]) &&
                        isset($current[$len+4]) && isset($current[$len+6]) && isset($current[$len+7])) {
                        $next = $current[$len+1].$current[$len+2].$current[$len+3].$current[$len+4];
                        $next .= $current[$len+5].$current[$len+6].$current[$len+7];
                        if ($next == 'ba-page') {
                            $flag = true;
                        } else {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }
                }
            }
            if (($prev == '&' || $prev == '?') && (!isset($current[$pos + strlen('root')]) || $flag)) {
                $current = substr($current, 0, $pos - 1);
                return $current;
            }
        }
        foreach ($aliasMap as $key => $value) {
            $alias = self::getAlbumAlias($key, $aliasMap, $parentMap);
            $pos = strrpos($current, $alias);
            if ($pos !== false) {
                $prev = $current[$pos - 1];
                $len = $pos + strlen($alias);
                $flag = false;
                if (isset($current[$len]) &&
                    ($current[$len] == '?' || $current[$len] == '&')) {
                    $flag = true;
                    if ($current[$len] == '&') {
                        if (isset($current[$len+1]) && isset($current[$len+2]) &&
                            isset($current[$len+3]) && isset($current[$len+4]) &&
                            isset($current[$len+4]) && isset($current[$len+6]) && isset($current[$len+7])) {
                            $next = $current[$len+1].$current[$len+2].$current[$len+3].$current[$len+4];
                            $next .= $current[$len+5].$current[$len+6].$current[$len+7];
                            if ($next == 'ba-page') {
                                $flag = true;
                            } else {
                                $flag = false;
                            }
                        } else {
                            $flag = false;
                        }
                    }
                }
                if (($prev == '&' || $prev == '?') && (!isset($current[$pos + strlen($alias)]) || $flag)) {
                    self::$_currentCat = $key;
                    $current = substr($current, 0, $pos - 1);
                    break;
                }
            }
        }
        return $current;
    }
    
    public static function drawHTMLPage($id)
    {
        $pos = strpos($id, ' category ID');
        $categorySelector = false;
        if ($pos !== false) {
            $categorySelector = substr($id, $pos+strlen(' category ID='));
            $id = substr($id, 0, $pos);
        }
        $categories = self::getCategories($id);
        $watermark = self::getWatermark($id);
        $compression = self::getCompression($id);
        $general = self::getGeneralOpions($id);
        $defaultFilter = json_encode(self::getDefaultFilter($id));
        $activeFilter = json_encode(self::getActiveFilter($id));
        $galleryOptions = self::getGalleryOptions($id);
        $thumbnail = self::getThumbnailOptions($id);
        $albums = self::getAlbumsOptions($id);
        $quality = $thumbnail->image_quality;
        $pagination = self::getPaginationOptions($id);
        $lightbox = self::getLightboxOptions($id);
        $sorting = self::getSorting($id);
        $header = self::getHeaderOptions($id);
        $sorting = explode('-_-', $sorting);
        $copyright = self::getCopyrightOptions($id);
        $lightbox->header_icons_color = $header->header_icons_color;
        $html = '';
        list($headr, $headg, $headb) = sscanf($lightbox->lightbox_bg, "#%02x%02x%02x");
        $language = JFactory::getLanguage();
        $language->load('com_bagallery', JPATH_ADMINISTRATOR);
        if (!$lightbox->enable_alias) {
            $general->enable_disqus = 0;
        }
        $html .= "<div class='ba-gallery " .$general->class_suffix;
        $regex = '/\[forms ID=+(.*?)\]/i';
        $html .= "' data-gallery='" .$id. "'";
        $html .= ' style="background-color:rgba(';
        $html .= $headr. ',' .$headg. ',' .$headb. ',' .$lightbox->lightbox_bg_transparency;
        $html .= ');">';
        if ($albums->album_enable_lightbox == 1) {
            $html .= '<div class="albums-backdrop"></div>';
        }
        if (JFactory::getUser()->authorise('core.edit', 'com_bagallery')) {
            $html .= '<a class="ba-edit-gallery-btn" target="_blank"';
            $html .= 'href="'.JUri::root().'index.php?option=com_bagallery&view=gallery&tmpl=component&id='.$id.'">';
            $html .= '<i class="zmdi zmdi-settings"></i>';
            $html .= '<span class="ba-tooltip ba-top">'.$language->_('EDIT_GALLERY').'</span>';
            $html .= '</a>';
        }
        $html .= '<div class="modal-scrollable">';
        $html .= '<div class="ba-modal gallery-modal '.$general->class_suffix.'" style="display:none">';
        if ($lightbox->enable_alias && self::checkShare($lightbox)) {
            $html .= '<div class="ba-share-icons" style="background-color:rgba(';
            $html .= $headr. ',' .$headg. ',' .$headb. ',' .$lightbox->lightbox_bg_transparency;
            $html .= ');"><div class="ba-share" >';
            if ($lightbox->twitter_share == 1) {
                $html .= '<i class="ba-twitter-share-button zmdi zmdi-twitter"';
                $html .= '></i>';
            }
            if ($lightbox->facebook_share == 1) {
                $html .= '<i class="ba-facebook-share-button zmdi zmdi-facebook"></i>';
            }
            if ($lightbox->google_share == 1) {
                $html .= '<i class="ba-google-share-button zmdi zmdi-google"></i>';
            }
            if ($lightbox->pinterest_share == 1) {
                $html .= '<i class="ba-pinterest-share-button zmdi zmdi-pinterest"></i>';
            }
            if ($lightbox->linkedin_share == 1) {
                $html .= '<i class="ba-linkedin-share-button zmdi zmdi-linkedin"></i>';
            }
            if ($lightbox->vkontakte_share == 1) {
                $html .= '<i class="ba-vk-share-button zmdi zmdi-vk"></i>';
            }
            $html .= '</div></div>';
        }
        if ($lightbox->display_header) {
            $html .= '<div class="ba-modal-header row-fluid" style="box-shadow: inset 0px 130px 100px -125px rgba(';
            $html .= $headr. ',' .$headg. ',' .$headb. ',' .$lightbox->lightbox_bg_transparency;
            $html .= ');"><div class="ba-modal-title" ';
            $html .= '>';
            if ($lightbox->lightbox_display_title) {
                $html .= '<h3 class="modal-title" style="color:' .$header->header_icons_color. ';"></h3>';
            }
            $html .= '</div><div class="ba-center-icons">';
            if ($lightbox->display_zoom == 1) {
                $html .= '<i style="color:';
                $html .= $header->header_icons_color. '" class="ba-zoom-in zmdi zmdi-zoom-in"></i>';
                $html .= '<i class="ba-zoom-out disabled-item zmdi zmdi-fullscreen-exit" style="color:';
                $html .= $header->header_icons_color. '"></i>';
            }
            $html .= '</div><div ';
            $html .= 'class="ba-right-icons"><div class="header-icons">';
            if ($lightbox->display_download == 1) {
                $html .= '<a href="#" class="ba-download-img zmdi zmdi-download" style="color:';
                $html .= $header->header_icons_color. '" download></a>';
            }
            if ($lightbox->enable_alias && self::checkShare($lightbox)) {
                $html .= '<i class="zmdi zmdi-share" style="color:';
                $html .= $header->header_icons_color. '"></i>';
            }
            if ($lightbox->display_likes) {
                $html .= '<div class="ba-like-wrapper"><div class="ba-like">';
                $html .= '<div class="ba-likes"><p></p></div>';
                $html .= '<i class="ba-add-like zmdi zmdi-favorite" style="color:';
                $html .= $header->header_icons_color.'"></i>';
                $html .= '</div></div>';
            }
            if ($lightbox->display_fullscreen) {
                $html .= '<i class="zmdi zmdi-fullscreen display-lightbox-fullscreen" style="color:';
                $html .= $header->header_icons_color. '"></i>';
            }
            $html .= '<i class="ba-icon-close zmdi zmdi-close" ';
            $html .= 'style="color:'.$header->header_icons_color;
            $html .= '"></i></div></div></div>';
        }
        $html .= '<div class="ba-modal-body"><div class="modal-image"><input ';
        $html .= 'type="hidden" class="ba-juri" value="' .JUri::root(). '"></div>';
        $html .= '<div class="description-wrapper">';
        if ($general->enable_disqus == 1) {
            $html .= '<div id="disqus_thread"></div><input type="hidden" class="';
            $html .= 'disqus-subdomen" value="' .$general->disqus_subdomen. '">';
        } else if ($general->enable_disqus == 'vkontakte') {
            $html .= '<div id="ba-vk-'.$id.'"></div><input type="hidden" value="';
            $html .= $general->vk_api_id.'" id="vk-api-id-'.$id.'">';
        }
        $html .= '</div>';
        $html .= "</div></div><input type='hidden' class='lightbox-options' ";
        $html .= "value='" .json_encode($lightbox). "'>";
        $html .= '<div class="modal-nav" style="display:none"><i class="ba-left-action zmdi ';
        $html .= 'zmdi-chevron-left" style="color:' .$header->nav_button_icon. '; ';
        $html .='background-color:' .$header->nav_button_bg. '"></i><i class="';
        $html .= 'ba-right-action zmdi zmdi-chevron-right" style="color:' .$header->nav_button_icon. '; ';
        $html .= 'background-color:' .$header->nav_button_bg. '"></i></div>';
        $html .= "</div>";
        $categoryName = array();
        $unpublishCats = array();
        $catImageCount = array('root' => self::getImageCount($id, 'root'));
        $aliasMap = array();
        $parentMap = array();
        $catSel = '';
        $catDesc = '';
        $user = JFactory::getUser();
        $groups = $user->getAuthorisedViewLevels();
        foreach ($categories as $category) {
            $parent = $category->parent;
            $catId = $category->id;
            $access = $category->access;
            $category->settings = str_replace('forms ID=', 'baforms ID=', $category->settings);
            $category = explode(';', $category->settings);
            if ($category[2] == 1 && in_array($access, $groups)) {
                $categoryName[$category[4]] = $category[0];
                if (isset($category[8]) && !empty($category[8])) {
                    $alias = $category[8];
                } else {
                    $alias = $category[0];
                }
                $aliasMap[$category[4]] = $alias;
                if (!empty($parent)) {
                    $parentMap[$category[4]] = $parent;
                }
                if ($category[1] == 1) {
                    self::$_currentCat = $category[4];
                }
                if ($categorySelector && $categorySelector == $catId) {
                    $catSel = $category[4];
                    if (isset($category[7])) {
                        $catDesc = $category[7];
                    }
                }
            } else {
                $unpublishCats[] = $category[4];
            }
            if ($category[4] !== '0') {
                $catImageCount['category-'.$category[4]] = self::getImageCount($id, 'category-'.$category[4]);
            }
        }
        foreach ($parentMap as $key => $value) {
            $parent = str_replace('category-', '', $value);
            $unpublishCats = self::getUnpublish($unpublishCats, $parentMap, $key);
        }
        if ($general->album_mode == 1) {
            self::$_currentCat = 'root';
        }
        $html .= '<div class="ba-gallery-row-wrapper">';
        if ($albums->album_enable_lightbox == 1) {
            $html .= '<i class="zmdi zmdi-close albums-backdrop-close"></i>';
        }
        $currentUri = self::getUri($aliasMap, $parentMap);
        if ($general->category_list == 1 && $general->album_mode != 1 && !$categorySelector) {
            $html .= "<div class='row-fluid'><div class='span12 category-filter' style='display: none;'>";
            foreach ($categories as $category) {
                $category = explode(';', $category->settings);
                if (!in_array($category[4], $unpublishCats)) {
                    $alias = $aliasMap[$category[4]];
                    $html .= "<a ";
                    $alias = strtolower($alias);
                    $alias = str_replace(' ', '-', $alias);
                    $alias = str_replace('%', '', $alias);
                    $alias = str_replace('?', '', $alias);
                    $alias = urlencode($alias);
                    $html .= " data-alias='".$alias."'";
                    if (strpos($currentUri, '?') === false) {
                        $alias = $currentUri.'?'.$alias;
                    } else {
                        $alias = $currentUri.'&'.$alias;
                    }
                    if (self::$_currentCat == $category[4] && self::$_activeImage) {
                        self::$_currentAlias = $alias;
                    }
                    $html .= 'href="'.$alias.'"';
                    $html .= " data-filter='.category-" .$category[4];
                    $html .= "' class='ba-btn ba-filter";
                    if ($category[1] == 1) {
                        $html .= "-active";
                    }
                    $html .= "'>" .$category[0]. "</a>";
                }
            }
            $html .= "<select class='ba-select-filter'>";
            foreach ($categories as $category) {
                $category = explode(';', $category->settings);
                if (!in_array($category[4], $unpublishCats)) {
                    $html .= "<option value='.category-" .$category[4]. "'";
                    if ($category[1] == 1) {
                        $html .= " selected";
                    }
                    $html .= ">". $category[0]. "</option>";
                }
            }
            $html .= "</select>";
            $html .= "<input type='hidden' value='" .$defaultFilter. "' class='";
            $html .= "default-filter-style'>";
            $html .= "<input type='hidden' value='" .$activeFilter. "' class='";
            $html .= "active-filter-style'>";
            $html .= "</div></div>";
        }
        $height2 = array();
        $width2 = array();
        if ($albums->album_layout == 'masonry') {
            for ($i = 0; $i < 100; $i++) {
                $height2[] = 4 * $i + 2;
            }
        } else if ($albums->album_layout == 'metro') {
            for ($i = 0; $i < 100; $i++) {
                $height2[] = 10 * $i + 2;
                $height2[] = 10 * $i + 5;
                $width2[] = 10 * $i + 4;
                $width2[] = 10 * $i + 7;
                $height2[] = 10 * $i + 7;
                
            }
        } else if ($albums->album_layout == 'square') {
            for ($i = 0; $i < 100; $i++) {
                $height2[] = 5 * $i + 5;
                $width2[] = 5 * $i + 5;
            }
        }
        if ($general->album_mode == 1 && !$categorySelector) {
            $cat = htmlspecialchars(json_encode($categories), ENT_QUOTES);
            $html .= '<div class="row-fluid"><div class="ba-goback" style';
            $html .= '="display:none"><a class="zmdi zmdi-long-arrow-left"';
            $html .= '></a><h2';
            $html .= '></h2>';
            $html .= "<div class='categories-description'>";
            $html .= "<input type='hidden' value='" .$cat;
            $html .= "' class='categories'></div>";
            $html .= '</div><div class="ba-album';
            if ($albums->album_disable_caption != 1) {
                $html .= ' css-style-';
                $html .= $albums->album_thumbnail_layout;
            }            
            $html .='">';
            $img = JUri::root().'index.php?option=com_bagallery&task=gallery.showCatImage&image=';
            $width = $thumbnail->image_width;
            $catIndex = 0;
            foreach ($categories as $category) {
                $catId = $category->id;
                $parent = $category->parent;
                if (empty($parent)) {
                    $parent = 'root';
                }
                $category = explode(';', $category->settings);
                if ($category[3] != '*' && !in_array($category[4], $unpublishCats)) {
                    $className = '';
                    $i = self::getImageCount($id, 'category-'.$category[4]);
                    $i += self::getChildImages($id, 'category-'.$category[4], $parentMap);
                    $file = JPATH_ROOT. '/images/bagallery/gallery-'.$id.'/album/';
                    if (empty($category[5])) {
                        $category[5] = 'components/com_bagallery';
                        $category[5] .= '/assets/images/image-placeholder.jpg';
                        $file.= 'image-placeholder.jpg';
                    } else {
                        $pos = stripos($category[5], 'images/');
                        $category[5] = substr($category[5], $pos);
                        $name = explode('/', $category[5]);
                        $file .= 'category-'.$category[4].'-'.end($name);
                    }
                    $src = $img.$category[5].'&width='.$albums->album_width.'&quality='.$albums->album_quality;
                    $src .= '&layout='.$albums->album_layout. '&index='.$catIndex;
                    $src .= '&id='.$catId.'&category=category-'.$category[4].'&gallery='.$id;
                    if (in_array($catIndex + 1, $height2)) {
                        $className.= ' height2';
                    }
                    if (in_array($catIndex + 1, $width2)) {
                        $className.= ' width2';
                    }
                    $catIndex++;
                    $origWidth = 250;
                    $origHeight = 250;
                    if (JFile::exists($file)) {
                        $ext = strtolower(JFile::getExt($file));
                        $imageCreate = self::imageCreate($ext);
                        $orig = $imageCreate($file);
                        $origWidth = imagesx($orig);
                        $origHeight = imagesy($orig);
                        $src = str_replace(JPATH_ROOT.'/', JUri::root(), $file);
                    } else if (!JFile::exists(JPATH_ROOT.'/'.$category[5])) {
                        $src = JUri::root().'/'.$category[5];
                    }
                    $html .= '<div class="ba-album-items '.$parent.$className.'"';
                    $alias = $aliasMap[$category[4]];
                    $alias = strtolower($alias);
                    $alias = str_replace(' ', '-', $alias);
                    $alias = str_replace('%', '', $alias);
                    $alias = str_replace('?', '', $alias);
                    $alias = urlencode($alias);
                    $html .= " style='display:none;' data-alias='".$alias."'";
                    $alias = self::getAlbumAlias($category[4], $aliasMap, $parentMap);
                    if (strpos($currentUri, '?') === false) {
                        $alias = $currentUri.'?'.$alias;
                    } else {
                        $alias = $currentUri.'&'.$alias;
                    }
                    if (self::$_currentCat == $category[4] && self::$_activeImage) {
                        self::$_currentAlias = $alias;
                    }
                    $html .= ' data-filter=".category-';
                    $html .= $category[4]. '"><a href="'.$alias;
                    $html .= '"></a><div class="ba-image">';
                    $html .='<img src="' .$src.'" data-width="'.$origWidth.'" data-height="'.$origHeight.'"></div>';
                    if ($albums->album_disable_caption != 1) {
                        $html .= '<div class="ba-caption"';
                        if ($albums->album_thumbnail_layout != 11) {
                            $html .= ' style="background-color: '.$albums->album_caption_bg.';"';
                        }
                        $html .= '><div class="ba-caption-content">';
                        if ($albums->album_display_title) {
                            $html .= '<h3 style="font-size: '.$albums->album_title_size.'px; font-weight: ';
                            $html .= $albums->album_title_weight.'; text-align: '.$albums->album_title_alignment.';';
                            if ($albums->album_thumbnail_layout != 11) {
                                $html .= 'color: '.$albums->album_title_color.';';
                            }
                            $html .= '">' .$category[0]. '</h3>';
                        }
                        if ($albums->album_display_img_count) {
                            $html .= '<p style="font-size: '.$albums->album_img_count_size.'px; font-weight: ';
                            $html .= $albums->album_img_count_weight.'; text-align: '.$albums->album_img_count_alignment.';';
                            if ($albums->album_thumbnail_layout != 11) {
                                $html .= 'color: '.$albums->album_img_count_color.';';
                            }
                            $html .= '">'.$i. ' ' .$language->_('PHOTOS').'</p>';
                        }
                        $html .= '</div></div>';
                    }
                    $html .= '</div>';
                }
            }
            $html .= "<input type='hidden' value='" .json_encode($pagination). "' class='back-style'>";
            $html .= "<input type='hidden' value='" .json_encode($albums). "' class='albums-options'>";
            $html .= '<input type="hidden" class="current-root" value="'.$currentUri.'">';
            $html .= '<input type="hidden" class="album-mode" value="';
            $html .= $general->album_mode.'"></div></div>';
        }
        if (($general->album_mode != 1 && $general->category_list == 1) || $categorySelector) {
            $html .= "<div class='row-fluid'><div class='categories-description'>";
            if (!$categorySelector) {
                $cat = htmlspecialchars(json_encode($categories), ENT_QUOTES);
                $html .= "<input type='hidden' value='" .$cat."' class='categories'>";
                
            } else {
                $catDesc = str_replace('-_-_-_', "'", $catDesc);
                $catDesc = str_replace('-_-', ";", $catDesc);
                $html .= $catDesc;
            }
            $html .= "</div></div>";
        }
        $html .= "<div class='row-fluid'>";
        $html .= "<div class='span12 ba-gallery-grid";
        if ($thumbnail->disable_caption != 1) {
            $html .= " css-style-" .$galleryOptions->thumbnail_layout;
        }
        if ($lightbox->disable_lightbox == 1) {
            $html .= ' disabled-lightbox';
        }
        if ($thumbnail->disable_caption == 1) {
            $html .= ' disable-caption';
        }
        $html .= "'>";
        if (!empty($general->all_sorting)) {
            $sorting = explode(',', $general->all_sorting);
        }
        if (!empty($watermark->watermark_upload)) {
            $pos = stripos($watermark->watermark_upload, 'images/');
            if ($pos !== 0) {
                $watermark->watermark_upload = substr($watermark->watermark_upload, $pos);
            }
        }
        if ($general->page_refresh == 1 || $categorySelector) {
            $start = 0;
            $cat = self::$_currentCat === 'root' ? 'root' : 'category-'.self::$_currentCat;
            if ($categorySelector) {
                $cat = 'category-'.$catSel;
            }
            if ($cat != 'category-0') {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('imageId')
                    ->from('#__bagallery_items')
                    ->where('`form_id` = '.$id)
                    ->where('`category` = '.$db->quote($cat));
                $db->setQuery($query);
                $items = $db->loadColumn();
                $emptyArray = array();
                foreach ($sorting as $value) {
                    if (in_array($value, $items)) {
                        $emptyArray[] = $value;
                    }
                }
                $start = 0;
                $sorting = $emptyArray;
            } else {
                if (!empty($unpublishCats)) {
                    $ind = 0;
                    foreach ($catImageCount as $key => $value) {
                        if (in_array(str_replace('category-', '', $key), $unpublishCats)) {
                            for ($i = $ind; $i < $value + $ind; $i++) {
                                unset($sorting[$i]);
                            }
                        }
                        $ind += $value;
                    }
                    $newSort = array();
                    foreach ($sorting as $value) {
                        $newSort[] = $value;
                    }
                    $sorting = $newSort;
                }
            }
            $catImages = self::getImageCount($id, $cat, $unpublishCats);
            $end = $start + $catImages;
            if ($general->page_refresh == 1 && $general->pagination == 1) {
                $end = $pagination->images_per_page + $start;
                $currentPage = 1;
                if (isset($_GET['ba-page']) && !empty($_GET['ba-page'])) {
                    $currentPage = $_GET['ba-page'];
                    $currentPage = explode('?', $currentPage);
                    $currentPage = $currentPage[0];
                    $start += $pagination->images_per_page * ($currentPage - 1);
                    $end = $start + $pagination->images_per_page;
                }
                if ($end > $start + $catImages) {
                    $end = $start + $catImages;
                }
                $pages = ceil($catImages / $pagination->images_per_page);
            }
        }
        $height2 = array();
        $width2 = array();
        $total = self::getImageCount($id, 'category-0');
        if ($general->gallery_layout == 'masonry') {
            for ($i = 0; $i < $total; $i++) {
                $height2[] = 4 * $i + 2;
            }
        } else if ($general->gallery_layout == 'metro') {
            for ($i = 0; $i < $total; $i++) {
                $height2[] = 10 * $i + 2;
                $height2[] = 10 * $i + 5;
                $width2[] = 10 * $i + 4;
                $width2[] = 10 * $i + 7;
                $height2[] = 10 * $i + 7;
                
            }
        } else if ($general->gallery_layout == 'square') {
            for ($i = 0; $i < $total; $i++) {
                $height2[] = 5 * $i + 5;
                $width2[] = 5 * $i + 5;
            }
        }
        $img = JUri::root().'index.php?option=com_bagallery&task=gallery.showImage&image=';
        $thumbCategory = '';
        $ind = 0;
        foreach ($sorting as $key => $sort) {
            if (empty($sort)) {
                continue;
            }
            if ($general->page_refresh == 1 || $categorySelector) {
                if ($key < $start) {
                    continue;
                } else if ($key >= $end) {
                    break;
                }
                $image = self::getSortImageRefresh($id, $sort);
            } else {
                $image = self::getSortImage($id, $sort, $unpublishCats);
                if (empty($image)) {
                    continue;
                }
            }
            if ($thumbCategory != $image->category) {
                $ind = 0;
                $thumbCategory = $image->category;
            }
            $imgSettings = json_decode($image->settings);
            $image->path = $imgSettings->path;
            $image->settings = null;
            $width = $thumbnail->image_width;
            $height = $thumbnail->image_width;
            $thumb = self::getThumbnail($image->id);
            $className = '';
            if (in_array($ind + 1, $height2)) {
                $height = $height * 2;
                $className.= ' height2';
            }
            if (in_array($ind + 1, $width2)) {
                $width = $width * 2;
                $className.= ' width2';
            }
            $ind++;
            $pos = stripos($image->url, 'images/');
            if ($pos !== 0) {
                $image->url = substr($image->url, $pos);
            }
            if (empty($image->lightboxUrl)) {
                $image->lightboxUrl = $image->title;
            }
            $image->url = JUri::root().$image->url;
            $pos = stripos($image->path, 'images/');
            if ($pos !== 0) {
                $image->path = substr($image->path, $pos);
            }
            $image->name = htmlspecialchars($image->name, ENT_QUOTES);
            $image->url = htmlspecialchars($image->url, ENT_QUOTES);
            $image->path = htmlspecialchars($image->path, ENT_QUOTES);
            $image->watermark_name = htmlspecialchars($image->watermark_name, ENT_QUOTES);
            if (!empty($image->thumbnail_url)) {
                $image->thumbnail_url = htmlspecialchars($image->thumbnail_url, ENT_QUOTES);
            }
            if (!empty($watermark->watermark_upload)) {
                if (JFile::exists(JPATH_ROOT.'/images/bagallery/gallery-' .$id.'/watermark/'.$image->watermark_name)) {
                    $image->url = JUri::root().'images/bagallery/gallery-' .$id.'/watermark/'.$image->watermark_name;
                } else {
                    $image->url = JUri::root().'index.php?option=com_bagallery&task=gallery.addWatermark&image=';
                    $image->url .= $image->path.'&watermark='.$watermark->watermark_upload;
                    $image->url .= '&position='.$watermark->watermark_position.'&opacity=';
                    $image->url .= $watermark->watermark_opacity.'&scale=';
                    $image->url .= $watermark->scale_watermark.'&name='.$image->watermark_name;
                    $image->url .= '&id='.$image->id.'&gallery='.$id;
                }
            }
            $n = substr($image->category, 9);
            if ($image->description) {
                $image->description = htmlspecialchars($image->description, ENT_NOQUOTES);
                $image->description = str_replace("'", '-_-_-_', $image->description);
                $image->description = str_replace('forms ID=', 'baforms ID=', $image->description);
            }
            if ($image->video) {
                $image->video = htmlspecialchars($image->video, ENT_NOQUOTES);
                $image->video = str_replace("'", '-_-_-_', $image->video);
                $image->video = str_replace('forms ID=', 'baforms ID=', $image->video);
            }
            if ($image->link) {
                $image->link = str_replace("'", '%27', $image->link);
            }
            $image->suffix = '';
            if (isset($imgSettings->suffix)) {
                $image->suffix = $imgSettings->suffix.' ';
            }
            $html .= "<div class='ba-gallery-items ";
            $html .= $image->suffix;
            if ($image->hideInAll == 0) {
                $html .= "category-0 ";
            }
            $html .= $image->category;
            $html .= $className;
            $html .= "' style='display: none;'>";
            if ($image->link != '') {
                $html .= "<a href='" .$image->link. "' target='_";
                $html .= $image->target. "'>";
            }
            $html .= "<div class='ba-image'><img ";
            if ($general->lazy_load) {
                $html .= "data-original='";
            } else {
                $html .= "src='";
            }
            $file = JPATH_ROOT.'/'.$thumb;
            $origWidth = 250;
            $origHeight = 250;
            if (empty($thumb) || strlen($thumb) < 10 || !JFile::exists($file)) {
                $src = $img . $image->path.'&width='.$width.'&height='.$height.'&quality=';
                $src .= $quality.'&id='.$image->id.'&gallery='.$id.'&category='.$image->category;
                $src .= '&gallery_layout='.$general->gallery_layout;
            } else {
                $thumb = htmlspecialchars($thumb, ENT_QUOTES);
                $src = JUri::root().$thumb;
                if ($general->gallery_layout == 'justified' || $general->gallery_layout == 'random') {
                    $ext = strtolower(JFile::getExt($file));
                    $imageCreate = self::imageCreate($ext);
                    $orig = $imageCreate($file);
                    $origWidth = imagesx($orig);
                    $origHeight = imagesy($orig);
                }
            }
            if (!JFile::exists(JPATH_ROOT.'/'.$image->path)) {
                $src = $image->url;
            }
            $html .= $src;
            $html .= "'";
            if ($general->gallery_layout == 'justified' || $general->gallery_layout == 'random') {
                $html .= ' data-width="'.$origWidth.'" data-height="'.$origHeight.'"';
            }
            $html .= " alt='" .$image->alt. "'>";
            $html .= "<div class='ba-caption'><div class='ba-caption-content'>";
            if ($thumbnail->display_title && $image->title) {
                $html .= "<h3>" .$image->title. "</h3>";
            }
            if ($thumbnail->display_categoty && $image->category != 'root' && !$categorySelector) {
                $html .= "<p class='image-category'>" .$categoryName[$n]. "</p>";
            }
            if ($image->short) {
                $html .= "<p class='short-description'>" .$image->short. "</p>";
            }
            $html .= "</div></div>";
            if ($image->title) {
                $image->title = htmlspecialchars($image->title, ENT_NOQUOTES);
                $image->title = str_replace("'", '-_-_-_', $image->title);
            }
            if ($image->short) {
                $image->short = htmlspecialchars($image->short, ENT_NOQUOTES);
                $image->short = str_replace("'", '-_-_-_', $image->short);
            }
            if ($image->alt) {
                $image->alt = htmlspecialchars($image->alt, ENT_NOQUOTES);
                $image->alt = str_replace("'", '-_-_-_', $image->alt);
            }
            $image->lightboxUrl = str_replace("'", '-_-_-_', $image->lightboxUrl);
            if ($compression->enable_compression == 1) {
                if (JFile::exists(JPATH_ROOT.'/images/bagallery/gallery-' .$id.'/compression/'.$image->watermark_name)) {
                    $image->url = JUri::root().'images/bagallery/gallery-' .$id.'/compression/'.$image->watermark_name;
                } else {
                    $image->url = JUri::root().'index.php?option=com_bagallery&task=gallery.compressionImage&image=';
                    $image->url .= $image->path.'&width='.$compression->compression_width;
                    $image->url .= '&quality='.$compression->compression_quality;
                    $image->url .= '&watermark='.$watermark->watermark_upload;
                    $image->url .= '&position='.$watermark->watermark_position.'&opacity=';
                    $image->url .= $watermark->watermark_opacity.'&scale=';
                    $image->url .= $watermark->scale_watermark.'&name='.$image->watermark_name;
                    $image->url .= '&id='.$image->id.'&gallery='.$id;
                }
            }
            if (isset($imgSettings->alternative) && !empty($imgSettings->alternative)) {
                $image->url = JUri::root().$imgSettings->alternative;
            }
            $html .= "<input type='hidden' class='image-id' data-id='ba-image-";
            $html .= $image->id. "' value='" .json_encode($image). "'>";
            $html .= "</div>";
            if ($image->link != '') {
                $html .= "</a>";
            }
            $html .= "</div>";
        }
        $html .= "<input type='hidden' class='gallery-options' value='";
        $html .= json_encode($galleryOptions). "'>";
        $html .= "<input type='hidden' value='" .$general->gallery_layout. "' class='gallery-layout'>";
        $html .= "<input type='hidden' value='" .$general->page_refresh. "' class='page-refresh'>";
        $html .= "<input type='hidden' value='" .$language->_('CREATE_THUMBNAILS'). "' class='creating-thumbnails'>";
        $html .= "<input type='hidden' value='" .json_encode($copyright). "' class='copyright-options'>";
        if (self::$_activeImage) {
            $html .= '<input type="hidden" class="active-category-image" value="'.self::$_currentAlias.'">';
        }
        $html .= "</div></div>";
        if ($general->pagination == 1) {
            $html .= "<div class='row-fluid'><div class='span12 ba-pagination'>";
            if ($general->page_refresh == 1 && $pages > 1) {
                if (self::$_currentCat === 'root') {
                    $alias = 'root';
                } else {
                    $alias = self::getAlbumAlias(self::$_currentCat, $aliasMap, $parentMap);
                }
                if (strpos($currentUri, '?') === false) {
                    $alias = $currentUri.'?'.$alias;
                } else {
                    $alias = $currentUri.'&'.$alias;
                }
                if ($pagination->pagination_type == 'dots' || $pagination->pagination_type == 'default') {
                    if ($pagination->pagination_type != 'dots') {
                        $html .= '<a href="'.$alias.'&ba-page=1" class="ba-btn ba-first-page';
                        if ($currentPage == 1) {
                            $html .= ' ba-dissabled';
                        }                    
                        $html .= '"';
                        $html .= ' style="display:none;"><span class="zmdi zmdi-skip-previous"></span></a>';
                    }
                    $html .= '<a href="'.$alias.'&ba-page='.($currentPage - 1).'" class="ba-btn ba-prev';
                    if ($currentPage == 1) {
                        $html .= ' ba-dissabled';
                    }
                    if ($pagination->pagination_type == 'dots') {
                        $html .= ' ba-dots';
                    }
                    $html .= '" style="display:none;"><span class="zmdi zmdi-play"></span></a>';
                    for ($i = 0; $i < $pages; $i++) {
                        $html .= '<a href="'.$alias.'&ba-page='.($i + 1).'" class="ba-btn';
                        if ($i == $currentPage - 1) {
                            $html .= ' ba-current';
                        }
                        if ($pagination->pagination_type == 'dots') {
                            $html .= ' ba-dots';
                        }
                        $html .= '"';
                        $html .= ' style="display:none;">';
                        if ($pagination->pagination_type != 'dots') {
                            $html .= ($i + 1);
                        }
                        $html .= '</a>';
                    }
                    $html .= '<a href="'.$alias.'&ba-page='.($currentPage + 1);
                    $html .= '" class="ba-btn ba-next';
                    if ($currentPage == $pages) {
                        $html .= ' ba-dissabled';
                    }
                    if ($pagination->pagination_type == 'dots') {
                        $html .= ' ba-dots';
                    }
                    $html .= '" style="display:none;"><span class="zmdi zmdi-play"></span></a>';
                    if ($pagination->pagination_type != 'dots') {
                        $html .= '<a href="'.$alias.'&ba-page='.$pages.'" class="ba-btn ba-last-page';
                        if ($currentPage == $pages) {
                            $html .= ' ba-dissabled';
                        }
                        $html .= '" style="display:none;"><span class="zmdi zmdi-skip-next"></span></a>';
                    }                    
                } else if ($pagination->pagination_type == 'slider') {
                    $prev = $currentPage - 1;
                    $next = $currentPage + 1;
                    if ($prev == 0) {
                        $prev = $pages;
                    }
                    if ($next > $pages) {
                        $next = 1;
                    }
                    $html .= '<a href="'.$alias.'&ba-page='.$prev.'" class="ba-btn ba-prev';
                    $html .= '" style="display:none;"><span class="zmdi zmdi-play"></span></a>';
                    $html .= '<a href="'.$alias.'&ba-page='.$next;
                    $html .= '" class="ba-btn ba-next';
                    $html .= '" style="display:none;"><span class="zmdi zmdi-play"></span></a>';
                }
            }
            $html .= "<input type='hidden' class='ba-pagination-options' value='";
            $html .= json_encode($pagination). "'>";
            $html .= "<input type='hidden' class='ba-pagination-constant' value='";
            $html .= $language->_('PREV'). "-_-" .$language->_('NEXT'). "-_-";
            $html .= $language->_('LOAD_MORE'). "-_-" .$language->_('SCROLL_TOP');
            $html .= "'></div></div>";
        }
        $html .= "</div></div>";
        $html .= "<div class='ba-gallery-substrate' style='height: 0;'></div>";
        return $html;
    }
    
    protected static function getCategories($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("settings, id, parent, access");
        $query->from("#__bagallery_category");
        $query->where("form_id=" . $id);
        $query->order("orders ASC");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    protected static function imageCreate($type) {
        switch ($type) {
            case 'jpeg':
            case 'jpg':
                $imageCreate = 'imagecreatefromjpeg';
                break;

            case 'png':
                $imageCreate = 'imagecreatefrompng';
                break;

            case 'gif':
                $imageCreate = 'imagecreatefromgif';
                break;

            default:
                $imageCreate = 'imagecreatefromjpeg';
        }
        return $imageCreate;
    }

    protected static function getImageCount($id, $category, $unpublish = array())
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("COUNT(id)");
        $query->from("#__bagallery_items");
        $query->where("`form_id` = " . $id);
        if ($category !== 'category-0') {
            $query->where("`category` = " . $db->Quote($category));
        } else {
            $query->where("`hideInAll` = " . $db->Quote('0'));
            foreach ($unpublish as $value) {
                $query->where("`category` <>" . $db->Quote('category-'.$value));
            }
        }
        $db->setQuery($query);
        $items = $db->loadResult();
        return $items;
    }

    protected static function getSortImageRefresh($id, $imageId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*");
        $query->from("#__bagallery_items");
        $query->where("`form_id`=" . $id);
        $query->where("`imageId`=" . $imageId);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }

    protected static function getSortImage($id, $imageId, $unpublishCats)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*");
        $query->from("#__bagallery_items");
        $query->where("form_id=" . $id);
        $query->where("imageId=" . $imageId);
        foreach ($unpublishCats as $value) {
            $query->where("`category` <>" . $db->Quote('category-'.$value));
        }
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getImages($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("category, name, url, title, short, thumbnail_url, target,
                        alt, description, link, video, id, likes, path, imageId,
                        lightboxUrl, watermark_name, hideInAll");
        $query->from("#__bagallery_items");
        $query->where("form_id=" . $id);
        $query->order("imageId ASC");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    public static function getWatermark($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('watermark_upload, watermark_position, watermark_opacity, scale_watermark')
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }

    protected static function getCompression($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('enable_compression, compression_width, compression_quality')
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getDefaultFilter($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("bg_color, bg_color_hover, border_color,
                        border_radius, font_color, font_color_hover,
                        font_weight, font_size, alignment")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getActiveFilter($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("bg_active, bg_hover_active, border_color_active,
                        font_color_active, font_color_hover_active")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getGalleryOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("thumbnail_layout, column_number, image_spacing, caption_bg,
                        title_color, title_weight, title_size, title_alignment,
                        category_color, category_weight, category_size, category_alignment,
                        description_color, description_weight, description_size,
                        description_alignment, caption_opacity, sorting_mode, random_sorting,
                        tablet_numb, phone_land_numb, phone_port_numb, disable_auto_scroll")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getGeneralOpions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("gallery_layout, category_list, pagination, lazy_load,
                        class_suffix, album_mode, all_sorting, enable_disqus,
                        disqus_subdomen, vk_api_id, page_refresh")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }

    protected static function getAlbumsOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("album_layout, album_width, album_quality, album_image_spacing, album_disable_caption,
            album_thumbnail_layout, album_caption_bg, album_display_title, album_display_img_count,
            album_title_color, album_title_weight, album_title_size, album_title_alignment, album_enable_lightbox,
            album_img_count_color, album_img_count_weight, album_img_count_size, album_img_count_alignment,
            album_phone_port_numb, album_phone_land_numb, album_tablet_numb, album_column_number")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getThumbnailOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("display_title, display_categoty, disable_caption, image_width, image_quality")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getLightboxOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("lightbox_border, lightbox_bg, lightbox_bg_transparency,
            display_likes, display_header, display_zoom, lightbox_display_title,
            lightbox_width, auto_resize, disable_lightbox, twitter_share,
            description_position, facebook_share, google_share, pinterest_share,
            linkedin_share, vkontakte_share, display_download, enable_alias, display_fullscreen")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getPaginationOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("pagination_type, images_per_page, pagination_bg,
                        pagination_bg_hover, pagination_border, pagination_font,
                        pagination_font_hover, pagination_radius, pagination_alignment")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
    
    protected static function getSorting($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("settings")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadResult();
        return $items;
    }
    
    protected static function getHeaderOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("header_icons_color, nav_button_bg, nav_button_icon")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }

    protected static function getCopyrightOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("disable_right_clk, disable_shortcuts, disable_dev_console")
            ->from("#__bagallery_galleries")
            ->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }
}