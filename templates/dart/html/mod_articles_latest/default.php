<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="home-featured">
	<?php foreach ($list as $item) : ?>
    <?php $item->images = json_decode($item->images); ?>
	<a class="home-featured-block" href="<?php echo $item->link; ?>" itemprop="url">
		<img src="<?php echo JURI::root() . $item->images->image_intro ?>"
			 alt="<?php echo $item->images->image_intro_alt ?  $item->images->image_intro_alt : 'Image: ' . $item->title; ?>" />
		<h3><?php echo $item->title; ?></h3>
	</a>
    <?php endforeach; ?>
</div>

<p style="text-align:center;" class="more-news mobile"><a href="/news" class="btn blue">See more news</a></p>
