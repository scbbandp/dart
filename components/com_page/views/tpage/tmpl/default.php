<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Page
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$app       = JFactory::getApplication(); 
$menu = $app->getMenu();
$menuname = $menu->getActive()->title;
?>

<?php $blueBlockCheck = 'even'; ?>
<?php if($this->item->title || $this->item->intro): ?>
<div class="content-block intro">
	<?php $hclass = ''; ?>
	<?php if($this->item->title == ''){
		$this->item->title = strip_tags($this->item->intro);
		$this->item->intro = '';
		
	}
	
	if($this->item->intro==''){
		$hclass = 'statement';
	}
	
	?>
	
	<h1 class="<?php echo $hclass; ?>"><?php echo $this->item->title; ?></h1>
    
	<?php echo $this->item->intro; ?>
    
	<?php if($this->item->intro_image): ?>
	<div class="shadow"><figure itemscope itemtype="https://schema.org/ImageObject"><img class="full"
		src="<?php echo $this->baseurl ?>/<?php echo $this->item->intro_image; ?>"
		width="1150" height="500" alt="<?php echo $this->item->title; ?>" /><meta itemprop="url" content="<?php echo $this->baseurl ?>/<?php echo $this->item->intro_image; ?>">
<meta itemprop="width" content="1150">
<meta itemprop="height" content="500">
<meta itemprop="name" content="<?php echo $menuname; ?>">
</figure></div>
	<?php endif; ?>
	<?php if($this->item->module && $this->item->module_name): ?>
	<?php 
			
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer('module');
		$mod      = JModuleHelper::getModule($this->item->module, $this->item->module_name);
		echo $renderer->render($mod, array());
		
	?>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php $isStackedContent = false; ?>


<?php $this->item->content = json_decode($this->item->content); ?>
<?php if($this->item->content):?>
	<?php foreach($this->item->content as $block):?>
		
        <?php
			if($block->type != 'stack' && $isStackedContent){
				$isStackedContent = false;
				echo '</div>';
			}
		?>
		
        <?php if($block->type == 'stack'): ?>
        
        	<?php
				if(!$isStackedContent) {
					echo '<div class="content-block">';
					$isStackedContent = true;
				}
			?>
        	<div class="image-cta-block">
            	<img
				src="<?php echo $this->baseurl ?>/<?php echo $block->image; ?>" alt="<?php echo $block->title; ?>" />
                <div class="image-cta-block-text">
                    <h3><?php echo $block->title; ?></h3>
                    <?php echo $block->text; ?>
                    <?php if($block->href && $block->cta): ?>
                    <p>
                        <a href="<?php echo $block->href; ?>" class="btn"><?php echo $block->cta; ?></a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        
		<?php elseif($block->type == 'blue'): ?>
			<div class="blue-block <?php echo $blueBlockCheck; ?>">
			<?php $blueBlockCheck = $blueBlockCheck == 'odd' ? 'even' :  'odd'; ?>
			<img src="<?php echo $this->baseurl ?>/<?php echo $block->image; ?>" width="575" height="380" alt="<?php echo $block->title; ?>" />
				<div class="blue-block-text-wrapper ">
					<div class="blue-block-text">
						<h2><?php echo $block->title; ?></h2>
						<?php echo $block->text; ?>
                        <?php if($block->href && $block->cta): ?>
						<p><a class="btn" href="<?php echo $block->href; ?>"><?php echo $block->cta; ?></a></p>
                        <?php endif; ?>
					</div>
				</div>
	
			</div>
		<?php elseif($block->type == 'module'): ?>
			
			<?php 
			$document = JFactory::getDocument();
			$renderer = $document->loadRenderer('module');
			$mod      = JModuleHelper::getModule($block->title, $block->text);
			echo $renderer->render($mod, array());
			
			?>
			
		<?php elseif($block->type == 'cta'): ?>
			<div class="content-block blue">
				<h2><?php echo $block->title; ?></h2>
				<?php echo $block->text; ?>
				<p class="content-block-cta"><a class="btn" href="<?php echo $block->href; ?>"><?php echo $block->cta; ?></a></p>
			</div>
		<?php else: ?>
        
        	<?php $class = $block->title ? 'hash' : ''; ?>
        
			<div class="content-block <?php echo $class; ?>">
            	<?php if($block->title):?>
				<h2><?php echo $block->title; ?></h2>
                <?php endif; ?>
				<?php echo $block->text; ?>
                <?php if($block->image): ?>
                
                <?php if(isset($block->ns)):?>
                	<img style="margin-top: 0;" class="full"
					src="<?php echo $this->baseurl ?>/<?php echo $block->image; ?>"
					width="1150" height="500" alt="<?php echo $block->title; ?> image" />
                <?php else: ?>
                	<div class="shadow"><img class="full"
					src="<?php echo $this->baseurl ?>/<?php echo $block->image; ?>"
					width="1150" height="500" alt="<?php echo $block->title; ?> image" /></div>
                <?php endif; ?>
                
				
                <?php endif; ?>
				<?php if(isset($block->cta) && $block->cta && $block->href):?>
					<p class="content-block-cta">
                        <a href="<?php echo $block->href; ?>" class="btn blue"><?php echo $block->cta; ?></a>
                    </p>
				<?php endif; ?>
                
                <?php if(isset($block->quote) && $block->quote):?>
					<blockquote><p style="text-align:center;"><?php echo $block->quote; ?></p></blockquote>
                    <?php if(isset($block->author) && $block->author):?>
                    <cite><?php echo $block->author; ?></cite>
                    <?php endif; ?>
				<?php endif; ?>
                
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>


