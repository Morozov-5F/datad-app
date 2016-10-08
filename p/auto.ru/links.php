<?php
/*
https://auto.ru/-/ajax/phones/?
category=cars
&sale_id=1015130007&sale_hash=4d9e
&__blocks=antifraud%2Ccard-phones%2Cgoogle-conversion%2Ccall-numbers
&crc=1d521aff191613801c3f885142c9a776be3c4ea0%3A1473976054594

https://auto.ru/-/ajax/phones/?
category=cars
&sale_id=1023713127&sale_hash=4ecc
&__blocks=antifraud%2Ccard-phones%2Cgoogle-conversion%2Ccall-numbers
&crc=e6641d7082c53a8d4b8c3b9e9a47bfe328bb37a1%3A1473976663000

*/
	# Парсим категории
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	require '../lib/dbAuto.php';
	require '../lib/curl.php';
	require_once '../lib/CookiejarEdit.php';


	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	echo $proxy['proxy'].'<br/><br/>';

	$id = 113;
	$fn_cook = __DIR__.'/cookies/'.$id;
	$url = 'https://auto.ru/cars/used/sale/ford/focus/1033341153-b066a/';
	echo '<pre>';
	echo 'Парсим категорию: '.$url.'<br/>';
	
	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'cookiefile' => __DIR__.'/cookies/'.$id,
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);
	$page = $curl->makerequest();
	
	$cookedit= new CookiejarEdit($fn_cook, 'yaca.yandex.ru');

	
	if (preg_match("#302 Found#", $page)) { echo 'все плохо'; exit; }
	preg_match_all('#"crc":"([^\"]+)"#', $page, $mat);
	preg_match('#{"sale-services":{"sale_id":[\d]+,"category_id":([\d]+),"ya_region_id":([\d]+)}}#', $page, $cat);
	preg_match('#"params":{"category":"cars","sale_id":"([\d]+)","sale_hash":"([\w]+)"}#', $page, $sale);
	
	$data = [
		'sale_id' => $sale[1],
		'sale_hash' => $sale[2],
		'category_id' => $cat[1],
		'ya_region_id' => $cat[2],
		'crc' => str_replace(':', '%3A', $mat[1][0])
	];
	
	//$url = 'https://auto.ru/cars/used/sale/ford/focus/1044081821-6761c6/#phones';
	$url = 'https://auto.ru/-/ajax/phones/?category=cars&sale_id='.$data['sale_id'].'&sale_hash='.$data['sale_hash'].'&__blocks=antifraud%2Ccard-phones%2Cgoogle-conversion%2Ccall-numbers&crc='.$data['crc'];
echo $url;	
	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'cookiefile' => __DIR__.'/cookies/'.$id,
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);
	$page = $curl->makerequest();
	print_r($curl->getanswerHeader());
	
	print_r($data);
	
	print_r($page);
?>