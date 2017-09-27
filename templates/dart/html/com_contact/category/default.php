<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>

<div class="content-block">
	<h1><?php echo $this->category->title; ?></h1>
	<?php echo $this->category->description; ?>
	
	<div class="contact-category<?php echo $this->pageclass_sfx;?>">
		<?php echo $this->loadTemplate('items'); ?>
	</div>
    
    <h2>Addresses</h2>
<div class="address-col-wrapper">
<div class="address-col">
<p><strong>Physical:</strong><br /> Dart Enterprises Ltd.<br /> 89 Nexus Way<br /> Camana Bay<br /> Grand Cayman</p>
</div>
<div class="address-col">
<p><strong>Mailing:</strong> <br />
Dart Enterprises Ltd. <br />
10 Market Street, #771 <br />
Camana Bay <br />
Grand Cayman <br />
KY1-9006 <br />
Cayman Islands </p>
</div>
</div>
    
</div>