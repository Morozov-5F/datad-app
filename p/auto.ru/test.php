<?php
	#217.172.170.80
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	require '../lib/dbAuto.php';
	require '../lib/curl.php';

/*
	$proxy = selectrow("SELECT * FROM `proxy` WHERE `status` = -1 LIMIT 1");
	echo 'Наш proxy: '.$proxy['proxy'].'<br/>';

	$url = 'http://mega.esvr.ru/testProxy.php';
	
	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'proxy' => $proxy['proxy'],
 		'proxysocks' => 1
 	]);

	$page = $curl->makerequest();
	update('proxy', ['status' => $page], ['id' => $proxy['id']]);
	
	echo 'Ответ сервера: <br/>';
	echo $page;
*/

	preg_match_all('#Hi ([А-Яа-яёЁ ]+)#u', 'Hi рысь John32', $mat);
  	
	print_r($mat);


# <div class="menu-item menu-item_theme_islands i-bem" role="option" id="uniq147378138581818560345" aria-checked="false" data-bem='{"menu-item":{"val":"HATCHBACK_LIFTBACK"}}'>&nbsp;&nbsp;Лифтбек</div>

# <div class="menu-item menu-item_theme_islands i-bem" role="option" id="uniq147378138581818560346" aria-checked="false" data-bem='{"menu-item":{"val":"ALLROAD"}}'>Внедорожник</div>
 	
?>