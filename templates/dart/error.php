<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.dart
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined ( '_JEXEC' ) or die ();

$fullurl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

/** @var JDocumentHtml $this */
$config = JFactory::getConfig ();
$option = JFactory::getApplication ()->input->getCmd ( 'option', '' );
//$this->setHtml5 ( true );

JHtml::_ ( 'stylesheet', 'template.css', array (
		'version' => 'auto',
		'relative' => true 
) );
JHtml::_ ( 'bootstrap.framework', false );
JHtml::_ ( 'script', 'jui/html5.js', array (
		'version' => 'auto',
		'relative' => true,
		'conditional' => 'lt IE 9' 
) );

JHtml::_('jquery.framework');

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WDNXD4V');</script>
<!-- End Google Tag Manager -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta
	content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui'
	name='viewport' />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="robots" content="noindex">
<link href="<?php echo $this->baseurl ?>/templates/dart/css/template.css?cb1f799b6af141d5493d1b197c98b20a" rel="stylesheet" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#00539c">

<style>
	body.error {
		padding: 40px;
	}
	.content-block.error {
		margin: 0;
	}
	
	.content-block h2.super-large {
		text-align: center;
		margin: 1em auto 30px;
		font-size: 100px;
		line-height: 100px;
	}
	
	footer.error {
		margin:0;
	}
	
</style>

</head>

<body class="error">

	<div id="header-container">
		<header>
			<div class="container">
				<a href="/" class="logo"><img width="427" height="120" src="<?php echo $this->baseurl ?>/templates/dart/images/logo.jpg">></a>
 
			</div>
			
		</header>
	</div>

	<div class="container">
		<div class="content-block error">
        	<h2 class="super-large">404</h2>
            <h3>Page not found. <br />Sorry, but this page doesn't exist</h3>
            <p></p>
            <p style="text-align:center;"><a href="/" class="btn blue">VISIT HOME PAGE</a>
            <p></p>
            <p></p>
        </div>
	</div>


	<footer class="error" id="footer">
		<div class="container">
			
			<div class="logo-wrapper">

				<svg version="1.1" xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink"
					xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px"
					y="0px" width="207.9px" height="58.4px" viewBox="0 0 207.9 58.4"
					style="enable-background: new 0 0 207.9 58.4;" xml:space="preserve">
<g>
	<path  d="M0,0h21.5C30.7,0,38,2.7,43.7,8.1c5.6,5.2,8.3,12.3,8.3,21.1c0,8.8-2.9,15.9-8.7,21.2c-5.7,5.3-13.4,8-22.9,8
		H0V0L0,0z M165.7,0h42.3v12.3h-14.4v46.1H180V12.3h-14.4V0L165.7,0z M117.8,0h20.7c6.3,0,11.2,1.5,14.9,4.6
		c3.6,3.1,5.4,7.3,5.4,12.4c0,3-0.8,6.1-2.5,9.4c-1.6,3.2-4.7,5.8-9.3,7.6l17.6,24.3h-15.5l-16.3-22.6h-1.5v22.6h-13.5V0L117.8,0z
		 M131.4,10.4v15.2h2.9c7.1,0,10.6-2.8,10.6-8.1c0-2.2-0.9-3.9-2.8-5.2c-1.9-1.2-4.4-1.9-7.6-1.9H131.4L131.4,10.4z M76.3,0
		L51.2,58.4h13.5l4.9-11.5h24l5.2,11.5h13.5L86.6,0H76.3L76.3,0z M81.5,19L89,36.2H74.2L81.5,19L81.5,19z M13.7,11v36.1h5.4
		c5.6,0,10.1-1.5,13.7-4.3c3.6-2.8,5.3-7.5,5.3-14.1c0-3.8-0.8-7.2-2.6-10.2c-1.7-3-4.3-5-7.5-6c-3.3-0.9-6.4-1.5-9.4-1.5H13.7z" />
