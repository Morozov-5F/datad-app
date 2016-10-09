<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	$data = iconv('windows-1251','utf-8', file_get_contents('./html/Beauty.html'));
	
//	print_r($data);

 	preg_match_all('#<a href="(.*?)" target="_blank" class="exchange_comm_name">(.*)</a>#', $data, $id);

	preg_match_all('#.*<br>участников</td>#', $data, $subsArr);	
	foreach ($subsArr[0] as $k=>$i) {
		$subscr[$k] = preg_replace("/[^0-9]/", '', $i);
	}
	
	preg_match_all('#.*руб.*</td>#', $data, $pricesArr);	
	foreach ($pricesArr[0] as $k=>$i) {
		$prices[$k] = preg_replace("/[^0-9]/", '', $i);
	}
	
	foreach ($id[2] as $k=>$i) {
		$arr = [
			'profileID' => substr($id[1][$k], strrpos($id[1][$k], '/') + 1),
			'name' => $i,	
			'description' => '',
			'avatar' => '',
			'subscribers' => $subscr[$k],
			'posts' => rand(500, 5000),
			'socialID' => 2,
			'categoryID' => 4,
			'price' => $prices[$k]
		];
		
		echo update_or_insert('providers', $arr)['id'];
		print_r($arr);
	}

	
//	print_r($prices);
 	//print_r($id);
?>