<?php 
// No direct access
defined('_JEXEC') or die; ?>


<div class="content-block">
	<h1>Job Opportunities</h1>
	<p>
Dart Enterprises is the holding company for a portfolio of companies. Based in Grand Cayman, the company has a global reach with investments around the world and its holdings include real estate, development, property management, retail, finance and technology.</p>
	<?php foreach($dartJobs as $job): ?>
		
		<div class="job">
			<h3><a href="<?php echo $job['info']; ?>"><?php echo $job['title']; ?></a></h3>
			<p class="meta">
				<strong>Location:</strong> <?php echo $job['location']; ?> 
				<strong>Company:</strong> <?php echo $job['company']; ?>
				<strong>Closing date:</strong> <?php echo $job['closing']; ?>
			</p>
			<p><?php echo $job['summary']; ?></p>
            <!--
			<p><a href="<?php echo $job['info']; ?>">more info</a> &nbsp; | &nbsp; <a href="<?php echo $job['apply']; ?>">apply</a></p>
            -->
		</div>
		
	<?php endforeach; ?>
</div>


<div class="content-block">
	<h2>Active Capital Careers</h2>
	<p>Active Capital Ltd. is a Dart Enterprises company that develops, supports and manages a portfolio of retail and hospitality businesses.</p>
	<?php foreach($activeJobs as $job): ?>
		
		<div class="job">
			<h3><a href="<?php echo $job['info']; ?>"><?php echo $job['title']; ?></a></h3>
			<p style="font-size: 14px;">
				<strong>Location:</strong> <?php echo $job['location']; ?> 
				<strong>Company:</strong> <?php echo $job['company']; ?>
				<strong>Closing date:</strong> <?php echo $job['closing']; ?>
			</p>
            <!--
			<p><a href="<?php echo $job['info']; ?>">more info</a> &nbsp; | &nbsp; <a href="<?php echo $job['apply']; ?>">apply</a></p>
            -->
		</div>
		
	<?php endforeach; ?>
</div>

