<?php 
// No direct access
defined('_JEXEC') or die; ?>

<div class="content-block more-links">
	<a href="<?php echo $params->get('link1'); ?>"><img src="<?php echo JURI::root() . $params->get('image1'); ?>" /><span><?php echo $params->get('title1'); ?></span></a>
    <a href="<?php echo $params->get('link2'); ?>"><img src="<?php echo JURI::root() . $params->get('image2'); ?>" /><span><?php echo $params->get('title2'); ?></span></a>
</div>
