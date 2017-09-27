<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class bagalleryModelUploader extends JModelLegacy
{
    protected $_parent;
    protected $_folders;
    
    public function getParent()
    {
        return $this->_parent;
    }

    public function getFolderList()
    {
        $dir = $this->_folders;
        $folders = JFolder::folders($dir);
        $items = array();
        foreach ($folders as $folder) {
            $fold = new stdClass;
            $fold->path = $dir. '/' .$folder;
            $fold->name = $folder;
            $this->_folders = $dir. '/' .$folder;
            $fold->childs = $this->getFolderList();
            $items[] = $fold;
        }

        return $items;
    }

    public function getBreadcrump()
    {
        $dir = JPATH_ROOT. '/images';
        $this->_folders = $dir;
        $input = JFactory::getApplication()->input;
        $name = $input->get('folder', '', 'string');
        if ($name == "undefined") {
            $name = '';
        }
        if (!empty($name)) {
            $dir = $name;
        }
        $fold = '';
        if ($dir != JPATH_ROOT. '/images') {
            $fold = new stdClass;
            $pat = JPATH_ROOT;
            $prepath = str_replace(JPATH_ROOT. '/', '', $dir);
            $prepath = explode('/', $prepath);
            $fold->curr = $prepath[count($prepath)-1];
            unset($prepath[count($prepath)-1]);
            for ($i =0; $i<count($prepath); $i++) {
                $pat .= '/' .$prepath[$i];
                $path[] = $pat;
            }
            $fold->par = $prepath;
            $fold->path = $path;
            $fold->name = "../";
        }
        return $fold;
    }
    
    public function getFolders()
    {
        $dir = JPATH_ROOT. '/images';
        $this->_folders = $dir;
        $input = JFactory::getApplication()->input;
        $name = $input->get('folder', '', 'string');
        if ($name == "undefined") {
            $name = '';
        }
        if (!empty($name)) {
            $dir = $name;
        }
        $items = array();
        if ($dir != JPATH_ROOT. '/images') {
            $this->_parent = $dir;
        }
        $folders = JFolder::folders($dir);
        if (!empty($folders)) {
            foreach ($folders as $folder) {
                
                $fold = new stdClass;
                $fold->path = $dir. '/' .$folder;
                $fold->name = $folder;
                $items[] = $fold;
            }
        }
        $folders = $items;
        return $folders;
    }

    public function getImages()
    {
        $dir = JPATH_ROOT. '/images';
        $url = JUri::root(). 'images';
        $input = JFactory::getApplication()->input;
        $name = $input->get('folder', '', 'string');
        if ($name == "undefined") {
            $name = '';
        }
        if (!empty($name)) {
            $dir = $name;
            $curent = str_replace(JPATH_ROOT. '/images', '', $dir);
            $url .= $curent;
        }
        $files	= JFolder::files($dir);
        $images = array();
        if (!empty($files)) {
            foreach ($files as $file) {
                $ext = strtolower(JFile::getExt($file));
                $flag = $this->checkExt($ext);
                if ($flag) {
                    $image = new stdClass;
                    $image->name = $file;
                    $image->path = $dir. '/' .$file;
                    $image->size = filesize($image->path);
                    $image->width = 170;
                    $image->height = 170;
                    $image->min_width = 60;
                    $image->min_height = 60;
                    $image->url = $url. '/' .$file;
                    $images[] = $image;
                }
            }
        }
        return $images;
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
}
