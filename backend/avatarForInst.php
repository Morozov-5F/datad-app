<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	echo '<pre>';
		
	$provider = selectRow("SELECT * FROM `providers` WHERE `socialID` = 3 AND `name` = '' LIMIT 1");
	
	print_r($provider);
	//echo 'http://www.spellfeed.com/instagram/'.$provider['profileID'];
	$curl = new CurlBasic();
	$curl->url('http://www.spellfeed.com/instagram/'.$provider['profileID'].'/');
	$page = $curl->makerequest();
	
	preg_match_all('#<h2>(.+)<\/h2>#', $page, $name);	
	preg_match_all('#<a href="http://instagram.com/(.*)"#', $page, $ref);	
	preg_match_all('#<td style="text-align: right; padding-right: 5px;">\n[ ]*<strong>\n[ ]*(\d+)#', $page, $posts);
	preg_match_all('#<img src="(.*?)" class="img-circle">#', $page, $img);
	
	$arr = [
			'profileID' => $ref[1][0],
			'name' => $ref[1][0],	
			'description' => $name[1][0],
			'avatar' => $img[1][0],
			'posts' => $posts[1][0]
	];

	print_r($arr);
	
	update('providers', $arr, ['id' => $provider['id']]);
	echo '<br/><br/>Осталось получить иконок: '.selectRow("SELECT COUNT(*) FROM `providers` WHERE `socialID` = 3 AND `name` = ''")['COUNT(*)'];
?>