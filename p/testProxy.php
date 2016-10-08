<?php

//Виды прокси
//Level 4 Элитные
//Level 3 Искажающие
//Level 2 Анонимные
//Level 1 Прозрачные
//Level 0 Редирект

$ip = $_GET['ip'];
$proxy = $_GET['proxy'];
$forwarded = "$ip, $proxy";
//$via = strstr ($_SEREVER['HTTP_VIA'], "$proxy");
//print($via);
//print($proxy);
print_r($_SERVER);
//print_r($_GET);

if ($_SERVER['HTTP_X_FORWARDED_FOR'] == $proxy && !$_SERVER['HTTP_VIA'] && $_SERVER['HTTP_X_REAL_IP'] == $proxy) { echo '4'; } 
else if ($_SERVER['HTTP_X_FORWARDED_FOR'] != $ip && $_SERVER['HTTP_X_FORWARDED_FOR'] != $proxy && $_SERVER['HTTP_X_FORWARDED_FOR'] != $forwarded && $_SERVER['HTTP_VIA'] && $_SERVER['REMOTE_ADDR'] != $ip ) { echo '3'; }
else if ($_SERVER['HTTP_X_FORWARDED_FOR'] == $proxy && $_SERVER['HTTP_VIA'] && $_SERVER['HTTP_X_REAL_IP'] == $proxy) { echo '2'; } 
else if ($_SERVER['HTTP_X_FORWARDED_FOR'] == $forwarded && $_SERVER['HTTP_VIA'] && $_SERVER['HTTP_X_REAL_IP'] == $proxy) { echo '1'; }
else { echo '0'; }
?>