<?php 
// No direct access
defined('_JEXEC') or die; ?>

<?php
$document = JFactory::getDocument ();
$document->addStyleSheet ( JURI::root(). '/components/com_home/js/slider/css/flexslider.css' );
?>
<script defer
	src="<?php echo JURI::root() ?>/components/com_home/js/slider/js/jquery.flexslider.js"></script>

<style>

	.flexslider-guide {
		display:block;
		width: 100%;
		height: 2px;
		background-color: #EFF0F2;
		overflow:hidden;
	}
	.flexslider-guide-progress {
		display:block;
		height: 4px;
		float:left;
		background-color: #00539c;
	}
</style>



<div class="home-hero">
	<section class="slider">
		<div class="flexslider" id="hero-slider">
			<ul class="slides" id="hero-slides">
				<?php foreach ($heros as  $item) : ?>

				<li><a href="<?php echo JURI::root() ?><?php echo $item->link; ?>" style="display: block; overflow: hidden;"> <img
						src="<?php echo $item->image; ?>"
						width="1150" height="510" alt="<?php echo $item->title; ?>" />
						<div class="slide-copy">
							<h2><?php echo $item->title; ?></h2>
							<p><?php echo $item->sub; ?></p>
						</div>
				</a></li>
				<?php endforeach; ?>
			</ul>
            
		</div>
        
	</section>
    
</div>


<script type="text/javascript">

	var progress = 0;
	var interval;
	function updateProgress(){
		
		progress+=.2;
		
		if(progress>=100){
			progress = 0;
			jQuery('#hero-slider').flexslider("next");
		}
		
		jQuery("div.flexslider-guide-progress").css('width', '' + progress + '%');
		
	}
	
	jQuery(document).ready(function() {
		jQuery('#hero-slider').flexslider({
			animation: "fade",
			slideshow: false,
			animationSpeed: 1000,
			before: function(){
				// check if user has interacted with the slider
				
				progress = 0;
				
				/*
				if(progress != 0) {
					
					progress = 0;
					jQuery("div.flexslider-guide-progress").css('width', '' + progress + '%');
					clearInterval(interval);
				}*/
				
			}
		});
		
		jQuery('#hero-slider').flexslider("stop")
		
		interval = setInterval(function(){ updateProgress(); }, 20);
		jQuery("#hero-slides").after('<div class="flexslider-guide"><div class="flexslider-guide-progress"></div></div>');
	  
    });
  </script>
  
  

  