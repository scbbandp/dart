<?php
/**
* @package   BaGallery
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;
// load tooltip behavior
JHtml::_('behavior.tooltip');
$user = JFactory::getUser();
$sortFields = $this->getSortFields();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$state = $this->state->get('filter.state');
$checkUpdate = bagalleryHelper::checkUpdate($this->about->version);
$language = JFactory::getLanguage();
$language->load('com_languages', JPATH_ADMINISTRATOR);
$limit = $this->pagination->limit;
$pagLimit = array(
    5 => 5,
    10 => 10,
    15 => 15,
    20 => 20,
    25 => 25,
    30 => 30,
    50 => 50,
    100 => 100,
    0 => JText::_('JALL'),
);
if (!isset($pagLimit[$limit])) {
    $limit = 0;
}
if ($listDirn == 'asc') {
    $dirn = JText::_('JGLOBAL_ORDER_ASCENDING');
} else {
	$listDirn = 'desc';
    $dirn = JText::_('JGLOBAL_ORDER_DESCENDING');
}
if ($listOrder == 'published') {
    $order = JText::_('JSTATUS');
} else if ($listOrder == 'title') {
    $order = JText::_('JGLOBAL_TITLE');
} else {
    $order = JText::_('JGRID_HEADING_ID');
}
if ($state == '') {
    $status = JText::_('SELECT_STATUS');
} else if ($state == '1') {
    $status = JText::_('JPUBLISHED');
} else if ($state == '0') {
    $status = JText::_('JUNPUBLISHED');
} else {
    $status = JText::_('JTRASHED');
}
$uploading = new StdClass();
$uploading->const = JText::_('UPDATING');
$uploading->saving = JText::_('SAVING');
$uploading->updated = JText::_('UPDATED');
$uploading->error = JText::_('UPDATED_ERROR');
$uploading->url = JUri::root();
$uploading = json_encode($uploading);
?>
<link rel="stylesheet" href="components/com_bagallery/assets/css/ba-admin.css?<?php echo $this->about->version; ?>" type="text/css"/>
<script src="components/com_bagallery/assets/js/ba-about.js?<?php echo $this->about->version; ?>" type="text/javascript"></script>
<script type="text/javascript">
    Joomla.orderTable = function()
    {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }    
    var str = "<div class='btn-wrapper' id='toolbar-language'>";
    str += "<button class='btn btn-small'><span class='icon-star'>";
    str += "</span><?php echo $language->_('COM_LANGUAGES_HEADING_LANGUAGE'); ?></button></div>";
    str += "<div class='btn-wrapper' id='toolbar-about'>";
    str += "<button class='btn btn-small'><span class='icon-bookmark'>";
    str += "</span><?php echo JText::_('ABOUT') ?></button></div>";
    str += "<div class='btn-wrapper' id='toolbar-cleanup-images'>";
    str += "<button class='btn btn-small'><span class='icon-trash'>";
    str += "</span><?php echo JText::_('CLEANUP_IMAGES') ?></button></div>";
    jQuery('#toolbar').append(str);
</script>
<?php if (!$checkUpdate) { ?>
<div class="ba-update-message">
    <h3><?php echo JText::_('UPDATE_AVAILABLE'); ?></h3>
    <p><?php echo JText::_('UPDATE_MESSAGE'); ?></p>
    <a class="update-link ba-btn-primary">
        <span>
            <?php echo JText::_('UPDATE'); ?>
        </span>
    </a>
</div>
<div id="update-dialog" class="modal hide ba-modal-md" style="display:none">
    <div class="modal-header">
        <h3>Account login</h3>
    </div>
    <div class="modal-body">
        <div id="form-update">
            
        </div>
    </div>
</div>
<div id="message-dialog" class="ba-modal-sm modal hide" style="display:none">
    <div class="modal-body">
        <p></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<?php } ?>
<input type="hidden" value="<?php echo htmlentities($uploading); ?>" id="update-data">
<div id="ba-notification">
    <p></p>
</div>
<div id="cleanup-images-dialog" class="ba-modal-sm modal hide" style="display:none">
    <div class="modal-body">
        <h3><?php echo JText::_('CLEANUP_IMAGES') ?></h3>
        <p class="modal-text"><?php echo JText::_('REMOVE_UNUSED_IMAGES') ?></p>
        <p class="modal-text"><?php echo JText::_('CLEANUP_ATTENTION') ?></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CANCEL'); ?></a>
        <a href="#" class="ba-btn-primary red-btn" id="cleanup-images"><?php echo JText::_('DELETE'); ?></a>
    </div>
</div>
<div id="language-message-dialog" class="ba-modal-sm modal hide" style="display:none">
    <div class="modal-body">
        <p><?php echo $language->_('COM_LANGUAGES_VIEW_INSTALLED_TITLE'); ?></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<div id="language-dialog" class="modal hide ba-modal-lg" style="display:none">
    <div class="modal-header">
        <h3><?php echo $language->_('COM_LANGUAGES_INSTALL'); ?></h3>
    </div>
    <div class="modal-body">
        <iframe src="https://www.balbooa.com/demo/index.php?option=com_baupdater&view=bagallery&layout=language"></iframe>
    </div>
</div>
<form action="<?php echo JRoute::_('index.php?option=com_bagallery'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="about-dialog" class="ba-modal-md modal hide" style="display:none">
        <div class="modal-header">
            <a class="close zmdi zmdi-close" data-dismiss="modal"></a>
            <h3><?php echo JText::_('ABOUT') ?></h3>
        </div>
        <div class="modal-body">
            <div class="tab-content">
                <div id="form-about">
                    <div class="about-element">
                        <label><?php echo JText::_('WEBSITE') ?>:</label>
                        <a target="_blank" href="<?php echo $this->about->authorUrl; ?>">www.balbooa.com</a>
                    </div>
                    <div class="about-element">
                        <label><?php echo JText::_('LICENSE') ?>:</label>
                        GNU Public License version 2.0.
                    </div>
                    <div class="about-element">
                        <label><?php echo JText::_('COPYRIGHT') ?>:</label>
                        © <?php echo date('Y'); ?> Balbooa All Rights Reserved.
                    </div>
                    <div class="about-element">
                        <label><?php echo JText::_('EMAIL') ?>:</label>
                        <?php echo $this->about->authorEmail; ?>
                    </div>
                    <div class="about-element">
                        <label><?php echo JText::_('VERSION') ?>:</label>
                        <span class="update">
                            <?php echo $this->about->version; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="update-dialog" class="modal hide" style="display:none">
        <div class="modal-header">
            <h3><?php echo JText::_('ACCOUNT_LOGIN') ?></h3>
        </div>
        <div class="modal-body">
            <div id="form-update">
                
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12 gallary-view">
            <div id="filter-bar">
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                       placeholder="<?php echo JText::_('SEARCH') ?>">
                <i class="zmdi zmdi-search"></i>
                <input type="submit">
            <div class="pagination-limit">
                <div class="ba-custom-select">
                    <input readonly value="<?php echo $pagLimit[$limit]; ?>"
                           size="<?php echo strlen($limit); ?>" type="text">
                    <input type="hidden" name="limit" id="limit" 
                           onchange="Joomla.submitform()" value="<?php echo $limit; ?>">
                    <i class="zmdi zmdi-caret-down"></i>
                    <ul>
                        <?php
                        foreach ($pagLimit as $key => $lim) {
                            $str = '<li data-value="'.$key.'">';
                            if ($key == $limit) {
                                $str .= '<i class="zmdi zmdi-check"></i>';
                            }
                            $str .= $lim.'</li>';
                            echo $str;
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="sorting-direction">
                <div class="ba-custom-select">
                    <input readonly value="<?php echo $dirn; ?>"
                           size="<?php echo strlen($dirn); ?>" type="text">
                    <input type="hidden" name="directionTable" id="directionTable" 
                           onchange="Joomla.orderTable()" value="<?php echo $listDirn; ?>">
                    <i class="zmdi zmdi-caret-down"></i>
                    <ul>
                        <li data-value="asc" >
                            <?php echo $listDirn == 'asc' ? '<i class="zmdi zmdi-check"></i>' : ''; ?>
                            <?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?>
                        </li>
                        <li data-value="desc">
                            <?php echo $listDirn == 'desc' ? '<i class="zmdi zmdi-check"></i>' : ''; ?>
                            <?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sorting-table">
                <div class="ba-custom-select">
                    <input readonly value="<?php echo $order; ?>" size="<?php echo strlen($order); ?>" type="text">
                    <input type="hidden" name="sortTable" id="sortTable" 
                           onchange="Joomla.orderTable()" value="<?php echo $listOrder; ?>">
                    <i class="zmdi zmdi-caret-down"></i>
                    <ul>
                        <?php
                        foreach ($sortFields as $key => $field) {
                            $str = '<li data-value="'.$key.'">';
                            if ($key == $listOrder) {
                                $str .= '<i class="zmdi zmdi-check"></i>';
                            }
                            $str .= $field.'</li>';
                            echo $str;
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="filter-state">
                <div class="ba-custom-select">
                    <input readonly value="<?php echo $status; ?>" size="<?php echo strlen($status); ?>" type="text">
                    <input type="hidden" name="filter_state" 
                           onchange="this.form.submit()" value="<?php echo $state; ?>">
                    <i class="zmdi zmdi-caret-down"></i>
                    <ul>
                        <li data-value="">
                            <?php echo $state == '' ? '<i class="zmdi zmdi-check"></i>' : ''; ?>
                            <?php echo JText::_('SELECT_STATUS');?>
                        </li>
                        <li data-value="1" >
                            <?php echo $state == '1' ? '<i class="zmdi zmdi-check"></i>' : ''; ?>
                            <?php echo JText::_('JPUBLISHED');?>
                        </li>
                        <li data-value="0">
                            <?php echo $listDirn == '0' ? '<i class="zmdi zmdi-check"></i>' : ''; ?>
                            <?php echo JText::_('JUNPUBLISHED');?>
                        </li>
                        <li data-value="-2">
                            <?php echo $state == '-2' ? '<i class="zmdi zmdi-check"></i>' : ''; ?>
                            <?php echo JText::_('JTRASHED');?>
                        </li>
                    </ul>
                </div>                
            </div>
            </div>
            <?php if ($this->count > 0) { ?>
            <div class="main-table">
                <div class="table-header">
                    <div>
                    <label>
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        <i class="zmdi zmdi-check-circle check-all"></i>
                    </label>                        
                    </div>
                    <div>
                         <?php echo JText::_('JSTATUS'); ?>
                    </div>
                    <div>
                        <?php echo JText::_('GALLERIES'); ?>
                    </div>
                    <div>
                        <?php echo JText::_('ID'); ?>
                    </div>
                </div>
                <table class="table table-striped">
                    <tbody>
                       <?php foreach ($this->items as $i => $item) : 
                            $canChange  = $user->authorise('core.edit.state', '.galleries.' . $item->id); ?>
                        <tr>
                            <td class="select-td">
                                <label>
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                    <i class="zmdi zmdi-circle-o"></i>
                                    <i class="zmdi zmdi-check"></i>
                                </label>                                
                            </td>
                            <td class="status-td">
                                <?php echo JHtml::_('bagalleryhtml.jgrid.published', $item->published, $i, 'galleries.', $canChange); ?>
                            </td>
                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_bagallery&task=gallery.edit&id='. $item->id); ?>">
                                    <?php echo $item->title; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $item->id; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>                
            </div>
            <?php } else if (JFactory::getUser()->authorise('core.create', 'com_bagallery')) { ?>
            <div class="camera-container" onclick="Joomla.submitbutton('gallery.add')">
                <i class="zmdi zmdi-camera"></i>
                <span class="ba-tooltip ba-bottom"><?php echo JText::_('CREATE_GALLERY'); ?></span>
            </div>
            <?php } ?>
            <?php echo $this->pagination->getListFooter(); ?>
            <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>