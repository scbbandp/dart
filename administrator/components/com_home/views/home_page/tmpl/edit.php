<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Home
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_home/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'home_page.cancel') {
			Joomla.submitform(task, document.getElementById('home_page-form'));
		}
		else {
			
			if (task != 'home_page.cancel' && document.formvalidator.isValid(document.id('home_page-form'))) {
				
				Joomla.submitform(task, document.getElementById('home_page-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_home&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="home_page-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', 'General'); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>				
				<?php echo $this->form->renderField('name'); ?>
				
					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'discover', 'Discover'); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
				<?php echo $this->form->renderField('discover_title'); ?>
				<?php echo $this->form->renderField('discover_text'); ?>
				<?php echo $this->form->renderField('discover_main_title'); ?>
				<?php echo $this->form->renderField('discover_main_image'); ?>
				<?php echo $this->form->renderField('discover_main_link'); ?>
				<?php echo $this->form->renderField('discover_first_title'); ?>
				<?php echo $this->form->renderField('discover_first_image'); ?>
                <?php echo $this->form->renderField('discover_first_mobile'); ?>
				<?php echo $this->form->renderField('discover_first_link'); ?>
				<?php echo $this->form->renderField('discover_second_title'); ?>
				<?php echo $this->form->renderField('discover_second_image'); ?>
                <?php echo $this->form->renderField('discover_second_mobile'); ?>
				<?php echo $this->form->renderField('discover_second_link'); ?>
				<?php echo $this->form->renderField('discover_third_title'); ?>
				<?php echo $this->form->renderField('discover_third_image'); ?>
                <?php echo $this->form->renderField('discover_third_mobile'); ?>
				<?php echo $this->form->renderField('discover_third_link'); ?>

				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'careers', 'Careers'); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
				
				<?php echo $this->form->renderField('careers_title'); ?>
				<?php echo $this->form->renderField('careers_text'); ?>
                <?php echo $this->form->renderField('careers_image'); ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'companies', 'Companies'); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
				
				<?php echo $this->form->renderField('companies_title'); ?>
                <?php echo $this->form->renderField('companies_sub'); ?>
				<?php echo $this->form->renderField('companies_text'); ?>

				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'community', 'Community'); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
				
				<?php echo $this->form->renderField('community_title'); ?>
				<?php echo $this->form->renderField('community_text'); ?>
				<?php echo $this->form->renderField('community_first_title'); ?>
				<?php echo $this->form->renderField('community_first_image'); ?>
				<?php echo $this->form->renderField('community_first_text'); ?>
				<?php echo $this->form->renderField('community_second_title'); ?>
				<?php echo $this->form->renderField('community_second_image'); ?>
				<?php echo $this->form->renderField('community_second_text'); ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
