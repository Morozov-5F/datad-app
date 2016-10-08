<?php 
	require './lib/db.php';
	require './lib/curl.php';
	
	$adv = selectRow("SELECT * FROM `adv_info` WHERE `url` LIKE '%http%' LIMIT 1");
	if (!$adv) { echo "Все старье поменяли!"; exit; }
	
	echo '<pre>';
	print_r($adv);
	echo '<br/>';
	
	$text = str_replace("'", "`", str_replace("<br />", "\n", strip_tags($adv['text'], "<br>")));

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
	
	$nameRow = selectrow("SELECT * FROM `name` WHERE `name` LIKE '".$adv['name']."' LIMIT 1");
	if (!$nameRow) 
	{ 
		echo 'Имя не найдено<br/>Добавляем имя: '.$adv['name'].'<br/>';
		$name = insert('name', ['name' => $adv['name']]);
	}
	else {
		echo 'Имя найдено<br/>';
		$name = $nameRow['name_id'];
	}
	echo 'name_id: '.$name.'<br/><br/>';
	
	$ownerRow = selectrow("SELECT * FROM `owner` WHERE `owner` LIKE '".$adv['owner']."' LIMIT 1");
	if (!$ownerRow) 
	{ 
		echo 'Owner не найден<br/>Добавляем Owner: '.$adv['owner'].'<br/>';
		$owner = insert('owner', ['owner' => $adv['owner']]);
	}
	else {
		echo 'Owner найден<br/>';
		$owner = $ownerRow['owner_id'];
	}
	echo 'owner_id: '.$owner.'<br/><br/>';
	
	$addressRow = selectrow("SELECT * FROM `address` WHERE `address` LIKE '".$adv['address']."' LIMIT 1");
	if (!$addressRow) 
	{ 
		echo 'Address не найден<br/>Добавляем Address: '.$adv['address'].'<br/>';
		$address = insert('address', ['address' => $adv['address']]);
	}
	else {
		echo 'Address найден<br/>';
		$address = $addressRow['address_id'];
	}
	echo 'address_id: '.$address.'<br/><br/>';
	
	$img = explode(', ', $adv['images']);
	$img_str = '';
	foreach($img as $k=>$i) {
		$img_str .= substr($i,(strrpos($i, '/')) + 1, -4).($k == count($img) - 1 ? '' : ', ');
	}
	echo 'img_str: '.$img_str;
	
	//$url_id = selectrow("SELECT * FROM `advert` WHERE ")
	
	update('adv_info', ['text' => '', 'text_id' => $text, 'name_id' => $name, 'owner_id' => $owner, 'address_id' => $address, 'images' => $img_str], ['id' => $adv['id']]);
?>