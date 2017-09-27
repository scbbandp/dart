<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

abstract class bagalleryHelper 
{
    public static function cleanup()
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        $dir = JPATH_ROOT.'/images/bagallery/original/';
        $db = JFactory::getDbo();
        if (JFolder::exists($dir)) {
            $images = JFolder::files($dir);
            foreach ($images as $image) {
                $name = '%bagallery/original/'.$image;
                $query = $db->getQuery(true);
                $query->select('COUNT(id)')
                    ->from('`#__bagallery_items`')
                    ->where('`path` like '.$db->quote($name));
                $db->setQuery($query);
                $count = $db->loadResult();
                if ($count == 0) {
                    $name .= '%';
                    $query = $db->getQuery(true);
                    $query->select('COUNT(id)')
                        ->from('`#__bagallery_category`')
                        ->where('`settings` like '.$db->quote($name));
                    $db->setQuery($query);
                    $count = $db->loadResult();
                    if ($count == 0) {
                        JFile::delete($dir.$image);
                    }
                }
            }
        }
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

    public static function defaultCheckboxes($name, $form)
    {
        $input = $form->getField($name);
        $test = $form->getValue($name);
        if ($test == null) {
            $class = !empty($input->class) ? ' class="' . $input->class . '"' : '';
            $value = !empty($input->default) ? $input->default : '1';
            $checked = $input->checked || !empty($value) ? ' checked' : '';
            return '<input type="checkbox" name="' . $input->name . '" id="' . $input->id . '" value="'
            . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . ' />';
        } else {
            return $form->getInput($name);
        }
    }

    public static function getAccess()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, title')
            ->from('#__viewlevels')
            ->order($db->quoteName('ordering') . ' ASC')
            ->order($db->quoteName('title') . ' ASC');
        $db->setQuery($query);
        $array = $db->loadObjectList();
        $access = array();
        foreach ($array as $value) {
            $access[$value->id] = $value->title;
        }

        return $access;
    }
    
    public static function checkUpdate($version)
    {
        $version = str_replace('.', '', $version);
        $url = 'https://www.balbooa.com/updates/bagallery/com_bagallery_update.xml';
        if (ini_get('allow_url_fopen') == 1 && function_exists('file_get_contents')) {
            $curl = file_get_contents($url);
        } else if (function_exists('curl_init')) {
            $curl = self::getContentsCurl($url);
        } else {
            return true;
        }
        $xml = simplexml_load_string($curl);
        $newVersion = (string)$xml->version;
        $newVersion = str_replace('.', '', $newVersion);
        $exp = strlen($version);
        $pow = pow(10, $exp);
        $version = $version / $pow;
        $exp = strlen($newVersion);
        $pow = pow(10, $exp);
        $newVersion = $newVersion / $pow;
        if ($newVersion > $version) {
            return false;
        } else {
            return true;
        }
    }
    
    public static function getContentsCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        
        return $data;
    }

    public static function replace($str)
    {
        $str = mb_strtolower($str, 'utf-8');
        $search = array('?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '***91;',
            '***93;', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=',
            '|', '"', '\'', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й',
            'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ',
            'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я');
        $replace = array('-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
            '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n',
            'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'j', 'i', 'e', '-', 'zh', 'ts',
            'ch', 'sh', 'shch', '', 'yu', 'ya');
        $str = str_replace($search, $replace, $str);
        $str = trim($str);
        $str = preg_replace("/_{2,}/", "-", $str);

        return $str;
    }
}