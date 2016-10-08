<?php
	require './lib/db.php';
	require './lib/curl.php';
	require './lib/parseImage.php';

	function get_date_from_rus_str($str) {
		preg_match('#(\d*) (.*?)#U', $str, $date);
		
		$day = $date[1] < 10 ? '0'.$date[1] : $date[1];
		
		switch ($date[2]){
			case 'января': return $day.'.01.16';
			case 'февраля': return $day.'.02.16';
			case 'марта': return $day.'.03.16';
			case 'апреля': return $day.'.04.16';
			case 'мая': return $day.'.05.16';
			case 'июня': return $day.'.06.16';
			case 'июля': return $day.'.07.16';
			case 'августа': return $day.'.08.16';
			case 'сентября': return $day.'.09.16';
			case 'октября': return $day.'.10.16';
			case 'ноября': return $day.'.11.16';
			case 'декабря': return $day.'.12.16';
			default: return 'ERROR_DATE'; 
		}
	}
	function phoneDemixer($key, $id) {
	    preg_match_all("/[\da-f]+/", $key, $pre);
	    $pre = $id % 2 == 0 ? array_reverse($pre[0]) : $pre[0];
	    $mixed = join('', $pre);
	    $s = strlen($mixed);
	    $r = '';
	    
	    for($k = 0; $k < $s; ++$k) {
	        if ($k % 3 == 0) {
	            $r .= substr($mixed,$k,1);
	        }
	    }
	    
	    return $r;
	}

	$user = selectrow("SELECT * FROM `user-agent` ORDER BY rand() LIMIT 1")['user_agent'];
	echo $user.'<br/>';

	$proxy = selectrow("SELECT * FROM `proxy` WHERE `ban` = 0 ORDER BY `last_use` ASC LIMIT 1");
	update('proxy', ['last_use' => time()], ['id' => $proxy['id']]);
	echo $proxy['proxy'].'<br/>';

	$advert = selectrow("SELECT * FROM `advert` WHERE `checked` = 0 LIMIT 1");
	if (!$advert) { echo 'Нет объявлений для парсинга!'; exit; }
	
	$url = 'https://www.avito.ru'.$advert['url'];
	echo '<pre>';
	echo 'Парсим объяву: <a href="'.$url.'" target="_blank">'.$url.'</a><br/>';
	print_r($advert);

	$curl = new CurlBasic();
 	$curl->url($url);
 	$curl->setArray([
	 	'httpheaders' => [ 
		 	'User-Agent: '.$user,
	 	],
 		'proxy' => $proxy['proxy'],
 		'proxysocks' => 1
 	]);

	$page = $curl->makerequest();
	
	if (!$page) { 
		echo 'Не открылась страница! Удаляем объяву!';
		update('advert', ['checked' => -1], ['id' => $advert['id']]);
		
		//query("DELETE FROM `advert` WHERE `id` = ".$advert['id']);
		exit; 
	}//update('proxy', ['ban' => 2], ['id' => $proxy['id']]); exit; }
	
	if (preg_match("#<i class=\"icon-forbidden\"><\/i>#", $page)) { echo 'Banned<br/>Proxy ID: '.$proxy['id'];  update('proxy', ['ban' => 1], ['id' => $proxy['id']]); print_r($page); exit;  }
	
	preg_match('#data-item-id="(.*)"#U', $page, $id);
	if (!$id[1]) { update('advert', ['checked' => -1], ['id' => $advert['id']]); echo 'Не получилось спарсить объяву!'; exit; }
	
	preg_match('#pubads.setTargeting\("par_title", "(.*)"#U', $page, $nameArr);
	preg_match('#pubads.setTargeting\("par_price", "(.*)"#U', $page, $price);
	preg_match('#<strong itemprop="name">(.*)</strong>#U', $page, $ownerArr);
	preg_match('#data-location-id="(.*)"#U', $page, $region);
	preg_match('#<span itemprop="streetAddress">(.*?)</span>#', $page, $address);
	preg_match('#data-map-lat="(.*?)"#', $page, $latitude);
	preg_match('#data-map-lon="(.*?)"#', $page, $longitude);
	preg_match('#<div class="description description-text"> <div.*?itemprop="description">(.*)</div> </div>#U', $page, $textArr);

	$text = str_replace("<br />", "\n", strip_tags($textArr[1], "<br>"));
	$textRow = selectrow("SELECT * FROM `text` WHERE `text` LIKE '".$text."' LIMIT 1");
	if (!$textRow) 
	{ 
		echo 'Текст не найден<br/>';
		$text = insert('text', ['text' => $text]);
		
	}
	else {
		echo 'Текст найден<br/>';
		$text = $textRow['text_id'];
	}
	echo 'text_id: '.$text.'<br/><br/>';
	
	$nameRow = selectrow("SELECT * FROM `name` WHERE `name` LIKE '".$nameArr[1]."' LIMIT 1");
	if (!$nameRow) 
	{ 
		echo 'Имя не найдено<br/>Добавляем имя: '.$nameArr[1].'<br/>';
		$name = insert('name', ['name' => $nameArr[1]]);
	}
	else {
		echo 'Имя найдено<br/>';
		$name = $nameRow['name_id'];
	}
	echo 'name_id: '.$name.'<br/><br/>';
	
	$ownerRow = selectrow("SELECT * FROM `owner` WHERE `owner` LIKE '".$ownerArr[1]."' LIMIT 1");
	if (!$ownerRow) 
	{ 
		echo 'Owner не найден<br/>Добавляем Owner: '.$ownerArr[1].'<br/>';
		$owner = insert('owner', ['owner' => $ownerArr[1]]);
	}
	else {
		echo 'Owner найден<br/>';
		$owner = $ownerRow['owner_id'];
	}
	echo 'owner_id: '.$owner.'<br/>';
	
	# Картинки
	preg_match_all('#<meta property="og:image" content="https://(.*?)" />#', $page, $images);
	$img_str = '';
	foreach($images[1] as $i=>$img) {
		if ($i == 0) continue;
		$img_str .= $img.($i == count($images[1]) - 1 ? '' : ', ');
	}
	
	# Категория
	preg_match('#<i class="i i-triangle-right-gray"><\/i><a href="(.*)".*</strong></a> </div>#U', $page, $cat_url);
	$cat_str = substr($cat_url[1], strpos($cat_url[1], "/", 1) + 1);
	$cat_id = selectrow("SELECT * FROM `subcategories` WHERE `url` LIKE '".$cat_str."' LIMIT 1")['id'];
	if (!$cat_id) {
		preg_match('#data-category-id="(.*)"#U', $page, $cat);
		$cat_id = -$cat[1];
	}
	
	# Дата размещения
	$date_str = '';
	preg_match('#<div class="item-subtitle.*">\n Размещено (.*)\.<a href=#U', $page, $date);
	preg_match('#(.*?) в (.*?)#U', $date[1], $date);
	switch ($date[1]) {
		case 'вчера': 
			$day = date("d") - 1;
			if ($day < 10) $day = '0'.$day;
			$date_str = $day.'.'.date("m.y").', '. $date[2];
			break;
		case 'сегодня': $date_str = date("d.m.y").', '. $date[2]; break;
		default: $date_str = get_date_from_rus_str($date[1]).', '. $date[2]; break;
	}
	$dateTimeStamp = strtotime($date_str);
	
	# Картинка с теефоном
	preg_match('#avito.item.phone = \'(.*?)\';#', $page, $item_phone);
	$pkey = phoneDemixer($item_phone[1], $id[1]);
	$phone_img_url = 'https://www.avito.ru/items/phone/'.$id[1].'?pkey='.$pkey;
	
	echo '<br/>ID объявления: '.$advert['id'].'<br/>';
	echo 'Ссылка на картинку с номером: '.$phone_img_url.'<br/>';
	
	$phone = 0;
	
	$curl = new CurlBasic();

 	$curl->url($phone_img_url);
 	$curl->setArray([
	 	'httpheaders' => [ 
		 	'User-Agent: '.$user,
		 	'X-Requested-With: XMLHttpRequest'
	 	],
	 	'referer' => $advert['url'],
 		'proxy' => $proxy['proxy'],
 		'proxysocks' => 1
 	]);

	$page = json_decode($curl->makerequest(), true);
	 
	if (!$page['image64']) { 
		echo '<br/>Что-то пошло не так с номером!<br/>';
		echo $page['error']['message'].'<br/><br/>';
		$phone = -1; 
	}
	
 	if ($phone != -1) {
	 	$encrypt = new parseImage($page['image64']); 
	 	echo "Картинка с номером: <img src='".$page['image64']."'><br/>";
 	
	 	if (preg_match('#-5-552#', $encrypt->resolve)) { 
			echo 'Ошибка! Обновите страницу!<br/>';
			$phone = -2;
		}
		
		if ($phone != -2) { 
			echo "Номер распознан: ".$encrypt->resolve.'<br/><br/>'; 
			$phone = $encrypt->resolve;
		}
	}
 		
	$data = [
		'id' => $id[1],
		'date' => $dateTimeStamp,
		'name' => $name,
		'price' => $price[1],
		'phone' => $phone,
		'phone_img' => $phone_img_url,
		'owner' => $owner,
		'region' => $region[1],
		'address' => $address[1],
		'latitude' => $latitude[1],
		'longitude' => $longitude[1],
		'text_id' => $text,
		'category' => $cat_id,
		'url' => $url,
		'images' => $img_str,
		'proxy_id' => $proxy['id']
	];
	
	echo '<br/>';
	print_r($data);
	
	insert('adv_info', $data);
	update('advert', ['checked' => 1], ['id' => $advert['id']]);

	//print_r(str_replace("<br />", "\n", strip_tags($adv_info['text'], "<br>")));
	
	
?>