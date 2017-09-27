<?php 
// No direct access
defined('_JEXEC') or die; ?>
<?php 

$cta = $params->get('cta', "0"); 
$hero = $params->get('hero', ""); 

?>
<div class="content-block">
<h2><?php echo $params->get('title', ''); ?></h2>
<p class="body"><?php echo $params->get('text', ''); ?></p>

<?php if($hero): ?>
<img src="<?php echo JURI::root() . $hero; ?>" class="full desktop" alt="Image: <?php echo $params->get('title', ''); ?>" />
<?php endif; ?>

<?php if($cta=="1") {
	$people = array_slice($people, 0, 3);
}
?>
<ul class="featured-people desktop">
	<?php foreach ($people as  $item) : ?>
        <li><a class="load-modal" href="<?php echo JRoute::_('index.php?option=com_people&view=person&id='.(int) $item->id); ?>"><img src="<?php echo $item->image; ?>" alt="Image: <?php echo $item->name; ?>" />
            <span class="featured-person-title">
                <?php echo $item->name; ?> | <?php echo $item->company; ?>
            </span>
        </a></li>
    <?php endforeach; ?>
</ul>

<div class="mobile">
	<div class="flexslider" id="people-slider">
        <ul class="slides featured-people">
            <?php foreach ($people as  $item) : ?>
                <li><a class="load-modal" href="<?php echo JRoute::_('index.php?option=com_people&view=person&id='.(int) $item->id); ?>"><img src="<?php echo $item->mobile; ?>" alt="Image: <?php echo $item->name; ?>" />
                    <span class="featured-person-title">
                        <?php echo $item->name; ?> | <?php echo $item->company; ?>
                    </span>
                </a></li>
            <?php endforeach; ?>
        </ul>
    </div>
 </div>

<?php if($cta == "1" ): ?>
<p class="content-block-cta"><a href="<?php echo JURI::root(); ?>careers" class="btn blue">CAREERS AT DART</a></p>
<?php  endif;?>
</div>


<script type="text/javascript">

jQuery(document).ready(function() {
	
	if(typeof jQuery('#people-slider').flexslider === "function") {
	
		jQuery('#people-slider').flexslider({
			slideshow: false,
			animation: 'slide',
			before: function(){
				
			}
		});
	}
});
</script>