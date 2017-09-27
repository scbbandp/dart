<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Companies
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>

<div class="content-block">
	<h2><?php echo $this->item->name; ?></h2>
	<?php echo nl2br($this->item->description); ?>
	
	
	<div class="company-info-block">
		
		<div class="col">
			<p><?php echo nl2br($this->item->address); ?></p>
			<p><?php echo nl2br($this->item->tel); ?></p>
		</div>
		
		<div class="col">
			<p><strong>Opening hours</strong></p>
			<p><?php echo nl2br($this->item->hours); ?></p>
		</div>

	</div>

	<img class="company-info-img" src="<?php echo $this->baseurl ?>/images/companies/photos/sample.jpg" />
	<p><a class="btn blue" href="<?php echo $this->item->link; ?>">Visit site</a></p>

</div>