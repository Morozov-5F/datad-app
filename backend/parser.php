<?php
	require './support/vk.php';
	
	$login = 'example@example.com';
	$password = 'strong_password';
	
// 	echo vk_auth($login, $password, 8450);
	

	$curl = new CurlBasic();
 	$curl->url("https://vk.com/exchange?act=community_search");
 	$curl->setArray([
	 	'cookiefile' => './_temp/'.md5($login.$password),
	// 	'postdata' => 'age=&al=1&cache=1&category=0&city=&cost_to=&country=&load=1&offset=20&preach=&q=&r=0&reach=&sex=0&size
//=&sort='
 	]);

	$page = $curl->makerequest();
	$headers = $curl->getanswerHeader();
	
	
	
// 	print_r($page);
	print_r($headers);	
?>
