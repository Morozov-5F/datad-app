<?php
	require './support/curl.php';
	
	$api_key = 'AIzaSyAmwciSSSwBc3Tdr4kaUAmNF8iGdM_cNVc';
	
	$curl = new CurlBasic();
 	$curl->url('https://www.googleapis.com/youtube/v3/guideCategories?part=snippet&regionCode=ru&key='.$api_key);
	$page = $curl->makerequest();
	
	print_r($page);

	
	
	
?>