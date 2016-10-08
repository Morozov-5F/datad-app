<?php
	# Парсим категории
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	require '../lib/dbAuto.php';
	require '../lib/curl.php';

	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	echo $proxy['proxy'].'<br/><br/>';

	$url = 'https://auto.ru/cars/audi/a4/all/';
	echo '<pre>';
	echo 'Парсим категорию: '.$url.'<br/>';


	echo __DIR__.'/cookies/';
	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'cookiefile' => __DIR__.'/cookies/'.time(),
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);
	$page = $curl->makerequest();

	//print_r($page);
/*

	preg_match_all('#<div class="menu-item menu-item_theme_islands i-bem" role="option" id="[\w]+" aria-checked="false" data-bem=\'{"menu-item":{"val":"[\w]+"}}\'>[\w&;]*([А-Яа-я ]+)<\/div>#u', $page, $mat);
	
*/
	preg_match_all('#<div class="menu-item menu-item_theme_islands i-bem" role="option" id="[\w]+" aria-checked="false" data-bem=\'{"menu-item":{"val":"[\w\d]+"}}\'>[&nbsp;]*([\dА-Яа-яёЁ \.]+)<\/div>#u', $page, $mat);
	
	print_r($mat);
?>