<?
/**
* class CurlBasic v 0.1 d 12.08.2016
* 
* 
Список параметров
url 
useragent
ctimeout - максимальное время ожидания соединения
timeout - максмальное время ожидания ответа
maxredirs - максимальное количество редиректов
referer - откуда пришли

inteface_ip - с какого айпи сервера делать запрос
file - отпавляем файл, указываем путь до файла
put - для загрузки файла методом HTTP PUT
putfile - загружаем файл методом PUT
httpheaders передаются массивом ['User-Agent: aaaa','....']
ssl
postdata - отправляется массивом ['a'=>asd,....]
cookiefile - указываем файл для кук
cookie -  cookiename=value&... 
proxy - указываем прокси 178.79.184.158:80
proxysocks - если прокси сокс
proxy_userpwd - логин пароль от прокси user:pass



$Curl = new CurlBasic();
$Curl->url('http://reddit.nk5.ru/');
$Curl->makerequest();


*
*
*
*/

Class CurlBasic {
	
	function __construct() {
		$this->refresh();
    }
	
	function refresh() {
    	$this->p = [
	    	'useragent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:48.0) Gecko/20100101 Firefox/48.0',
	    	'ctimeout'=>10,
	    	'timeout'=>10,
	    	'referer'=>'',
	    	'maxredirs'=>5,
    	];		
	}
	
	function set($n,$s){
		$this->p[$n]=$s;
	}
	
	function setArray($a){
		foreach($a as $n=>$s)
			$this->p[$n]=$s;
	}
	
	
	function url($u){
		$this->p['url'] = $u;
	}
	
	function makerequest(){

			# Инициализируем запрос с урлом в массиве
			$this->ch = curl_init($this->p['url']);
			
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);						// возвращает веб-страницу
			curl_setopt($this->ch, CURLOPT_HEADER, 0);								// не возвращает заголовки
			curl_setopt($this->ch, CURLOPT_ENCODING, "");							// обрабатывает все кодировки
			
			curl_setopt($this->ch, CURLOPT_USERAGENT, $this->p['useragent']);		// useragent
			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->p['ctimeout']);	// таймаут соединения
			curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->p['timeout']);			// таймаут ответа
			curl_setopt($this->ch, CURLOPT_MAXREDIRS, $this->p['maxredirs']);		// останавливаться после 5-ого редиректа
			curl_setopt($this->ch, CURLOPT_REFERER, $this->p['referer']);			// От куда мы пришли
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		
			if(isset($this->p['httpheaders'])) 	curl_setopt ($this->ch, CURLOPT_HTTPHEADER, $this->p['httpheaders']);
			if(isset($this->p['put'])) 			curl_setopt($this->ch, CURLOPT_PUT, true);
			if(isset($this->p['inteface_ip'])) 	curl_setopt($this->ch, CURLOPT_INTERFACE, $this->p['inteface_ip']);
			if(isset($this->p['cookie'])) 		curl_setopt($this->ch, CURLOPT_COOKIE, $this->p['cookie']);

				
			if(isset($this->p['file'])){
				$fp = fopen($this->p['file'], 'w');
				curl_setopt($this->ch, CURLOPT_FILE, $fp);
			}

			
			if(isset($this->p['putfile'])){
				$fp = fopen ($this->p['putfile'], "r"); 

				curl_setopt($this->ch, CURLOPT_PUT, true);
				curl_setopt($this->ch, CURLOPT_INFILE, $fp);
				curl_setopt($this->ch, CURLOPT_INFILESIZE, filesize($this->p['putfile']));
			}
		
		
			if(isset($this->p['ssl'])){
				curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST ,0);
				#curl_setopt ($this->ch, CURLOPT_CAINFO, $this->p['ssl']);
		    }
			  
			 
			# Если что то отправлем методом POST
			if(isset($this->p['postdata'])){
				curl_setopt($this->ch, CURLOPT_POST, 1);
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->p['postdata']);
			}
			  
			# Если есть файл с куками от туда читаем и туда же пишем
			if(isset($this->p['cookiefile'])){
				curl_setopt($this->ch, CURLOPT_COOKIEFILE,$this->p['cookiefile']);
				curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->p['cookiefile']);
			}
			
			# Если есть прокси идем через прокси 178.79.184.158:80
			if(isset($this->p['proxy'])) 			curl_setopt($this->ch, CURLOPT_PROXY, $this->p['proxy']);
			if(isset($this->p['proxysocks'])) 		curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
			if(isset($this->p['proxy_userpwd']))	curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $this->p['proxy_userpwd']);


			$this->content = curl_exec($this->ch);
			$this->errno   = curl_errno($this->ch);
			$this->errmsg  = curl_error($this->ch);
			$this->header  = curl_getinfo($this->ch);
			curl_close($this->ch);
		
			if(isset($this->p['file'])) fclose($fp);
			
			
			
			return $this->content;
	}
	
	function getanswerHeader(){
		return $this->header;
	}
	
}
?>