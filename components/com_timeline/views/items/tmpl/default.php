<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Timeline
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

?>

<div class="content-block">
	<h1><?php echo $this->params->get('title'); ?></h1>
    <p class="btm-pad"><?php echo $this->params->get('description'); ?></p>
</div>


<?php $oddEven = 'odd'; ?>
<div class="timeline">

<?php $currentYear = 0; ?>
<?php foreach ($this->items as $i => $item) : ?>

	<?php $dataTime = new DateTime($item->date); ?>
	<?php $year = $dataTime->format('Y'); ?>
	
	<?php if($year != $currentYear): ?>
		<?php $currentYear = $year; ?>
		<div class="timeline-year timeline-element">
			<?php echo $currentYear; ?>
		</div>
	<?php endif; ?>
	<div class="timeline-item <?php echo $oddEven; ?> hidden timeline-element" style="opacity: 0;">
		<div class="timeline-item-content">
			<img itemprop="image" src="<?php echo $item->image; ?>" width="390" height="250" alt="Image: <?php echo $item->title; ?>"  />
            <?php $date = $item->date_text ? $item->date_text : ($year >= 2017 ? $dataTime->format('F Y') : $dataTime->format('Y')); ?>
            
			<p class="date"><?php echo $date; ?></p>
			<p class="title"><?php echo $item->title; ?></p>
			<p><?php echo nl2br($item->description); ?></p>
		</div>
	</div>
	<?php $oddEven = $oddEven == 'odd' ? 'even' : 'odd'; ?>
<?php endforeach; ?>
	<div class="timeline-year end">
			MORE TO COME
		</div>
</div>
<script>
jQuery(document).ready(function(e) {
    
	jQuery(window).resize(function(e) {
		
		if(jQuery(window).width() <= 600){ 
			jQuery(".timeline-element").css('margin-top', '0');
			return true
		
		};
		
        var previousHeight = 0;
		var previousBgTop = 0;
		var isPreviousItem = false;
		var height = jQuery(this).height();
		
		jQuery(".timeline-element").each(function(index, element) {
			
			if(isPreviousItem && jQuery(this).hasClass('timeline-item')) {
				
				var topMargin;
				
				if(previousBgTop != 0) {
					topMargin = -(previousHeight + previousBgTop);
				} else {
					topMargin =  -Math.round(previousHeight/2);
				}
				previousBgTop = topMargin;
				jQuery(this).css('margin-top', topMargin + 'px');
				var leftOrRight = jQuery(this).hasClass('odd') ? 'left' : 'right';
				jQuery(this).css('background-position', leftOrRight + ' 0 top ' +  (Math.abs(topMargin)-15) + 'px');

			} else {
				previousBgTop = 0;
			}

			
			previousHeight = jQuery(this).height();
			isPreviousItem = jQuery(this).hasClass('timeline-item');
		});
	
    });
	
	jQuery(window).resize();
	
	jQuery(window).load(function(e) {
        jQuery(window).resize();
    });
	
	jQuery(window).scroll(function() {
		
		var bottomOfScreen = jQuery(window).scrollTop() + jQuery(window).height() - (jQuery(window).height()*.25);
		
		jQuery('.hidden').each(function(index, element) {
	
			var top = jQuery(this).offset().top;

			if(top < bottomOfScreen) {
				jQuery(this).removeClass('hidden');
				jQuery(this).fadeTo('slow', 1);
			}
		});
	});
	
	jQuery(window).scroll();
});
</script>