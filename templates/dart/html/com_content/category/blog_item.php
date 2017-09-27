<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.beez3
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$params = &$this->item->params;
$images = json_decode($this->item->images);
$app = JFactory::getApplication();
$canEdit = $this->item->params->get('access-edit');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>


<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
<div class="system-unpublished">
<?php endif; ?>


<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
    <?php $imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
    <div class="img-fulltext-<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'UTF-8'); ?>"> <img class="full"
		<?php if ($images->image_fulltext_caption):
			echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'UTF-8') .'"';
		endif; ?>
		src="<?php echo htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8'); ?>"/> </div>
    <?php endif; ?>



<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php // to do not that elegant would be nice to group the params ?>

<?php if ($params->get('show_author') or $params->get('show_category') or $params->get('show_create_date') or $params->get('show_modify_date') or $params->get('show_publish_date') or $params->get('show_parent_category') or $params->get('show_hits')) : ?>
 <dl class="article-info">
<?php endif; ?>
<?php if ($params->get('show_parent_category') && $this->item->parent_id != 1) : ?>
		<dd class="parent-category-name">
			<?php $title = $this->escape($this->item->parent_title);
				$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('link_parent_category')) : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_category')) : ?>
		<dd class="category-name">
			<?php $title = $this->escape($this->item->category_title); ?>
	
				<?php echo  $title; ?>
	
		</dd>
<?php endif; ?>
<?php if ($params->get('show_create_date')) : ?>
		<dd class="create">
		<?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_modify_date')) : ?>
		<dd class="modified">
		<?php echo JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_publish_date')) : ?>
		<dd class="published">
		<?php echo JHtml::_('date', $this->item->publish_up, 'd F Y'); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
	<dd class="createdby">
		<?php $author = $this->item->author; ?>
		<?php $author = ($this->item->created_by_alias ?: $author);?>

			<?php echo $author; ?>

	</dd>
<?php endif; ?>
<?php if ($params->get('show_hits')) : ?>
		<dd class="hits">
		<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_author') or $params->get('show_category') or $params->get('show_create_date') or $params->get('show_modify_date') or $params->get('show_publish_date') or $params->get('show_parent_category') or $params->get('show_hits')) :?>
 	</dl>
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
	<h2>

		<?php echo $this->escape($this->item->title); ?>

	</h2>
<?php endif; ?>

<?php echo $this->item->introtext; ?>



<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
</div>
<?php endif; ?>

<p>
<?php // print_R($this->item); echo $this->item->slug; ?>
<!--
<a href="news/<?php echo $this->item->id; ?>-<?php echo $this->item->alias; ?>" class="btn blue">READ MORE</a>
-->
<?php $link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>
<a href="<?php echo $link; ?>" class="btn blue">READ MORE</a>

<?php echo $this->item->event->afterDisplayContent; ?>


