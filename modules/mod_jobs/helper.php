<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Bbandp.Module
 * @subpackage mod_jobs
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModJobsHelper
{
     public static function getActiveCapitalJobs() {
    	$htmlContent = file_get_contents('http://activecapitalltd.hrmdirect.com/employment/job-openings.php?search=true&');
    	
    	$dom = new DOMDocument;
    	@$dom->loadHTML ($htmlContent);
    	$xpath = new DOMXPath ($dom);
    	$count = 0;
    	
    	$jobs = array();
    	foreach ($xpath->query ("//tr") as $section) {
    	
    		//var_dump($section);
    		$items = $section->getElementsByTagName('td');
    	
    		if(trim($items[2]->nodeValue) != 'Position Title') {
    	
    			$links = $items[2]->getElementsByTagName('a');
    			$job = array();
    	
    			$job['department'] = (string)$items[1]->nodeValue;
    			$job['title'] = (string)$items[2]->nodeValue;
    			$job['location'] = (string)$items[3]->nodeValue;
    			$job['company'] = (string)$items[5]->nodeValue;
    			$job['info'] = 'http://activecapitalltd.hrmdirect.com/employment/' . $links[0]->getAttribute('href');
    			$job['apply'] = 'http://activecapitalltd.hrmdirect.com/employment/' . $links[0]->getAttribute('href');
    			$job['closing'] = 'See application';
    			
    			$jobs[] = $job;
    	
    		}
    	}
    	
    	return $jobs;
    }
    
    public static function getDartJobs() {
    	
    	$jobs = array();
    	
    	$htmlContent = self::getPage('https://careers.dart.ky/applications/externalapplicants/login/default.aspx');
    	$dom = new DOMDocument;
    	@$dom->loadHTML ($htmlContent);
    	$xpath = new DOMXPath ($dom);
    	$count = 0;
    	
    	foreach ($xpath->query ("//table[@class='jobdisplay']") as $section) {
    	
    		foreach ($xpath->query (".//a[@class='jobdisplaylink']", $section) as $review)
    		{
    			$jobs[$count] = array();
    			$jobs[$count]['title'] =  utf8_decode((string) $review->nodeValue);
    		}
    	
    		foreach ($xpath->query (".//td[@class='jobdisplaydetail']", $section) as $review)
    		{
    			$details = (string) $review->nodeValue;
				
    			preg_match("/Location:([\S\s]+)Company/", $details, $result);
    			$jobs[$count]['location'] = trim($result[1]);
    				
    			preg_match("/Company:([\S\s]+)Requisition/", $details, $result);
    			$jobs[$count]['company'] = trim($result[1]);
    				
    			preg_match("/Requisition ID:([\S\s]+)Closing Date/", $details, $result);
    			$jobs[$count]['requisition'] = trim($result[1]);
    				
    			preg_match("/Closing Date:([\S\s]+)/", $details, $result);
    			$jobs[$count]['closing'] = trim($result[1]);
    			
    			//$jobs[$count]['info'] = 'https://careers.dart.ky/applications/externalapplicants/jobs/listing/edit_dialog.aspx?requisition_id=' . $jobs[$count]['requisition'];
    			//$jobs[$count]['apply'] = 'https://careers.dart.ky/applications/externalapplicants/login/default.aspx';
				
				$jobs[$count]['info'] = 'https://careers.dart.ky/applications/externalapplicants/login/default.aspx';
				$jobs[$count]['apply'] = 'https://careers.dart.ky/applications/externalapplicants/login/default.aspx';
    	
    		}
    	
    	
    		foreach ($xpath->query (".//td[@class='jobdisplaysummary']", $section) as $review)
    		{
    			$jobs[$count]['summary'] =  utf8_decode ((string) $review->nodeValue);
    	
    		}
    		$count++;
    	}
    	array_reverse($jobs);
    	return $jobs;
    }
    
    public static function getPage ($url) {
    	//mask agent
    	$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
    	//set timeout
    	$timeout= 120;
    	//emulate cookies/session
    	$dir            = dirname(__FILE__);
    	$cookie_file    = $dir . '/cookies/' . md5($_SERVER['REMOTE_ADDR']) . '.txt';
    
    	//intialize curl
    	$ch = curl_init($url);
    	//curl options
    	curl_setopt($ch, CURLOPT_FAILONERROR, true);
    	//curl_setopt($ch, CURLOPT_HEADER, 1);
    	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    	curl_setopt($ch, CURLOPT_ENCODING, "" );
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    	curl_setopt($ch, CURLOPT_AUTOREFERER, true );
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
    	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
    	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    	curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com/');
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	$content = curl_exec($ch);
    	if(curl_errno($ch))
    	{
    		echo 'error:' . curl_error($ch);
    	}
    	else
    	{
    		return $content;
    	}
    	curl_close($ch);
    }
    
}