</g>
</svg>
			</div>
			<div class="links-wrapper">
				<ul>
					<li><a href="<?php echo $this->baseurl ?>/about-us">About</a></li>
					<li><a href="<?php echo $this->baseurl ?>/companies">Companies</a></li>
					<li><a href="<?php echo $this->baseurl ?>/careers">Careers</a></li>
					<li><a href="<?php echo $this->baseurl ?>/news">News</a></li>
					<li><a href="<?php echo $this->baseurl ?>/contact">Contact</a></li>
				</ul>
				<ul>
					<li><a href="<?php echo $this->baseurl ?>/about-us/timeline">Timeline</a></li>
					<li><a href="<?php echo $this->baseurl ?>/about-us/leadership">Leadership</a></li>
					<li><a href="<?php echo $this->baseurl ?>/about-us/community">Community</a></li>
					<li><a href="<?php echo $this->baseurl ?>/dart-global">Dart Global</a></li>
					<li><a href="<?php echo $this->baseurl ?>/dart-enterprises">Dart Enterprises</a></li>
					<li><a href="<?php echo $this->baseurl ?>/about-us/business-partners">Business Partners</a></li>
				</ul>
				<ul>
					<!--<li><a href="<?php echo $this->baseurl ?>/faqs">FAQs</a></li>-->
					<li><a href="<?php echo $this->baseurl ?>/contact/media">Media Centre</a></li>
					<li><a href="<?php echo $this->baseurl ?>/terms">Terms of Use</a></li>
					<li><a href="<?php echo $this->baseurl ?>/privacy-policy">Privacy Policy</a></li>
					
					<li><a href="https://www.linkedin.com/company-beta/1339153/" target="_blank"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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
	<path style="fill:#adadad;" class="st0" d="M141,0C63.1,0,0,63.1,0,141c0,77.9,63.1,141,141,141s141-63.1,141-141C282,63.1,218.8,0,141,0 M107.4,190.2H80
		v-82.5h27.4V190.2z M96.2,96.1c-0.8,0.1-1.7,0.1-2.5,0.1c-11,0-18-9.4-14.4-19.4c2-5.5,7.4-9,14.5-9.2c7.1-0.2,13.1,3.8,15,10
		C111.4,86.6,105.6,95.2,96.2,96.1 M207.5,187.7v2.5h-27.7v-2.6c0-13.5,0.1-27-0.1-40.5c0-3.4-0.4-7-1.3-10.3
		c-3.3-11.9-16.6-11.2-22.5-5.8c-4.2,3.9-5.4,8.7-5.4,14.2c0.1,14.1,0,28.1,0,42.2v2.7H123v-82.4h27.5V119c0.7-0.8,1.1-1.2,1.4-1.6
		c5.5-7.7,13.1-11.6,22.5-11.8c10.3-0.2,19.4,2.8,25.8,11.4c4.5,6,6.5,12.9,6.9,20.2c0.4,7.1,0.3,14.2,0.4,21.3
		C207.5,168.3,207.5,178,207.5,187.7"/>
</g>
</svg></a></li>
				</ul>
			</div>
			<p class="copyright">&copy; <?php echo date('Y'); ?> Dart Enterprises </p>
		</div>
	</footer>
	<!-- end footer -->

	<jdoc:include type="modules" name="debug" />
	
	<script>
	

	jQuery(window).load(function() {
	
		if (window.modal == null) {
			window.modal = new Modal();
			window.modal.init();
			window.modal.addEventListener('loaded', function(){});				
		}
	});
	
	jQuery(document).ready(function(e) {
		
		jQuery("#btn-nav").click(function(e) {
			e.preventDefault();
			if(jQuery(this).hasClass('active')){
				jQuery("ul.nav").slideUp();
				jQuery(this).removeClass('active');
			} else {
				jQuery("ul.nav").slideDown();
				jQuery(this).addClass('active');
			}
			return false;
		});
		
		jQuery( window ).resize(function() {
	
			if(jQuery("#btn-nav").is(":visible")) {
				jQuery("ul.nav").hide();
				jQuery('#btn-nav').removeClass('active');
				
			}else{
				jQuery("ul.nav").show();
				jQuery("ul.nav > li").attr('style', '');
				
			}
		});
		
		jQuery("a.scroll-to").click(function(e) {
			
			jQuery('header').stop();
			jQuery('header').hide();
			
			var offset = jQuery("#header-container").height() + 20;
			var top = jQuery(jQuery(this).attr('href')).offset().top - offset;
			
			jQuery('html,body').animate({scrollTop:top}, '500', 'swing', function(){autoScrolling = false;justFinishedAutoScrolling=true;});
			return false;
		})

		jQuery("#close_search").click(function(e) {
            jQuery("#mod-finder-searchform").hide();
			jQuery("#mod-finder-searchform").removeClass('active');
			e.preventDefault();
			return false;
        });
		
		jQuery("a.search").click(function(e) {
			
			if(jQuery("#mod-finder-searchform").hasClass('active')){
				jQuery("#mod-finder-searchform").hide();
				jQuery("#mod-finder-searchform").removeClass('active');
			}else{
			
				jQuery("#mod-finder-searchform").show();
				jQuery("#mod-finder-searchform").addClass('active');
				jQuery('input.search-query').trigger('click');
				jQuery("input.search-query").focus();
				jQuery("input.search-query").select();
			}
			e.preventDefault();
			return false;
        });
		jQuery.expr[':'].external = function(obj){
			return !obj.href.match(/^mailto\:/)
				   && (obj.hostname != location.hostname)
				   && !obj.href.match(/^javascript\:/)
				   && !obj.href.match(/^$/)
		};
		
		jQuery('a:external').attr('target', '_blank');
		jQuery(".pagination a").off();
    });
</script>

</body>
</html>
