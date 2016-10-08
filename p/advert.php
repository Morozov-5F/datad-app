<?php
	require './lib/db.php';
	require './lib/curl.php';
	
	$advert = selectrow("SELECT * FROM `advert` WHERE `checked` = 0 LIMIT 1");
	$link = 'https://www.avito.ru'.$advert['url'];

	echo $link;

/*

	$key = '258e97f93g19bdbf9370d030d973dfb0b95185e682f588a7df092b3e0fb8cgef2d6eb81g3393790850c80f0b3b8902d7388';
	$id = '829720178';
	
	$pkey = phoneDemixer($key, $id);
	
	$curl = new CurlBasic();
	$curl->url('https://www.avito.ru/items/phone/'.$id.'?pkey='.$pkey);
	$curl->setArray([
		'httpheaders' => [
			'X-Requested-With: XMLHttpRequest'
		],
		'referer' => 'https://www.avito.ru/voronezh/zapchasti_i_aksessuary/dvigatel_1.6_829720178'
	]);
	
	$page = $curl->makerequest();
	$a = json_decode($page,true);

	echo '<img src="'.$a['image64'].'">';	
	
*/
	
	
	
	function phoneDemixer($key, $id) {
		preg_match_all("/[\da-f]+/", $key, $pre);
	    $pre = $id % 2 == 0 ? array_reverse($pre[0]) : $pre[0];
	    $mixed = join('', $pre);
	    $s = strlen($mixed);
	    $r = '';
	    
	    for($k = 0; $k < $s; ++$k) {
	        if ($k % 3 == 0) {
	            $r .= substr($mixed, $k, 1);
	        }
		}
		
	    return $r;
	}
?>