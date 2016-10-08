<?php
	# Парсим категории
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	require '../lib/dbAuto.php';
	require '../lib/curl.php';

	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	echo $proxy['proxy'].'<br/><br/>';

	$cat = selectrow("SELECT * FROM `categories` WHERE `checked` = 0 LIMIT 1");
	if (!$cat) { echo 'Все категории спарсили!'; exit; }

	echo 'Категория: '.$cat['name'].'<br/>';

	$cat['alias'] = str_replace('-', '_', $cat['alias']);
	$url = 'https://auto.ru/cars/'.$cat['alias'].'/all/';
	echo '<pre>';
	echo 'Парсим категорию: '.$url.'<br/>';

	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'cookiefile' => __DIR__.'/cookies/'.$cat['alias'].'-'.time(),
		'proxy' => $proxy['proxy'],
		'proxysocks' => 1
	]);
	$page = $curl->makerequest();
//     print_r($page);
	
	if (preg_match("#302 Found#", $page)) { 

		$headers = $curl -> getanswerHeader();
		echo $headers['redirect_url'];


		$curl = new CurlBasic();
	 	$curl->url($headers['redirect_url']);
	 	$curl->setArray([
			'cookiefile' => __DIR__.'/cookies/'.$cat['alias'].'-'.time(),
			'proxy' => $proxy['proxy'],
			'proxysocks' => 1
		]);
		$page = $curl->makerequest();
	    print_r($page);
		
		echo 'Не удалось открыть страницу!'; 
		//exit; 
	}

	if (!$page) { 
		$headers = $curl -> getanswerHeader();
		echo $headers['redirect_url'];
		echo 'Пустая страница!';
		exit; 	
	}
	
 	preg_match_all('#<li class="mmm__item"><a class="[^\"]*" role="link" href="([^\"]+)" data-bem=\'{"link":{"model":"[\w]+"}}\'><span class="mmm__item-text">([\wА-Яа-яёЁ ()-/]+)<\/span><\/a>.*?<\/li>#u', $page, $matches);	
//  	print_r($matches);
	

	if (count($matches[0]) == 0) { echo 'Покатегорий не найдено!'; exit; }//print_r($page); }

	echo '<br/>Добавлены категории: <br/>';
	foreach($matches[2] as $i=>$name) {
		echo $name.'<br/>';
 		insert('subcategories', ['name' => $name, 'ref' => $matches[1][$i], 'parent_id' => $cat['id']]);
	}
	
	update('categories', ['alias' => $cat['alias'], 'checked' => 1], ['id' => $cat['id']])

?>