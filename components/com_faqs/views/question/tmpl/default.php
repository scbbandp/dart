<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Faqs
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_FAQS_FORM_LBL_QUESTION_QUESTION'); ?></th>
			<td><?php echo $this->item->question; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FAQS_FORM_LBL_QUESTION_ANSWER'); ?></th>
			<td><?php echo nl2br($this->item->answer); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_FAQS_FORM_LBL_QUESTION_CATEGORY'); ?></th>
			<td><?php echo $this->item->category_title; ?></td>
		</tr>

	</table>

</div>

