<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_People
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>
<img class="full" src="<?php echo $this->baseurl ?>/<?php echo $this->item->hero; ?>" />
<div class="content-block person">

	

	<h2 style="text-align:left;"><?php echo $this->item->name; ?></h2>

	<table class="person" style="font-size: 16px;">
    	<tr>
        	<td><strong>Title:</strong></td><td><?php echo $this->item->job; ?></td>
        </tr>
        <tr>
        	<td><strong>Company:</strong></td><td><?php echo $this->item->company; ?></td>
        </tr>
        <tr>
        	<td><strong>Since:</strong></td><td><?php echo $this->item->since; ?></td>
        </tr>
        <tr>
        	<td><strong>Specialty:</strong></td><td><?php echo $this->item->specialty; ?></td>
        </tr>
    </table>

	
	<?php echo nl2br($this->item->about); ?>
	

</div>

