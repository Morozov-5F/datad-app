<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	echo '<pre>';
		
	$provider = selectRow("SELECT * FROM `providers` WHERE `socialID` = 2 AND `description` = '' LIMIT 1");
	
	print_r($provider);
	
	$curl = new CurlBasic();
	$curl->url('https://vk.com/'.$provider['profileID']);
	$page = $curl->makerequest();
	$headers = $curl->getanswerHeader();
	
	print_r($headers);
	preg_match_all('#<meta name="description" content="(.*)" />#', $page, $descr);	
	preg_match_all('#<img class="page_avatar_img" src="(.*)" alt=#', $page, $img);
	
	if (empty($descr[1][0])) {
		preg_match_all('#<div class="page_current_info" id="page_current_info"><span class="current_text">(.*)</span></div>#', $page, $descr);	
	}
	if (empty($descr[1][0])) { $descr[1][0] = ''; }
	 
	//print_r(iconv('windows-1251','utf-8',$descr[1][0]));
 	echo '<br/>';
	//print_r($img);
	
	//update('providers', ['avatar' => $img[1][0], 'description' => iconv('windows-1251','utf-8',$descr[1][0])], ['id' => $provider['id']]);
	
?>