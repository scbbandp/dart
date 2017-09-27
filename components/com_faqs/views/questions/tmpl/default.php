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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$category = '';

?>
<div class="content-block faqs">

	<h2>Frequently asked questions</h2>

<?php foreach ($this->items as $i => $item) : ?>


	<?php if($category != $item->category) : ?>
    	<?php $category = $item->category; ?>
        <h3 class="faq-category"><?php echo $this->escape($item->category); ?></h3>
    <?php endif; ?>

	<div class="faq" data-target="#fag-<?php echo $item->id; ?>">
		<?php //echo JRoute::_('index.php?option=com_faqs&view=question&id='.(int) $item->id); ?>
        <h4 class="faq-question"><?php echo $this->escape($item->question); ?></h4>
        <p class="faq-answer" id="fag-<?php echo $item->id; ?>"><?php echo nl2br($this->escape($item->answer)); ?></p>
    </div>

<?php endforeach; ?>

</div>
	
<script>
	jQuery(document).ready(function(e) {
        
		jQuery('.faq').click(function(e) {
			
			var target = jQuery(this).data('target');
			
            if(jQuery(this).hasClass('active')) {
				jQuery(this).removeClass('active');
				jQuery(target).slideUp();
			}else{
				jQuery(this).addClass('active');
				jQuery(target).slideDown();
			}
        });
		
    });
</script>	
