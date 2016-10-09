<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	$curl = new CurlBasic();
 	$curl->url("http://www.spellfeed.com/publishers/all/page/1/?order_by=-followers_count&category=puckn");
 	$page = $curl->makerequest();
 	
 	//print_r($page);
 	//\n.*class="pull-left instagram-username">\n[ ]*(\w*)\n.*</a>
 	preg_match_all('#<a href="/instagram/(\d*)/"#', $page, $match);
 	preg_match_all('#<td width="110" valign="top" align="right">(\d+)</td>#', $page, $subscr);
 	preg_match_all('#<td width="80" valign="top" align="right">(\d+)</td>#', $page, $prices);
 	 	
 	$match = array_unique($match[1]);
 	
 	$k = 0;
 	foreach ($match as $i) {
	 	$arr = [
			'profileID' => $i,
			'name' => '',	
			'description' => '',
			'avatar' => '',
			'subscribers' => $subscr[1][$k],
			'posts' => '',
			'socialID' => 3,
			'categoryID' => 1,
			'price' => $prices[1][$k]
		];
		
		echo update_or_insert('providers', $arr)['id'];
		print_r($arr);	
		
		$k++;
 	}

 ?>