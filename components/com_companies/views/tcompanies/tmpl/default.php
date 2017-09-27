<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Companies
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

?>
<div class="content-block">
	<h1 class="statement"><?php echo $this->params->get('intro'); ?></h1>
	<figure itemscope itemtype="https://schema.org/ImageObject"><img class="full"
		src="<?php echo $this->baseurl ?>/<?php echo $this->params->get('image'); ?>"
		width="1150" height="500" alt="Companies" /><meta itemprop="url" content="<?php echo $this->baseurl ?>/<?php echo $this->params->get('image'); ?>">
<meta itemprop="width" content="1150">
<meta itemprop="height" content="500">
<meta itemprop="name" content="Dart Companies">
</figure>
</div>


<div class="content-block">
	<h2><?php echo $this->items[20]['title']; ?></h2>
    
	<?php echo $this->items[20]['description']; ?>
	<div class="image-cta-blocks">
	<?php foreach ($this->items[20]['items'] as  $item) : ?>
		
		<div class="image-cta-block">
			<img
				src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>" alt="<?php echo $item->name; ?>" />
			<div class="image-cta-block-text">
				<h3><?php echo $item->name; ?></h3>
				<?php echo $item->description; ?>
                
                <?php if($item->link): ?>
                	<p><a href="<?php echo $item->link; ?>" class="btn blue">Learn more</a></p>
                <?php endif; ?>
                
                <!--
				<p>
					<a href="#<?php //echo JRoute::_('index.php?option=com_companies&view=tcompany&id='.(int) $item->id); ?>" class="btn">Learn more</a>
				</p>
                -->
			</div>
			
		</div>
	<?php endforeach; ?>
    </div>
</div>

<?php
$item = array_shift ( $this->items[29]['items'] );
?>
<?php if($item): ?>
<div class="blue-block even">
            <img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>" width="575" height="380"  alt="<?php echo $item->name; ?>">
    <div class="blue-block-text-wrapper">
        <div class="blue-block-text">
            <h2><?php echo $item->name; ?></h2>
            <?php echo $item->description; ?>
            
            <?php if($item->link): ?>
                	<p><a href="<?php echo $item->link; ?>" class="btn">Learn more</a></p>
                <?php endif; ?>
            
        </div>
    </div>
</div>
<?php endif; ?>

<div class="content-block">
	<h2><?php echo $this->items[21]['title']; ?></h2>
	<?php echo $this->items[21]['description']; ?>
	
	<ul class="company-list">
	<?php foreach ($this->items[21]['items'] as  $item) : ?>

         <?php if($item->link): ?>
         	<li class="has-link"><a class="company-btn" href="<?php echo $item->link; ?>" target="blank">
			<img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>"  alt="<?php echo $item->name; ?>" /></a></li>
         <?php else: ?>
         	<li><span class="company-btn" href="<?php echo $item->link; ?>">
			<img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>"  alt="<?php echo $item->name; ?>" /></span></li>
         <?php endif; ?>
        
	<?php endforeach; ?>
	</ul>
</div>


<div class="content-block">
	<h2><?php echo $this->items[22]['title']; ?></h2>
	<?php echo $this->items[22]['description']; ?>
	
	<ul class="company-list">
	<?php foreach ($this->items[22]['items'] as  $item) : ?>
	
		<?php if($item->link): ?>
         	<li class="has-link"><a class="company-btn" href="<?php echo $item->link; ?>" target="blank">
			<img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>"  alt="<?php echo $item->name; ?>" /></a></li>
         <?php else: ?>
         	<li><span class="company-btn" href="<?php echo $item->link; ?>">
			<img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>"  alt="<?php echo $item->name; ?>" /></span></li>
         <?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div>

<div class="content-block">
	<h2><?php echo $this->items[23]['title']; ?></h2>
	<?php echo $this->items[23]['description']; ?>
	
	<ul class="company-list">
	<?php foreach ($this->items[23]['items'] as  $item) : ?>
	
		<?php if($item->link): ?>
         	<li class="has-link"><a class="company-btn" href="<?php echo $item->link; ?>" target="blank">
			<img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>" alt="<?php echo $item->name; ?>" /></a></li>
         <?php else: ?>
         	<li><span class="company-btn" href="<?php echo $item->link; ?>">
			<img src="<?php echo $this->baseurl ?>/<?php echo $item->logo; ?>" alt="<?php echo $item->name; ?>" /></span></li>
         <?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div>