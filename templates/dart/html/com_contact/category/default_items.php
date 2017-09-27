<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.framework');

?>
<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_CONTACT_NO_CONTACTS'); ?> </p>
<?php else : ?>
	<div class="contact-wrapper">
	<?php foreach ($this->items as $i => $item) : ?>
		<div class="contact">
			<p class="department"><?php echo $item->name; ?></p>
            <?php if( $item->con_position || $item->suburb): ?>
			<p>
            <?php if( $item->suburb): ?>
            <strong><?php echo $item->suburb; ?></strong>
            <br />
            <?php endif; ?>
            <?php if( $item->con_position): ?>
			<?php echo $item->con_position; ?>
            <?php endif; ?>
            
			</p>
            <?php endif; ?>
            trackEvent = function(category, action, label)
			<p><a onclick="trackEvent('user_click', 'email', '<?php echo $item->email_to; ?>'); return false;" href="mailto:<?php echo $item->email_to; ?>"><?php echo $item->email_to; ?></a>
            
            <?php if($item->telephone): ?>
            <br />
			t: <a onclick="trackEvent('user_click', 'phone', '<?php echo str_replace(array(' ', '.'), '', $item->telephone); ?>'); return false;" href="tel:<?php echo str_replace(array(' ', '.'), '', $item->telephone); ?>"><?php echo $item->telephone; ?></a>
			<?php endif; ?>
            
            <?php if( $item->fax): ?>
            <br />
			f: <?php echo $item->fax; ?>
			<?php endif; ?>
            
			</p>
		</div>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
