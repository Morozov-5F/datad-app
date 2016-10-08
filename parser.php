<?php
	require './support/curl.php';

/*
$client_id = '5660176';
$scope = 'offline,ads'
*/

	$token = 'fb0bed1b068a655a5ef63b6faf23239ef11537065c64bdbdcd3c063efb86ea9f7cbd59054c7914dec4573';
// 	echo $token;	
// 518392


	$url = 'http://m.vk.com';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
	        'content-type: application/x-www-form-urlencoded',
	        'origin: http://vk.com',
	        'referer: http://vk.com/',
	));
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
	$content = curl_exec($ch);
	
	preg_match_all("/name=\"ip_h\" value=\"(.*?)\" \\//s", $content, $res[0]);
	preg_match_all("/name=\"lg_h\" value=\"(.*?)\" \\//s", $content, $res[1]); 
	
	print_r($content);
/*

	$curl = new CurlBasic();
 	$curl->url('https://api.vk.com/method/ads.searchCommunity&sort=size&union_id=2002217450&access_token='.$token);

 	$curl->setArray([
	 	'postdata' => 'age=&al=1&cache=1&category=0&city=&cost_to=&country=&load=1&offset=0&preach=&q=&r=0&reach=&sex=0&size
=&sort=size'
 	
]);
	$page = $curl->makerequest();
	
	print_r($page);
	
*/
?>

<!-- <a href="https://oauth.vk.com/authorize?client_id=<?=$client_id;?>&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=<?=$scope;?>&response_type=token&v=5.37">Push the button</a> -->
