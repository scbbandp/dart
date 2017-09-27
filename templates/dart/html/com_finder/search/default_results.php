<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>


<?php // Display the 'no results' message and exit the template. ?>
<?php if ($this->total == 0) : ?>
	<div id="search-result-empty">
		<?php $multilang = JFactory::getApplication()->getLanguageFilter() ? '_MULTILANG' : ''; ?>
		<p><?php echo JText::sprintf('COM_FINDER_SEARCH_NO_RESULTS_BODY' . $multilang, $this->escape($this->query->input)); ?></p>
	</div>

	<?php // Exit this template. ?>
	<?php return; ?>
<?php endif; ?>

<?php // Activate the highlighter if enabled. ?>
<?php if (!empty($this->query->highlight) && $this->params->get('highlight_terms', 1)) : ?>
	<?php JHtml::_('behavior.highlighter', $this->query->highlight); ?>
<?php endif; ?>

<?php // Display a list of results ?>
<br id="highlighter-start" />
<ul class="search-results<?php echo $this->pageclass_sfx; ?> list-striped">
	<?php $this->baseUrl = JUri::getInstance()->toString(array('scheme', 'host', 'port')); ?>

	<?php foreach ($this->results as $result) : ?>
		<?php $this->result = &$result; ?>
		<?php $layout = $this->getLayoutFile($this->result->layout); ?>
		<?php echo $this->loadTemplate($layout); ?>
	<?php endforeach; ?>
</ul>
<br id="highlighter-end" />

<?php // Display the pagination ?>
<div class="search-pagination">
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<div class="search-pages-counter">
		<?php
			// Prepare the pagination string.  Results X - Y of Z
			$start = (int) $this->pagination->get('limitstart') + 1;
			$total = (int) $this->pagination->get('total');
			$limit = (int) $this->pagination->get('limit') * $this->pagination->get('pages.current');
			$limit = (int) ($limit > $total ? $total : $limit);

			echo JText::sprintf('COM_FINDER_SEARCH_RESULTS_OF', $start, $limit, $total);
		?>
	</div>
</div>
