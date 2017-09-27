

<div class="content-block intro">
	<h2><?php echo $this->item->discover_title; ?></h2>
	<?php echo nl2br($this->item->discover_text); ?>

	<div class="row desktop">
		<a class="featured-block large"
			href="<?php echo $this->item->discover_main_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_main_image; ?>"
			width="1150" height="500" alt="Image: <?php echo $this->item->discover_main_title; ?>" />
			<h3><?php echo $this->item->discover_main_title; ?></h3>
		</a>
	</div>
	<div class="row desktop">
		<a class="featured-block"
			href="<?php echo $this->item->discover_first_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_first_image; ?>"
			width="370" height="290" alt="Image: <?php echo $this->item->discover_first_image; ?>" />
			<h3><?php echo $this->item->discover_first_title; ?></h3>
		</a> <a class="featured-block"
			href="<?php echo $this->item->discover_second_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_second_image; ?>"
			width="370" height="290" alt="Image: <?php echo $this->item->discover_second_image; ?>" />
			<h3><?php echo $this->item->discover_second_title; ?></h3>
		</a> <a class="featured-block"
			href="<?php echo $this->item->discover_third_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_third_image; ?>"
			width="370" height="290" alt="Image: <?php echo $this->item->discover_third_image; ?>" />
			<h3><?php echo $this->item->discover_third_title; ?></h3>
		</a>
	</div>
</div>


  
<div class="mobile home-swipe-container">
	<div class="flexslider" id="discover-slider">
        <ul class="slides">
        	<li>
            	<a class="featured-block"
			href="<?php echo $this->item->discover_main_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_main_image; ?>"
			width="1150" height="500" alt="Image: <?php echo $this->item->discover_main_image; ?>" />
			<h3><?php echo $this->item->discover_main_title; ?></h3>
		</a>
            </li>
            <li><a class="featured-block"
			href="<?php echo $this->item->discover_first_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_first_mobile; ?>"
			width="370" height="290" alt="Image: <?php echo $this->item->discover_first_mobile; ?>" />
			<h3><?php echo $this->item->discover_first_title; ?></h3>
		</a></li>
        <li><a class="featured-block"
			href="<?php echo $this->item->discover_second_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_second_mobile; ?>"
			width="370" height="290" alt="Image: <?php echo $this->item->discover_second_mobile; ?>" />
			<h3><?php echo $this->item->discover_second_title; ?></h3>
		</a></li>
        <li><a class="featured-block"
			href="<?php echo $this->item->discover_third_link; ?>"> <img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->discover_third_mobile; ?>"
			width="370" height="290" alt="Image: <?php echo $this->item->discover_third_mobile; ?>" />
			<h3><?php echo $this->item->discover_third_title; ?></h3>
		</a></li>

        </ul>
    </div>
</div>
  
  
<script type="text/javascript">

jQuery(document).ready(function() {
	jQuery('#discover-slider').flexslider({
		slideshow: false,
		animation: 'slide',
		before: function(){
			
		}
	});
});
</script>



<?php echo $this->peopleHtml; ?>


<div class="content-block">
	<h2><?php echo $this->item->companies_title; ?></h2>
    <!--<p class="sub"><?php echo $this->item->companies_sub; ?></p>-->
	<p><?php echo $this->item->companies_text; ?></p>
	<?php echo $this->companiesHtml; ?>
	<p class="content-block-cta">
		<a href="companies" class="btn blue">Learn more</a>
	</p>

</div>


<div class="content-block">
	<h2><?php echo $this->item->community_title; ?></h2>
	<p class="body"><?php echo nl2br($this->item->community_text); ?></p>

	<div class="image-cta-block">
		<img src="<?php echo $this->baseurl ?>/<?php echo $this->item->community_first_image; ?>" alt="Image: <?php echo $this->item->community_first_title; ?>" />
		<div class="image-cta-block-text">
			<h3><?php echo $this->item->community_first_title; ?></h3>
			<p><?php echo $this->item->community_first_text; ?></p>
			<p>
				<a href="/about-us/community" class="btn">Learn more</a>
			</p>
		</div>
		
	</div>
	<div class="image-cta-block">
		<img
			src="<?php echo $this->baseurl ?>/<?php echo $this->item->community_second_image; ?>" alt="Image: <?php echo $this->item->community_second_title; ?>" />
		<div class="image-cta-block-text">
			<h3><?php echo $this->item->community_second_title; ?></h3>
			<p><?php echo $this->item->community_second_text; ?></p>
			<p>
				<a href="/about-us/community" class="btn">Learn more</a>
			</p>
		</div>
		
	</div>
</div>



