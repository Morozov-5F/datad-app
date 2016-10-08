<?php
	# Парсим категории
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	require '../lib/dbAuto.php';
	require '../lib/curl.php';

	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	echo $proxy['proxy'].'<br/><br/>';

	$url = 'https://auto.ru';
	echo '<pre>';
	echo 'Парсим объяву: '.$url.'<br/>';

	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'cookiefile' => __DIR__.'/cookies/'.time(),
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);
	
	$page = $curl->makerequest();
//   	print_r($page);
  	
 	preg_match_all('#<div class="menu-item menu-item_js_inited menu-item_theme_islands menu-item_size_l" data-reactid="(\d+)">([\d]*)<\/div>#', $page, $matches);	
	print_r($matches);

	foreach($matches[1] as $i=>$id) {
		//preg_match_all('#{"id":"[\w]*","alias":"([\w-]*?)","name":"'.$matches[2][$i].'","count":[\d]*,"popular":[\w]*}#', $page, $alias);
		//echo $matches[2][$i].' | ';
		//echo $alias[1][0].'<br/>';
		
 		insert('years', ['id' => $id, 'year' => $matches[2][$i]], ['id' => $id]);
	}
?>