<?php 
// No direct access
defined('_JEXEC') or die; ?>

<ul class="company-list featured">
<?php foreach ($companies as  $item) : ?>
	<?php $image =  $item->module_logo ? $item->module_logo : $item->logo; ?>
	<li>
		<img src="<?php echo $image; ?>" alt="Image: <?php echo $item->name; ?>" />
	</li>
<?php endforeach; ?>
</ul>