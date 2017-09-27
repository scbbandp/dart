<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class BagalleryControllerGallery extends JControllerForm
{
    public function __construct($config = array())
    {
        if(!empty($_GET)) {
            foreach($_GET as $key => $value) {
                if(strpos($key, 'amp;') === 0) {
                    $new_key = str_replace('amp;', '', $key);
                    $_GET[$new_key] = $value;
                    unset($_GET[$key]);
                }
            }
        }
        parent::__construct($config = array());
    }

    public function checkForms()
    {
        $data = $_POST['ba_data'];
        $path = JPATH_ROOT . '/components/com_baforms/helpers/baforms.php';
        if (jFile::exists($path)) {
            JLoader::register('baformsHelper', $path);
            $regex = '/\[baforms ID=+(.*?)\]/i';
            preg_match_all($regex, $data, $matches, PREG_SET_ORDER);
            if ($matches) {
                foreach ($matches as $index => $match) {
                    $form = explode(',', $match[1]);
                    $formId = $form[0];
                    if (isset($formId)) {
                        if (baformsHelper::checkForm($formId)) {
                            $doc = JFactory::getDocument();
                            $form = baformsHelper::drawHTMLPage($formId);
                            $script = baformsHelper::drawScripts($formId);
                            $str = '<script type="text/javascript" src="' .JUri::root(true). '/media/jui/js/jquery.min.js"></script>';
                            $script = str_replace($str, '', $script);
                            $form = $script.$form;
                            $pop = baformsHelper::getType($formId);
                            if ($pop['button_type'] == 'link' && $pop['display_popup'] == 1) {
                                $data = @preg_replace("|\[baforms ID=".$formId."\]|", '<a style="display:none" class="baform-replace">[forms ID='.$formId.']</a>', $data, 1);
                                $data = $data.$form;
                            } else {
                                $data = @preg_replace("|\[baforms ID=".$formId."\]|", addcslashes($form, '\\$'), $data, 1);
                            }
                        }
                    }
                }
            }
        }
        echo $data;
        exit;
    }

    public function getCategories()
    {
        $id = $_POST['gallery'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, title, settings')
            ->from('#__bagallery_category')
            ->where('`form_id` = '.$id)
            ->order('orders ASC');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        print_r(json_encode($result));
        exit;
    }

    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

    public function save($key = null, $urlVar = null)
    {
        $data = $this->input->post->get('jform', array(), 'array');
        $id = $data['id'];
        $model = $this->getModel();
        if ($model->save($data)) {
            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=gallery&tmpl=component&id='.$id, false
                ), JText::_('JLIB_APPLICATION_SAVE_SUCCESS')
            );
        }        
    }

    public function checkFileName($dir, $name)
    {
        $file = $dir.$name;
        if (JFile::exists($file)) {
            $name = rand(0, 10).'-'.$name;
            $name = $this->checkFileName($dir, $name);
        }
        return $name;
    }

    public function uploadAjax()
    {
        $dir = JPATH_ROOT. '/images/bagallery';
        $file = $_GET['file'];
        $file = JFile::makeSafe($file);
        if (!JFolder::exists($dir)) {
            jFolder::create($dir);
        }
        $dir .= '/images/';
        if (!JFolder::exists($dir)) {
            jFolder::create($dir);
        }
        $file = $this->checkFileName($dir, $file);
        $url = JUri::root(). 'images/bagallery/images';
        $ext = strtolower(JFile::getExt($file));
        if ($this->checkExt($ext)) {
            file_put_contents(
                $dir. $file,
                file_get_contents('php://input')
            );
            $image = new stdClass;
            $image->name = $file;
            $image->path = $dir. $file;
            $image->size = filesize($image->path);
            $image->width = 170;
            $image->height = 170;
            $image->min_width = 60;
            $image->min_height = 60;
            $image->url = $url. '/' .$file;
            echo json_encode($image);
        }
        exit;
    }

    public function checkExt($ext)
    {
        switch($ext) {
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
                return true;
            default:
                return false;
        }
    }

    public function getSession()
    {
        $session = JFactory::getSession();
        echo new JResponseJson($session->getState());
        exit;
    }

    public function clearOld()
    {
        $itemId = $_POST['gallery_items'];
        $allThumb = $_POST['allThumb'];
        $allCat = $_POST['allCat'];
        $formId = $_POST['ba_id'];
        $itemId = json_decode($itemId);
        $allThumb = json_decode($allThumb);
        $allCat = json_decode($allCat);
        $allThumb = get_object_vars($allThumb);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("id")
            ->from("#__bagallery_items")
            ->where("form_id=" . $db->Quote($formId));
        $db->setQuery($query);
        $items = $db->loadColumn();
        $model = $this->getModel();
        foreach ($items as $id) {
            if (!in_array($id, $itemId)) {
                $query = $db->getQuery(true);
                $thumbnail = $model->getThumbnail($id);
                $dir = JPATH_ROOT. '/'.$thumbnail;
                if (JFile::exists($dir)) {
                    JFile::delete($dir);
                }
                $conditions = array(
                    $db->quoteName('id'). '=' .$id
                );
                $query->delete($db->quoteName('#__bagallery_items'))
                    ->where($conditions);
                $db->setQuery($query)
                    ->execute();
            }
        }
        $model->clearImageDirectory($formId, $allCat, $allThumb);
        jexit();
    }

    public function saveItems()
    {
        $data = $_POST;
        $formId = $data['ba_id'];
        $items = $data['gallery_items'];
        $items = json_decode($items);
        $model = $this->getModel();
        $id = array();
        foreach ($items as $item) {
            $obj = $item;
            $obj = $model->checkObj($obj);
            $pos = stripos($obj->path, 'images/');
            if ($pos !== 0) {
                $obj->path = substr($obj->path, $pos);
            }
            $obj->path = JPATH_ROOT.'/'.$obj->path;
            $pos = stripos($obj->url, 'images/');
            if ($pos !== 0) {
                $obj->url = substr($obj->url, $pos);
            }
            if (!isset($obj->id)) {
                $table = JTable::getInstance('Items', 'GalleryTable');
                $table->bind(array('form_id' => $formId, 'category' => $obj->category,
                                   'name' => $obj->name, 'path' => $obj->path,
                                   'url' => $obj->url, 'thumbnail_url' => $obj->thumbnail_url,
                                   'title' => $obj->title, 'short' => $obj->short,
                                   'alt' => $obj->alt, 'description' => $obj->description,
                                   'link' => $obj->link, 'video' => $obj->video, 'settings' => $item,
                                   'imageId' => $obj->imageId, 'target' => $obj->target,
                                   'watermark_name' => $obj->watermark_name,
                                   'lightboxUrl' => $obj->lightboxUrl, 'hideInAll' => $obj->hideInAll));
                $table->store();
                $obj->id = $table->id;
                $db = JFactory::getDbo();
                $item = json_encode($obj);
                $query = "UPDATE `#__bagallery_items` SET `settings`=";
                $query .= $db->Quote($item). " WHERE `id`=" .$table->id;
                $db->setQuery($query)
                    ->execute();
                $id[] = $table->id;
            } else {
                $db = JFactory::getDbo();
                $query = "UPDATE `#__bagallery_items` SET `form_id`=";
                $query .= $db->Quote($formId). ", `category`=";
                $query .= $db->Quote($obj->category). ", `name`=";
                $query .= $db->Quote($obj->name). ", `path`=";
                $query .= $db->Quote($obj->path). ", `url`=";
                $query .= $db->Quote($obj->url). ", `thumbnail_url`=";
                $query .= $db->Quote($obj->thumbnail_url). ", `title`=";
                $query .= $db->Quote($obj->title). ", `short`=";
                $query .= $db->Quote($obj->short). ", `alt`=";
                $query .= $db->Quote($obj->alt). ", `description`=";
                $query .= $db->Quote($obj->description). ", `link`=";
                $query .= $db->Quote($obj->link). ", `video`=";
                $query .= $db->Quote($obj->video). ", `settings`=";
                $query .= $db->Quote(json_encode($obj)). ", `target`=";
                $query .= $db->Quote($obj->target). ", `lightboxUrl` =";
                $query .= $db->Quote($obj->lightboxUrl). ", `watermark_name` =";
                $query .= $db->Quote($obj->watermark_name).", `hideInAll` =";
                $query .= $db->Quote($obj->hideInAll)." WHERE `id`=";
                $query .= $db->Quote($obj->id);
                $db->setQuery($query)
                    ->execute();
            }
        }
        $id = json_encode($id);
        echo new JResponseJson(true, $id);
        jexit();
    }

    public function emptyAlbums()
    {
        $id = $_POST['ba_id'];
        if (!empty($id)) {
            $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$id. '/album';
            if (jFolder::exists($dir)) {
                jFolder::delete($dir);
            }
        }
    }

    public function emptyThumbnails()
    {
        $id = $_POST['ba_id'];
        if (!empty($id)) {
            $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$id. '/thumbnail';
            if (jFolder::exists($dir)) {
                jFolder::delete($dir);
            }
        }
    }

    public function removeWatermark()
    {
        $id = $_POST['ba_id'];
        if (!empty($id)) {
            $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$id. '/watermark';
            if (jFolder::exists($dir)) {
                jFolder::delete($dir);
            }
        }
    }

    public function download()
    {
        $id = $_GET['id'];
        $gallery = $_GET['gallery'];
        $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$gallery. '/watermark/';
        if (isset($_GET['quality'])) {
            $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$gallery. '/compression/';
        }
        $name = $_GET['name'];
        $file = $dir.$name;
        if (!empty($name) && file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public function compressionImage()
    {
        $root = JPATH_ROOT;
        if ($root == '/') {
            $root = '';
        }
        $image = $root.'/'.$_GET['image'];
        $id = $_GET['id'];
        $name = $_GET['name'];
        $gallery = $_GET['gallery'];
        $watermark = $_GET['watermark'];
        $position = $_GET['position'];
        $opacity = $_GET['opacity'];
        $scale = $_GET['scale'];
        $ext = strtolower(JFile::getExt($image));
        $dir = $root. '/images/bagallery/gallery-' .$gallery;
        $dir .= '/compression/';
        if (!JFolder::exists($dir)) {
            jFolder::create($dir);
        }
        $file = $dir.$name;
        if (empty($name) || !JFile::exists($dir.$name)) {
            $width = $height = $_GET['width'];
            $quality = $_GET['quality'];
            if (empty($name)) {
                $name = jFile::getName($image);
                if (JFile::exists($dir.$name)) {
                    $name = rand(0, 999999999).'-'.$name;
                }
            }
            $imageCreate = $this->imageCreate($ext);
            $imageSave = $this->imageSave($ext);
            $orig = $imageCreate($image);
            $orig = $this->checkExif($image, $orig, $ext);
            $origWidth = imagesx($orig);
            $origHeight = imagesy($orig);
            $ratio = $origWidth / $origHeight;
            if ($origWidth > $origHeight) {
                if ($origWidth >= $width) {
                    $height = round($width / $ratio);
                } else {
                    $width = $origWidth;
                    $height = $origHeight;
                }
            } else {
                if ($origHeight >= $height) {
                    $width = round($ratio * $height);
                } else {
                    $width = $origWidth;
                    $height = $origHeight;
                }
            }
            $sx = 0;
            $sy = 0;
            $w = $origWidth;
            $h = $origHeight;
            $out = imagecreatetruecolor($width, $height);
            if ($ext == 'png') {
                imagealphablending($out, false);
                imagesavealpha($out, true);
                $transparent = imagecolorallocatealpha($out, 255, 255, 255, 127);
                imagefilledrectangle($out, 0, 0, $width, $height, $transparent);
            }
            imagecopyresampled($out, $orig, 0, 0, $sx, $sy, $width, $height, $w, $h);
            if (!empty($watermark)) {
                $ex = strtolower(JFile::getExt($watermark));
                $imageCreate = $this->imageCreate($ex);
                $stamp = $imageCreate($watermark);
                $marge_right = 10;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);
                $xx = $width;
                $yy = $height;
                if ($scale == 1) {
                    $ratio = $sy / $sx;
                    $width = floor( imagesx($im) * 0.1)  - $marge_right * 2;
                    $height = $width*$ratio;
                    $stamp = $this->resizeImage($stamp, $width, $height);
                    $sx = imagesx($stamp);
                    $sy = imagesy($stamp);
                }
                switch ($position) {
                    case 'top_left':
                        $x = $marge_right;
                        $y = $marge_right;
                        break;
                    case 'top_right':
                        $x = $xx - $sx - $marge_right;
                        $y = $marge_right;
                        break;
                    case 'bottm_left':
                        $x = $marge_right;
                        $y = $yy - $sy - $marge_right;
                        break;
                    case 'bottom_right':
                        $x = $xx - $sx - $marge_right;
                        $y = $yy - $sy - $marge_right;
                        break;
                    case 'center':
                        $x = $xx / 2 - $sx / 2;
                        $y = $yy / 2 - $sy / 2;
                }
                $this->imagecopymerge_alpha($out, $stamp, $x, $y, 0, 0, $sx, $sy, $opacity);
            }
            $file = $dir.$name;
            if ($ext == 'png') {
                $quality = round($quality / 11.111111111111);
                $imageSave($out, $file, $quality);
            } else if ($ext == 'gif') {
                $imageSave($out, $file);
            } else {
                $imageSave($out, $file, $quality);
            }
            $this->setWatermarkName($id, $name);
        }
        echo str_replace($root.'/', JUri::root(), $file);
        exit;
    }

    public function addWatermark()
    {
        $img = JPATH_ROOT.'/'.$_GET['image'];
        $watermark = $_GET['watermark'];
        $position = $_GET['position'];
        $id = $_GET['id'];
        $opacity = $_GET['opacity'];
        $scale = $_GET['scale'];
        $name = $_GET['name'];
        $gallery = $_GET['gallery'];
        $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$gallery;
        $ext = strtolower(JFile::getExt($img));
        if (!JFolder::exists($dir)) {
            jFolder::create($dir);
        }
        $dir .= '/watermark/';
        if (!JFolder::exists($dir)) {
            jFolder::create($dir);
        }
        if (empty($name) || !JFile::exists($dir.$name)) {
            $imageCreate = $this->imageCreate($ext);
            $imageSave = $this->imageSave($ext);
            if (empty($name)) {
                $name = jFile::getName($img);
                if (JFile::exists($dir.$name)) {
                    $name = rand(0, 999999999).'-'.$name;
                }
            }
            $im = $imageCreate($img);
            $im = $this->checkExif($img, $im, $ext);
            $ex = strtolower(JFile::getExt($watermark));
            $imageCreate = $this->imageCreate($ex);
            $stamp = $imageCreate($watermark);
            $marge_right = 10;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
            $xx = imagesx($im);
            $yy = imagesy($im);
            if ($scale == 1) {
                $ratio = $sy / $sx;
                $width = floor( imagesx($im) * 0.1)  - $marge_right * 2;
                $height = $width*$ratio;
                $stamp = $this->resizeImage($stamp, $width, $height);
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);
            }
            switch ($position) {
                case 'top_left':
                    $x = $marge_right;
                    $y = $marge_right;
                    break;
                case 'top_right':
                    $x = $xx - $sx - $marge_right;
                    $y = $marge_right;
                    break;
                case 'bottm_left':
                    $x = $marge_right;
                    $y = $yy - $sy - $marge_right;
                    break;
                case 'bottom_right':
                    $x = $xx - $sx - $marge_right;
                    $y = $yy - $sy - $marge_right;
                    break;
                case 'center':
                    $x = $xx / 2 - $sx / 2;
                    $y = $yy / 2 - $sy / 2;
                    break;
            }
            $this->imagecopymerge_alpha($im, $stamp, $x, $y, 0, 0, $sx, $sy, $opacity);
            $file = $dir.$name;
            if ($ext == 'png') {
                $imageSave($im, $file, 9);
            } else if ($ext == 'gif') {
                $imageSave($im, $file);
            } else {
                $imageSave($im, $file, 100);
            }
            $this->setWatermarkName($id, $name);
            imagedestroy($im);
            imagedestroy($stamp);
        } else {
            $file = $dir.$name;
        }
        echo str_replace(JPATH_ROOT.'/', JUri::root(), $file);
        exit;
    }

    public function setWatermarkName($id, $name)
    {
        $db = JFactory::getDbo();
        $obj = new stdClass();
        $obj->id = $id;
        $obj->watermark_name = $name;
        $db->updateObject('#__bagallery_items', $obj, 'id');        
    }

    public function resizeImage($image, $width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagealphablending($new_image, true);
        $trans_layer_overlay = imagecolorallocatealpha($new_image, 0, 0, 200, 127);
        imagefill($new_image, 0, 0, $trans_layer_overlay);
        imagesavealpha($new_image, true);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        imagedestroy($image);

        return $new_image;
    }

    public function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity)
    {
        if (!isset($opacity)) {
            return false;
        }
        $opacity /= 100;
        $w = imagesx($src_im);
        $h = imagesy($src_im);
        imagealphablending( $src_im, false );
        $minalpha = 127;
        for ($x = 0; $x < $w; $x++)
            for ($y = 0; $y < $h; $y++){
                $alpha = (imagecolorat($src_im, $x, $y) >> 24 ) & 0xFF;
                if ($alpha < $minalpha){
                    $minalpha = $alpha;
                }
            }
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $colorxy = imagecolorat( $src_im, $x, $y );
                $alpha = ( $colorxy >> 24 ) & 0xFF;
                if ($minalpha !== 127){
                    $alpha = 127 + 127 * $opacity * ( $alpha - 127 ) / ( 127 - $minalpha );
                } else {
                    $alpha += 127 * $opacity;
                }
                $alphacolorxy = imagecolorallocatealpha($dst_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha );
                if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)){
                    return false;
                }
            }
        }
        imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
    }

    public function showAdminImage()
    {
        $dir = $_GET['image'];
        $pos = strpos($dir, '/images/');
        $dir = substr($dir, $pos);
        $dir = JPATH_ROOT.$dir;
        $ext = strtolower(JFile::getExt($dir));
        $imageCreate = $this->imageCreate($ext);
        $imageSave = $this->imageSave($ext);
        Header("Content-type: image/".$ext);
        $offset = 60 * 60 * 24 * 90;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        if (!$im = $imageCreate($dir)) {
            $f = fopen($dir, "r");
            fpassthru($f);
        } else {
            $width = imagesx($im);
            $height = imagesy($im);
            $ratio = $width / $height;
            if ($width > $height) {
                $w = 100;
                $h = 100 / $ratio;
            } else {
                $h = 100;
                $w = 100 * $ratio;
            }
            $out = imagecreatetruecolor($w, $h);
            if ($ext == 'png') {
                imagealphablending($out, false);
                imagesavealpha($out, true);
                $transparent = imagecolorallocatealpha($out, 255, 255, 255, 127);
                imagefilledrectangle($out, 0, 0, $w, $h, $transparent);
            }
            imagecopyresampled($out, $im, 0, 0, 0, 0, $w, $h, $width, $height);
            $imageSave($out);
            imagedestroy($im);
            imagedestroy($out);
        }
        exit;
    }
    
    public function imageCreate($type) {
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
    
    public function imageSave($type) {
        switch ($type) {
            case 'jpeg':
                $imageSave = 'imagejpeg';
                break;

            case 'png':
                $imageSave = 'imagepng';
                break;

            case 'gif':
                $imageSave = 'imagegif';
                break;

            default:
                $imageSave = 'imagejpeg';
        }

        return $imageSave;
    }

    public function showCatImage()
    {
        $id = $_GET['id'];
        $image = JPATH_ROOT.'/'.$_GET['image'];
        $width = $height = $_GET['width'];
        $quality = $_GET['quality'];
        $category = $_GET['category'];
        $gallery = $_GET['gallery'];
        $layout = $_GET['layout'];
        $index = $_GET['index'] * 1 + 1;
        $width2 = array();
        $height2 = array();
        if ($layout == 'masonry') {
            for ($i = 0; $i < 100; $i++) {
                $height2[] = 4 * $i + 2;
            }
        } else if ($layout == 'metro') {
            for ($i = 0; $i < 100; $i++) {
                $height2[] = 10 * $i + 2;
                $height2[] = 10 * $i + 5;
                $width2[] = 10 * $i + 4;
                $width2[] = 10 * $i + 7;
                $height2[] = 10 * $i + 7;
            }
        } else if ($layout == 'square') {
            for ($i = 0; $i < 100; $i++) {
                $height2[] = 5 * $i + 5;
                $width2[] = 5 * $i + 5;
            }
        }
        if (in_array($index, $height2)) {
            $height = $height * 2;
        }
        if (in_array($index, $width2)) {
            $width = $width * 2;
        }
        $ext = strtolower(JFile::getExt($image));
        $name = JFile::getName($image);
        $file = JPATH_ROOT. '/images/bagallery/gallery-'.$gallery.'/album/';
        if ($name == 'image-placeholder.jpg') {
            $file .= $name;
        } else {
            $file .= $category.'-'.$name;
        }
        if (!JFile::exists($file)) {
            $dir = JPATH_ROOT. '/images/bagallery/';
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $dir .= 'gallery-'.$gallery;
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $dir .= '/album';
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $imageCreate = $this->imageCreate($ext);
            $imageSave = $this->imageSave($ext);
            $orig = $imageCreate($image);
            $orig = $this->checkExif($image, $orig, $ext);
            $origWidth = imagesx($orig);
            $origHeight = imagesy($orig);
            $sx = 0;
            $sy = 0;
            $w = $origWidth;
            $h = $origHeight;
            $ratio = $origWidth / $origHeight;
            if ($layout == 'random') {
                if ($origWidth > $origHeight) {
                    $height = round($width / $ratio);
                } else {
                    $width = round($ratio * $height);
                }
            } else if ($layout == 'justified') {
                $width = round($ratio * $height);
            } else {
                if ($origHeight / $origWidth > $height / $width) {
                    $h = round(($height * $origWidth) / $width);
                    $sy = round(($origHeight - $h) / 3);
                } else {
                    $w = round(($origHeight * $width) / $height);
                    $sx = round(($origWidth - $w) / 2);
                }
            }
            $out = imagecreatetruecolor($width, $height);
            if ($ext == 'png') {
                imagealphablending($out, false);
                imagesavealpha($out, true);
                $transparent = imagecolorallocatealpha($out, 255, 255, 255, 127);
                imagefilledrectangle($out, 0, 0, $width, $height, $transparent);
            }            
            imagecopyresampled($out, $orig, 0, 0, $sx, $sy, $width, $height, $w, $h);
            if ($ext == 'png') {
                $quality = round($quality / 11.111111111111);
                $imageSave($out, $file, $quality);
            } else if ($ext == 'gif') {
                $imageSave($out, $file);
            } else {
                $imageSave($out, $file, $quality);
            }
        }
        Header("Content-type: image/".$ext);
        $f = fopen($file, 'r');
        fpassthru($f);
        fclose($f);
        exit;
    }

    public function showImage()
    {
        $id = $_GET['id'];
        $thumbnail = bagalleryHelper::getThumbnail($id);
        $root = JPATH_ROOT;
        if ($root == '/') {
            $root = '';
        }
        $image = $root.'/'.$_GET['image'];
        $ext = strtolower(JFile::getExt($image));
        $file = $root.'/'.$thumbnail;
        $layout = $_GET['gallery_layout'];
        $width = $_GET['width'];
        $height = $_GET['height'];
        if (empty($thumbnail) || strlen($thumbnail) < 10 || !JFile::exists($file)) {
            $quality = $_GET['quality'];
            if (!empty($thumbnail)) {
                $name = explode('/', $thumbnail);
            } else {
                $name = explode('/', $image);
            }            
            $name = end($name);        
            $gallery = $_GET['gallery'];
            $category = $_GET['category'];
            $dir = $root. '/images/bagallery/';
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $dir .= 'gallery-'.$gallery;
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $dir .= '/thumbnail/';
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $dir .= $category;
            if (!JFolder::exists($dir)) {
                jFolder::create($dir);
            }
            $imageCreate = $this->imageCreate($ext);
            $imageSave = $this->imageSave($ext);
            $orig = $imageCreate($image);
            $orig = $this->checkExif($image, $orig, $ext);
            $origWidth = imagesx($orig);
            $origHeight = imagesy($orig);
            $sx = 0;
            $sy = 0;
            $w = $origWidth;
            $h = $origHeight;
            $ratio = $origWidth / $origHeight;
            if ($layout == 'random') {
                if ($origWidth > $origHeight) {
                    $height = round($width / $ratio);
                } else {
                    $width = round($ratio * $height);
                }
            } else if ($layout == 'justified') {
                $width = round($ratio * $height);
            } else {
                if ($origHeight / $origWidth > $height / $width) {
                    $h = round(($height * $origWidth) / $width);
                    $sy = round(($origHeight - $h) / 3);
                } else {
                    $w = round(($origHeight * $width) / $height);
                    $sx = round(($origWidth - $w) / 2);
                }
            }
            $out = imagecreatetruecolor($width, $height);
            if ($ext == 'png') {
                imagealphablending($out, false);
                imagesavealpha($out, true);
                $transparent = imagecolorallocatealpha($out, 255, 255, 255, 127);
                imagefilledrectangle($out, 0, 0, $width, $height, $transparent);
            }            
            imagecopyresampled($out, $orig, 0, 0, $sx, $sy, $width, $height, $w, $h);
            $file = $dir. '/' .$name;
            if ($ext == 'png') {
                $quality = round($quality / 11.111111111111);
                $imageSave($out, $file, $quality);
            } else if ($ext == 'gif') {
                $imageSave($out, $file);
            } else {
                $imageSave($out, $file, $quality);
            }
            $this->setThumbnail($id, $file);
        }
        Header("Content-type: image/".$ext);
        $f = fopen($file, 'r');
        fpassthru($f);
        fclose($f);
        exit;
    }

    public function checkExif($src, $img, $ext)
    {
        if (($ext == 'jpg' || $ext == 'jpeg') && function_exists('exif_read_data')) {
            $exif = exif_read_data($src);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $img = imagerotate($img, 180, 0);
                        break;
                    case 6:
                        $img = imagerotate($img, -90, 0);
                        break;
                    case 8:
                        $img = imagerotate($img, 90, 0);
                        break;
                }
            }
        }

        return $img;
    }

    public function setThumbnail($id, $image)
    {
        $root = JPATH_ROOT;
        if ($root == '/') {
            $root = '';
        }
        $db = JFactory::getDbo();
        $obj = new stdClass();
        $obj->id = $id;
        $obj->thumbnail_url = str_replace($root, '', $image);
        $db->updateObject('#__bagallery_items', $obj, 'id');
    }
    
    public function likeIt()
    {
        $id = $this->input->post->get('image_id', '', 'INT');
        if (empty($id)) {
            $id = $_GET['image_id'];
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from("#__bagallery_users")
            ->where('image_id=' .$id)
            ->where('ip=' .$db->Quote($ip));
        $db->setQuery($query);
        $user = $db->loadResult();
        $query = "UPDATE `#__bagallery_items` ";
        if (!$user) {
            $query .= "SET `likes`=`likes`+1 ";
        } else {
            $query .= "SET `likes`=`likes`-1 ";
        }
        $query .= "WHERE `id`=" .$db->Quote($id);
        $db->setQuery($query)
            ->execute();
        if ($user) {
            $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('id'). '=' .$user
            );
            $query->delete($db->quoteName('#__bagallery_users'))
                ->where($conditions);
            $db->setQuery($query)
                ->execute();
        } else {
            $query = $db->getQuery(true);
            $columns = array(
                'image_id',
                'ip',
            );
            $values = array(
                $db->quote($id),
                $db->quote($ip)
            );
            $query->insert($db->quoteName('#__bagallery_users'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query)
                ->execute();
        }
        echo new JResponseJson($this->getLikes($id));
        jexit();
    }
    
    public function getLikes($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('likes')
            ->from('#__bagallery_items')
            ->where('id=' .$id);
        $db->setQuery($query);
        return $db->loadResult();
    }
}