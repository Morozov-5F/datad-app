<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	require '../lib/dbAuto.php';
	require '../lib/curl.php';

	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	echo $proxy['proxy'].'<br/><br/>';

	$id = 113;
	$url = 'https://auto.ru/moskva/cars/bmw/all/';
	echo '<pre>';
	echo 'Парсим категорию: '.$url.'<br/>';
//echo __DIR__.'/cookies/'.time();
	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'cookiefile' => __DIR__.'/cookies/'.$id,
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);
	
	$page = $curl->makerequest();
	
	echo 123;
?>