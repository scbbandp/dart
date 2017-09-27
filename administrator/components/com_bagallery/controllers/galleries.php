<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

class BagalleryControllerGalleries extends JControllerAdmin
{
	public function getModel($name = 'gallery', $prefix = 'bagalleryModel', $config = array()) 
	{
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
	}

    public function cleanup()
    {
        bagalleryHelper::cleanup();
        echo JText::_('COM_BAGALLERY_N_ITEMS_DELETED');
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

    public function duplicate()
    {
        $pks = $this->input->getVar('cid', array(), 'post', 'array');
        $model = $this->getModel();
        $model->duplicate($pks);
        $this->setMessage(JText::plural('GALLERY_DUPLICATED', count($pks)));
        $this->setRedirect('index.php?option=com_bagallery&view=galleries');
    }
    
    public function updateGallery()
    {
        $target = $_POST['target'];
        $config = JFactory::getConfig();
        $path = $config->get('tmp_path') . '/pkg_BaGallery.zip';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $file = fopen($path, "w+");
        fputs($file, $data);
        fclose($file);
        JArchive::extract($path, $config->get('tmp_path') . '/pkg_BaGallery');
        $installer = JInstaller::getInstance();
        $result = $installer->update($config->get('tmp_path') . '/pkg_BaGallery');
        JFile::delete($path);
        JFolder::delete( $config->get('tmp_path') . '/pkg_BaGallery' );
        $verion = bagalleryHelper::aboutUs();
        if ($result) {
            echo new JResponseJson($result, $verion->version);
        } else {
            echo new JResponseJson($result, '', true);
        }
        exit;
    }

    public function addLanguage()
    {
        $url = $_POST['ba_url'];
        $name = explode('/', $url);
        $name = end($name);
        $config = JFactory::getConfig();
        $path = $config->get('tmp_path') . '/'. $name;
        $name = explode('.', $name);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $file = fopen($path, "w+");
        fputs($file, $data);
        fclose($file);
        JArchive::extract($path, $config->get('tmp_path') . '/' .$name[0]);
        $installer = JInstaller::getInstance();
        $result = $installer->install($config->get('tmp_path') . '/'. $name[0]);
        JFile::delete($path);
        JFolder::delete( $config->get('tmp_path') . '/' .$name[0]);
        if ($result) {
            echo new JResponseJson($result, '');
        } else {
            echo new JResponseJson($result, '', true);
        }
        jexit();
    }
    
}