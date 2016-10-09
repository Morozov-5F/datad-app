<?php
	require './support/dataBase.php';

	$data = file_get_contents('./html/Games.html');
//	print_r($data);

	preg_match_all('#<a href=".*" target="_blank"><img src="(.*)"#', $data, $id);
//	preg_match_all('#<a href="(.*?)" target="_blank" class="exchange_comm_name">(.*)</a>#', $data, $id);
	
	print_r($id);
?>