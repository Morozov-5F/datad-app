<?php 
	require './lib/db.php';
	require './lib/curl.php';
	
	$adv_info = selectRow("SELECT * FROM `adv_info` WHERE `url` LIKE '%http%' LIMIT 1");
	if (!$adv_info) { echo "Все старье поменяли!"; exit; }
	
	echo '<pre>';
	$url = str_replace("https://www.avito.ru", "", $adv_info['url']);
	//;
	
	$advert = selectrow("SELECT * FROM `advert` WHERE `url` LIKE '".$url."'");
	//print_r($advert['id']);
	
 	update('adv_info', ['url' => $advert['id']], ['id' => $adv_info['id']]);
 	
	echo 'url: '.$adv_info['url'].'<br/>id: '.$adv_info['id'];
	
	echo '<br/><br/>Осталось: ';
	print_r(selectRow("SELECT COUNT(*) FROM `adv_info` WHERE `url` LIKE '%http%'")['COUNT(*)']);
?>