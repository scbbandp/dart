<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Leadership
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>

<div class="content-block leadership-info">
	<h1><?php echo $this->params->get('title'); ?></h1>
	<p><?php echo nl2br($this->params->get('description')); ?></p>
</div>

<div class="content-block no-top-margin mobile">
	<div class="leadership">
    	
		<?php foreach ($this->items as $item) : ?>
        
            <a class="leadership-member" href="<?php echo JRoute::_('index.php?option=com_leadership&view=member&id='.(int) $item->id); ?>">
            <img src="<?php echo $item->tile; ?>" alt="<?php echo $item->name; ?>" />
                <div class="leadership-member-info">
                    <p class="name"><?php echo $item->name; ?></p>
                    <p class="title"><?php echo $item->title; ?></p>
                    <p class="company"><?php echo $item->company; ?></p>
                </div>
            </a>
        <?php endforeach; ?>
	</div>
</div>

<?php $ceo = array_splice ($this->items, 0, 1); $ceo = $ceo[0]; ?>
<div class="blue-block desktop">
    <img src="<?php echo $ceo->featured; ?>" width="575" height="380"  alt="<?php echo $ceo->name; ?>">
    <div class="blue-block-text-wrapper">
        <div class="blue-block-text">
            <h2><?php echo $ceo->name; ?>
            <span style="display: block; clear:both; font-size: .6em;"><?php echo $ceo->title; ?></span>
            </h2>
            <p class="title">
Mark VanDevelde has overall responsibility for all Ken Dart’s companies in the Cayman Islands. For more than 20 years, he has managed the portfolio’s investments and guided the group’s business operations.
           </p>
            <p><a href="<?php echo JRoute::_('index.php?option=com_leadership&view=member&id='.(int) $ceo->id); ?>" class="btn">Profile</a></p>
        </div>
    </div>
</div>

<?php $presidents = array_splice ($this->items, 0, 3); ?>
<div class="content-block desktop">
	<div class="leadership">
    	<h2>Presidents</h2>
		<?php foreach ($presidents as $item) : ?>
		<a class="leadership-member president" href="<?php echo JRoute::_('index.php?option=com_leadership&view=member&id='.(int) $item->id); ?>">
		<img src="<?php echo $item->tile; ?>" alt="<?php echo $item->name; ?>" />
			<div class="leadership-member-info">
				<p class="name"><?php echo $item->name; ?></p>
				<p class="title"><?php echo $item->title; ?></p>
				<p class="company"><?php echo $item->company; ?></p>
			</div>
		</a>
	<?php endforeach; ?>
	</div>
</div>


<div class="content-block desktop">
	<div class="leadership">
    	<h2>Dart Enterprises Executive Team</h2>
		<?php foreach ($this->items as $item) : ?>
		<a class="leadership-member" href="<?php echo JRoute::_('index.php?option=com_leadership&view=member&id='.(int) $item->id); ?>">
		<img src="<?php echo $item->tile; ?>" alt="<?php echo $item->name; ?>" />
			<div class="leadership-member-info">
				<p class="name"><?php echo $item->name; ?></p>
				<p class="title"><?php echo $item->title; ?></p>
				<p class="company"><?php echo $item->company; ?></p>
			</div>
		</a>
	<?php endforeach; ?>
	</div>
</div>