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
<div class="shadow"><img src="<?php echo $this->item->image; ?>" class="member-hero" alt="<?php echo $this->item->name; ?>" /></div>
<div class="content-block no-top-margin">
	
	<div class="member">
    
    <style type="text/css">
	.st0{clip-path:url(#SVGID_2_); fill:#838386;}
	.st1{clip-path:url(#SVGID_2_);fill:#FFFFFF;}
</style>
    
		<div class="member-meta">
			<p><a href="<?php echo $this->item->linkedin; ?>" title="View LinkedIn Profile"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 282 282"  width="32px" height="32px" style="enable-background:new 0 0 282 282;" xml:space="preserve">
<style type="text/css">
	.st0{clip-path:url(#SVGID_2_);}
</style>
<g>
	<defs>
		<rect id="SVGID_1_" width="282" height="282"/>
	</defs>
	<clipPath id="SVGID_2_">
		<use xlink:href="#SVGID_1_"  style="overflow:visible;"/>
	</clipPath>
	<path class="st0" d="M141,0C63.1,0,0,63.1,0,141c0,77.9,63.1,141,141,141s141-63.1,141-141C282,63.1,218.8,0,141,0 M107.4,190.2H80
		v-82.5h27.4V190.2z M96.2,96.1c-0.8,0.1-1.7,0.1-2.5,0.1c-11,0-18-9.4-14.4-19.4c2-5.5,7.4-9,14.5-9.2c7.1-0.2,13.1,3.8,15,10
		C111.4,86.6,105.6,95.2,96.2,96.1 M207.5,187.7v2.5h-27.7v-2.6c0-13.5,0.1-27-0.1-40.5c0-3.4-0.4-7-1.3-10.3
		c-3.3-11.9-16.6-11.2-22.5-5.8c-4.2,3.9-5.4,8.7-5.4,14.2c0.1,14.1,0,28.1,0,42.2v2.7H123v-82.4h27.5V119c0.7-0.8,1.1-1.2,1.4-1.6
		c5.5-7.7,13.1-11.6,22.5-11.8c10.3-0.2,19.4,2.8,25.8,11.4c4.5,6,6.5,12.9,6.9,20.2c0.4,7.1,0.3,14.2,0.4,21.3
		C207.5,168.3,207.5,178,207.5,187.7"/>
</g>
</svg>
</a></p>
		</div>
		<div class="member-bio">
			<h3 class="member-bio-name"><?php echo $this->item->name; ?></h3>
			<p class="bio-title"><?php echo $this->item->title; ?> - <?php echo $this->item->company; ?></p>
		
			<?php echo $this->item->bio; ?>
		</div>
	
	
	<div class="member-highlights">
    	<h4 class="bio-title">CAREER HIGHLIGHTS</h4>
		<?php echo $this->item->highlights; ?>
	</div>
	
    <?php if($this->item->community): ?>
	<div class="member-community">
    	<h4 class="bio-title">INSIGHTS</h4>
		<?php echo $this->item->community; ?>
	</div>
    <?php endif; ?>
    <p><a href="<?php echo JRoute::_('index.php?Itemid=' . 235) ?>" class="btn blue">Back</a>
	</div>
    
    
</div>



