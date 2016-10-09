<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	$curl = new CurlBasic();
 	$curl->url("http://dealway.ru/advertiser/search.php?type=posts&sort=price&st=desc");
 	$page = $curl->makerequest();
 	
 	print_r($page);
 ?>