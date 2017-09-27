<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/ 

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
 
class bagalleryModelgallery extends JModelAdmin
{
    public function getTable($type = 'Galleries', $prefix = 'GalleryTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
 
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option . '.gallery', 'gallery', array('control' => 'jform', 'load_data' => $loadData)
        );
        
        if (empty($form))
        {
            return false;
        }
 
        return $form;
    }
    
    public function save($data)
    {
        $input = JFactory::getApplication()->input;
        $data = $input->post->get('jform', array(), 'array');
        $categories = $input->post->get('cat-options', array(), 'array');
        $width = $data['image_width'];
        $height = $width;
        $quality = $data['image_quality'];
        $sorting = $data['settings'];
        $sorting = explode('-_-', $sorting);
        $all_sorting = $data['all_sorting'];
        $oldQuality = 80;
        $db = JFactory::getDBO();
        if (!empty($data['id'])) {
            $query = 'SELECT `image_quality` FROM `#__bagallery_galleries` WHERE `id`=' .$data['id'];
            $db->setQuery($query);
            $oldQuality = $db->loadResult();
        }
        if(parent::save($data)) {
            $formId = $this->getState($this->getName() . '.id');
            $dirName = JPATH_ROOT. '/images/bagallery/gallery-' .$formId. '/album/';
            $catId = array();
            $catImgs = array();
            $order = 0;
            foreach ($categories as $category) {
                if ($category != '') {
                    $category = json_decode($category);
                    $cat = explode(';', $category->settings);
                    if (!empty($cat[5])) {
                        $name = explode('/', $cat[5]);
                        $catImgs[] = 'category-'.$cat[4].'-'.end($name);;
                    } else {
                        if (!in_array('image-placeholder.jpg', $catImgs)) {
                            $catImgs[] = 'image-placeholder.jpg';
                        }
                    }
                    $table = JTable::getInstance('Category', 'GalleryTable');
                    $table->load($category->id);
                    $table->bind(array('form_id' => $formId, 'title' => $cat[0], 'orders' => $order,
                        'settings' => $category->settings, 'parent' => $category->parent,
                        'access' => $category->access));
                    $table->store();
                    $catId[] = $table->id;
                    $order++;
                }
            }
            if (jFolder::exists($dirName)) {
                $albums  = jFolder::files($dirName);
                foreach ($albums as $value) {
                    if (!in_array($value, $catImgs)) {
                        print_r($value);
                    }
                }
            }
            $query = $db->getQuery(true);
            $query->select("id")
                ->from("#__bagallery_category")
                ->where("form_id=" . $db->Quote($formId));
            $db->setQuery($query);
            $items = $db->loadColumn();
            foreach ($items as $id) {
                if (!in_array($id, $catId)) {
                    $query = $db->getQuery(true);
                    $conditions = array(
                        $db->quoteName('id'). '=' .$db->quote($id)
                    );
                    $query->delete($db->quoteName('#__bagallery_category'))
                        ->where($conditions);
                    $db->setQuery($query)
                        ->execute();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function checkName($array, $name)
    {
        if (in_array($name, $array)) {
            $name = rand(0, 999999999).'-'.$name;
            $name = $this->checkName($array, $name);
        }

        return $name;
    }

    public function getThumbnail($id)
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
    
    public function clearImageDirectory($id, $allCat, $allThumb)
    {
        
        $dir = JPATH_ROOT. '/images/bagallery/gallery-' .$id. '/thumbnail';
        if (JFolder::exists($dir)) {
            $folders = jFolder::folders($dir);
            if (empty($folders)) {
                return;
            }
            foreach ($folders as $folder) {
                if (!in_array($folder, $allCat)) {
                    jFolder::delete($dir.'/'.$folder);
                } else {
                    $files = JFolder::files($dir .'/'.$folder);
                    if (!empty($files)) {
                        foreach ($files as $file) {
                            if (!in_array($file, $allThumb[$folder])) {
                                JFile::delete($dir .'/'.$folder. '/' .$file);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function delete(&$pks)
    {
        $pks = (array) $pks;
        foreach ($pks as $i => $pk)
        {
            $id = $pk;
            if (parent::delete($pk))
            {
                $this->_db->setQuery("DELETE FROM #__bagallery_items WHERE `form_id`=". $id);
                $this->_db->execute();
                $this->_db->setQuery("DELETE FROM #__bagallery_category WHERE `form_id`=". $id);
                $this->_db->execute();
                if (jFolder::exists(JPATH_ROOT. '/images/bagallery/gallery_' .$id)) {
                    jFolder::delete(JPATH_ROOT. '/images/bagallery/gallery_' .$id);
                }
                if (jFolder::exists(JPATH_ROOT. '/images/bagallery/gallery-' .$id)) {
                    jFolder::delete(JPATH_ROOT. '/images/bagallery/gallery-' .$id);
                }
            } else {
                return false;
            }
        }
        return true;
    }
    
    public function checkObj($obj)
    {
        if (!isset($obj->title)) {
            $obj->title = '';
        }
        if (!isset($obj->short)) {
            $obj->short = '';
        }
        if (!isset($obj->alt)) {
            $obj->alt = '';
        }
        if (!isset($obj->description)) {
            $obj->description = '';
        }
        if (!isset($obj->link)) {
            $obj->link = '';
        }
        if (!isset($obj->video)) {
            $obj->video = '';
        }
        if (!isset($obj->lightboxUrl)) {
            $obj->lightboxUrl = '';
        }
        if (!isset($obj->hideInAll)) {
            $obj->hideInAll = 0;
        }
        return $obj;
    }
    
    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.gallery.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
            $id = $data->id;
            if (isset($id)) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select("id, settings, parent, access");
                $query->from("#__bagallery_category");
                $query->where("form_id=" . $id);
                $query->order("orders ASC");
                $db->setQuery($query);
                $categories = $db->loadObjectList();
                $data->gallery_category = json_encode($categories);
                $query = $db->getQuery(true);
                $query->select("settings, thumbnail_url, likes");
                $query->from("#__bagallery_items");
                $query->where("form_id=" . $id);
                $query->order("id ASC");
                $db->setQuery($query);
                $items = $db->loadObjectList();
                foreach ($items as $item) {
                    $obj = json_decode($item->settings);
                    $obj->likes = $item->likes;
                    $index = stripos($obj->url, 'images/');
                    if ($index !== 0) {
                        $obj->url = substr($obj->url, $index);
                    }
                    $obj->url = JUri::root().$obj->url;
                    $obj->thumbnail_url = $item->thumbnail_url;
                    $item->settings = json_encode($obj);
                }
                $data->gallery_items = $data->gallery_items = json_encode($items);
            }
            
        }
        return $data;
    }

    protected function getNewTitle($title)
    {
        $table = $this->getTable();
        while ($table->load(array('title' => $title)))
        {
            $title = JString::increment($title);
        }

        return $title;
    }
    
    public function duplicate(&$pks)
    {
        $db = $this->getDbo();
        foreach ($pks as $pk) {
            $table = $this->getTable();
            $table->load($pk, true);
            $table->id = 0;
            $table->title = $this->getNewTitle($table->title);
            $table->published = 0;
            $table->store();
            $id = $table->id;
            $query = $db->getQuery(true);
            $query->select("*");
            $query->from("#__bagallery_category");
            $query->where("form_id=" . $pk);
            $query->order("id ASC");
            $db->setQuery($query);
            $items = $db->loadObjectList();
            foreach ($items as $item) {
                $item->id = 0;
                $item->form_id = $id;
                $db->insertObject('#__bagallery_category', $item);
            }
            $query = $db->getQuery(true);
            $query->select("*");
            $query->from("#__bagallery_items");
            $query->where("form_id=" . $pk);
            $query->order("id ASC");
            $db->setQuery($query);
            $items = $db->loadObjectList();
            foreach ($items as $key => $item) {
                $item->id = 0;
                $item->form_id = $id;
                if (!empty($item->thumbnail_url)) {
                    $item->thumbnail_url = str_replace('gallery-'.$pk, 'gallery-'.$id, $item->thumbnail_url);
                    $item->thumbnail_url = str_replace('gallery_'.$pk, 'gallery_'.$id, $item->thumbnail_url);
                }
                $db->insertObject('#__bagallery_items', $item);
            }
            $query = $db->getQuery(true);
            $query->select("id, settings");
            $query->from("#__bagallery_items");
            $query->where("form_id=" . $id);
            $query->order("id ASC");
            $db->setQuery($query);
            $items = $db->loadObjectList();
            foreach ($items as $item) {
                $obj = $item->settings;
                $obj = json_decode($obj);
                $obj->id = $item->id;
                $obj = json_encode($obj);
                $query = "UPDATE `#__bagallery_items` SET `settings`=";
                                $query .= $db->Quote($obj). " WHERE `id`=";
                                $query .= $db->Quote($item->id);
                                $db->setQuery($query)
                                    ->execute();
            }
        }
    }
    
}