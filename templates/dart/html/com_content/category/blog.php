<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.beez3
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');

$cparams = JComponentHelper::getParams('com_media');


if(isset($_GET['start'])) {
	
	$this->intro_items = array_merge($this->lead_items, $this->intro_items);
	$this->lead_items = array();
}


$db     = JFactory::getDbo();
$q      = $db->getQuery(true);
$q->select("YEAR(a.publish_up) as year")
->from('#__content AS a')
->group('year')
->order("year DESC");

$db->setQuery($q);
$months = $db->loadObjectList();

$categories = array(26 => 'Press Release', 27 => 'Dart News', 30 => 'Dart in the News');


foreach ($this->lead_items as $index => $item) : 
	$this->lead_items[$index]->images = json_decode($item->images);
	
	if(!$this->lead_items[$index]->images->image_fulltext){
		array_unshift($this->intro_items, $this->lead_items[$index]);
		unset($this->lead_items[$index]);
	}

 endforeach; ?>

<section class="blog">
	<div class="content-block">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
        <?php //echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>

    	<form class="news-filter" method="get">
        	<?php $currentCat = isset($_GET['category']) ? $_GET['category'] : ''; ?>
        	<select name="category">
            	<option value="">Select Category</option>
                <?php foreach($categories as $id => $cat): ?>
                
                <?php $selected = $id == $currentCat ? 'selected="selected"' : ''; ?>
                	<option value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $cat; ?></option>
                <?php endforeach; ?>
            </select>
            <?php $currentYear = isset($_GET['year']) ? $_GET['year'] : ''; ?>
            <select name="year">
            	<option value="">Select Year</option>
            	<?php foreach($months as $month): ?>
                
                <?php $selected = $month->year == $currentYear ? 'selected="selected"' : ''; ?>
                	<option value="<?php echo $month->year; ?>" <?php echo $selected; ?>><?php echo $month->year; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" class="" value="Apply" />
        </form>
    </div>
    
    <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
    <?php if ($this->params->get('show_no_articles', 1)) : ?>
    <div class="content-block">
    
    <h3 style="padding: 1em 0;">Sorry no articles found</h3>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php $leadingcount = 0; ?>
    <?php if (!empty($this->lead_items)) : ?>
    <div class="items-leading">
        <?php foreach ($this->lead_items as $item) : ?>
        <?php 
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
		//$link = 'news/' . $item->id . '-' . $item->alias;
		?> 
        <a class="leading" href="<?php echo $link; ?>">
        <?php //$item->images = json_decode($item->images); ?>
        <img src="<?php echo JURI::root(). $item->images->image_fulltext; ?>" />
        <div class="leading-text">
            <p>
                <?php 	$title = $this->escape($item->category_title); ?> 
                <?php echo $title; ?> |  <?php echo JHtml::_('date', $item->publish_up, 'd F Y'); ?> |
                <?php $author = $item->author; ?> 
                <?php $author = ($item->created_by_alias ?: $author);?>
                <?php echo $author; ?> </p>
            <h3> <?php echo $item->title; ?></h3>
        </div>
        </a>
        <?php $leadingcount++; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php
	$introcount = count($this->intro_items);
	$counter = 0;
?>
    <?php if (!empty($this->intro_items)) : ?>
    <?php foreach ($this->intro_items as $key => &$item) : ?>
    <?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
    <?php if ($rowcount == 1) : ?>
    <?php $row = $counter / $this->columns; ?>
    <div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?>">
        <?php endif; ?>
        <article class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
        	<div class="content-block article list">
            <?php
			$this->item = &$item;
			
			echo $this->loadTemplate('item');
			?>
            </div>
        </article>
        <?php $counter++; ?>
        <?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
        <span class="row-separator"></span> </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($this->link_items)) : ?>
    <?php //echo $this->loadTemplate('links'); ?>
    <?php endif; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
    
    <div class="content-block">
    <div class="pagination">
        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
        <h3><?php echo $this->pagination->getPagesCounter(); ?> </h3>
        <?php endif; ?>
        <?php echo $this->pagination->getPagesLinks(); ?> </div></div>
    <?php endif; ?>
    
    
</section>
