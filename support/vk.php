<?php 
require './support/curl.php';

function vk_auth($login,$password,$last4tlf=0){

	
	$curl = new CurlBasic();
 	$curl->url('http://m.vk.com/');
 	$curl->setArray([
	 	'cookiefile' => './_temp/'.md5($login.$password),
	 	'referer' => 'http://m.vk.com/',
	 	'inteface_ip' => '188.138.94.101',

 	]);
	$content = $curl->makerequest();

	//if(strpos($o['content'],'Настройки')>0)
								//return 1;
	
	
	$curl->url('http://m.vk.com/login');
 	$content = $curl->makerequest();

	//$content = curl_basic($param);
	
	# Выдираем специальный хэш
	preg_match('/action="(.*)"/Uims',$content,$m);
	$url_to = str_replace('amp;','',@$m[1]);
	
	$curl->url($url_to);
	$curl->setArray([
	 	'referer' => 'http://m.vk.com/login',
	 	'postdata' => 'email='.$login.'&pass='.$password
 	]);
 	$content = $curl->makerequest();
 	$headers = $curl->getanswerHeader();
 		
	if(isset($headers['redirect_url'])) {
		$curl->url($headers['redirect_url']);
		$curl->setArray([
		 	'referer' => $url_to
	 	]);
	 	$content = $curl->makerequest();
	}
	
	if($last4tlf!=0){
		$curl->url('http://m.vk.com/login.php?act=security_check&to=&al_page=');
		$curl->setArray([
		 	'referer' => 'http://m.vk.com/',
		 	'cookiefile' => './_temp/'.md5($login.$password),
	 	]);
	 	$content = $curl->makerequest();
	 	echo '<pre>';
		print_R($content);
		exit;
		preg_match('/security_check&to=&hash=(.*)">/Uims',$content,$m);
		@$hash=$m[1];
		if(count($hash)>0){
			print_R(array(
						'url'=>'http://m.vk.com/login.php?act=security_check&to=&hash='.$hash.'',
						'cookiefile'=>'./_temp/'.md5($login.$password),
						'referer'=>'http://m.vk.com/login',
						'postdata'=>'code='.$last4tlf
						));
			
			$curl->url('http://m.vk.com/login.php?act=security_check&to=&hash='.$hash);
			$curl->setArray([
			 	'referer' => 'http://m.vk.com/login',
			 	'cookiefile' => './_temp/'.md5($login.$password),
			 	'postdata'=>'code='.$last4tlf
		 	]);
		 	$content = $curl->makerequest();
		}
	}
	
	$curl1 = new CurlBasic();
 	$curl1->url('http://m.vk.com/');
 	$curl1->setArray([
	 	'referer' => 'http://m.vk.com/'
 	]);
	$content1 = $curl1->makerequest();
	$headers1 = $curl->getanswerHeader();

	if(isset($headers1['redirect_url'])){
	
		$curl1->url($headers1['redirect_url']);
	 	$curl1->setArray([
		 	'referer' => 'http://m.vk.com/'
	 	]);
		$content1 = $curl1->makerequest();
		print_r($content1);
	}
	
	if(strpos($content1,'Настройки')>0){
		return 1;
	}elseif(isset($content1['error'])&&$content1['error']=='cycle'&&$content1['url']='http://m.vk.com/login?act=blocked'){
		return 2; # проверка телефона
	}else{
		return 3;
	}
}



?>