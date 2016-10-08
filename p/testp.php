<?php
	require './lib/curl.php';
	
	$curl = new CurlBasic();
 	$curl->url('nk5.ru/api/registAcc');

	$page = $curl->makerequest();
	
	print_r($page);
?>