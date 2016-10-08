<?php
	require './lib/db.php';
	require './lib/curl.php';
	
	$curl = new CurlBasic();
	
	$user = selectrow("SELECT * FROM `user-agent` ORDER BY rand() LIMIT 1")['user_agent'];
	echo $user.'<br/>';
	
	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	
	echo $proxy['proxy'].'<br/>';

//  	$curl->url('http://yandex.ru/internet');
	$curl->url('https://www.avito.ru/rossiya');
	$curl->setArray([
		'httpheaders' => [ 
			'User-Agent: '.$user
		],
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);

	$page = $curl->makerequest();
	//print_r($page);
	
	if (!$page) { echo 'Не рабочий прокси!'; update('proxy', ['ban' => 2], ['id' => $proxy['id']]); exit; }
	
	preg_match_all("?<h3 class=\"(.*)\">.*href=\"(.*)\" title?", $page, $matches);
	
	if (preg_match("#<i class=\"icon-forbidden\"><\/i>#", $page)) { echo 'Banned<br/>Proxy ID: '.$proxy['id'];  update('proxy', ['ban' => 1], ['id' => $proxy['id']]); exit;  }
	
	foreach($matches[1] as $i=>$type) {
		if ($type == 'vip-item__header fader') { $vip = 1; }
		else { $vip = 0; }
		
		insert('advert', ['url' => $matches[2][$i], 'vip' => $vip]);
		echo $vip.'<br/>';
	}	
	
//  	print_r($matches);


//print_r($page);

?>