<?php
	require './support/dataBase.php';
	
	echo '<pre>';
		
	$provider = selectRow("SELECT * FROM `providers` WHERE `socialID` = 1 AND `check` = 0 LIMIT 1");
	
	print_r($provider);
	
	$new_price = $provider['subscribers'] * 0.03 / 62;
	
	echo '<br/>Старая цена: '.$provider['price'];
	echo '<br/>Новая цена: '.$new_price;
	echo '<br/>Кол-во подписчиков: '.$provider['subscribers'];
	
	update('providers', ['price' => $new_price, 'check' => 1], ['id' => $provider['id']]);
	echo '<br/><br/>'.selectRow("SELECT COUNT(*) FROM `providers` WHERE `socialID` = 1 AND `check` = 0")['COUNT(*)'];
	
?>