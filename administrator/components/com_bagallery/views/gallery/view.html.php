<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class bagalleryViewGallery extends JViewLegacy
{
    protected $about;
    protected $access;

    public function display ($tpl = null)
    {
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->access = bagalleryHelper::getAccess();
        $this->about = bagalleryHelper::aboutUs();
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('//fonts.googleapis.com/css?family=Roboto:300,400,500,700');
        $src = 'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css';
        $doc->addStyleSheet($src);
        if ($doc->getDirection() == 'rtl') {
            $doc->addStyleSheet(JUri::root().'administrator/components/com_bagallery/assets/css/rtl-ba-admin.css');
        }
        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        JToolBarHelper::title($isNew ? JText::_('BAGALLERY_NEW') : JText::_('BAGALLERY_EDIT'),'image');
        JToolBarHelper::apply('gallery.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('gallery.save');
        JToolBarHelper::cancel('gallery.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}