<?php
	require './support/curl.php';

	$url = 'https://m.vk.com/login';
	
	$curl = new CurlBasic();
 	$curl->url("https://m.vk.com/login");
	$page = $curl->makerequest();
	
	preg_match_all('#<form method="post" action="(.*)" novalidate>#', $page, $link);
	
	print_r($link);

	
/*

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*//*;q=0.8',
	        'content-type: application/x-www-form-urlencoded',
	        'origin: http://vk.com',
	        'referer: http://vk.com/',
	));
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt'); 

	$content = curl_exec($ch);
*/
	
	//preg_match_all("/name=\"ip_h\" value=\"(.*?)\" \\//s", $content, $ip_h);
//	preg_match_all("/name=\"lg_h\" value=\"(.*?)\" \\//s", $content, $lg_h);
	
// 	print_r($content);
	
// 	print_r($page);

?>