<?php
	require './support/curl.php';
	require './support/dataBase.php';
	
	echo '<pre>';
	
	$api_key = 'AIzaSyAmwciSSSwBc3Tdr4kaUAmNF8iGdM_cNVc';
	
	$cat_id = 4;
	
	$cat = selectrow("SELECT * FROM `category` WHERE `id` = ".$cat_id." LIMIT 1");
	
	$curl = new CurlBasic();
 	$curl->url("https://www.googleapis.com/youtube/v3/channels?part=snippet,statistics&categoryId=".$cat['youtubeID']."&maxResults=50&key=".$api_key);
	$page = json_decode($curl->makerequest(), true);
	
	$pageToken = $page['nextPageToken'];
	
	foreach ($page['items'] as $ch) {	
		
		$data = [
			'profileID' => $ch['id'],
			'name' => $ch['snippet']['title'],	
			'description' => $ch['snippet']['description'],
			'avatar' => $ch['snippet']['thumbnails']['default']['url'],
			'subscribers' => $ch['statistics']['subscriberCount'],
			'posts' => $ch['statistics']['videoCount'],
			'socialID' => 1,
			'categoryID' => $cat_id,
			'price' => rand(10, 5000)

		];
		
		print_r($data);
		print_r(insert('providers', $data));

	}
	
	insert('pageTokens', ['pageToken' => $pageToken]);	
?>