<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	//errors
	//1 - не правильный запрос (нету api/)
	//2 - Bad request
	//4 - нету обязательных параметров
	// -1 - метод не найден
	// 5 - пользователь не найден
	// 6 - нету параметров
	
	//echo '<pre>';
	//print_r(json_encode($_SERVER['REQUEST_METHOD']));
	//echo json_encode($_POST);
	//print_r($_GET['q']);
	
	
	preg_match("#api/(.*)#", $_GET['q'], $matches);
	if (empty($matches)) { echo 'error 1'; exit; }
	
	switch ($_GET['q']) {
		# Метод для регистрации аккаунта
		case 'api/registAcc': 
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$data = json_decode(file_get_contents("php://input"), true);
				
				if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
					$res['error'] = 'BAD_PARAMETERS';
					$res['error_code'] = 4; 
					
					header('Content-Type: application/json; charset=utf-8');
					print_r(json_encode($res));
					
					exit;
				}
				
				$id = insert('users', ['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
				if ($id == 0) {
					$res['error'] = 'ERROR_ADD_TO_DB';
					$res['error_code'] = 6; 
					
					header('Content-Type: application/json; charset=utf-8');
					print_r(json_encode($res));
				}
				else {
					echo 'sucess registration';	
				}
		    }
		    else { echo 'error: 2'; }
			break;
			
		# Метод для получения списка пользователей
		case 'api/users.get':
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				$limit = 100; //дефолтный лимит
				$after = 0;
				
				if (isset($_GET['limit']) && $_GET['limit'] != 0) {
					$limit = $_GET['limit'];
				}
				
				if (isset($_GET['after'])) {
					$after = $_GET['after'];
				}
				
				$users = select("SELECT * FROM `users` LIMIT ".$after.", ".$limit);
				
				$res['count'] = count($users);
				$res['users'] = $users;
			
				header('Content-Type: application/json; charset=utf-8');
				print_r(json_encode($res));
		    }
		    else { echo 'error: 2'; }
			break;	
			
		# Метод для получения пользователя по id
		case 'api/users.getByID': 
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				if (!isset($_GET['ids'])) { $res['error'] = 'BAD_PARAMETERS';
					$res['error_code'] = 4; 
					
					header('Content-Type: application/json; charset=utf-8');
					print_r(json_encode($res));
					exit;
				}

				$users = select("SELECT * FROM `users` WHERE id IN (".$_GET['ids'].")");
			
				$res['count'] = count($users);
				$res['users'] = $users;
				
				header('Content-Type: application/json; charset=utf-8');
				print_r(json_encode($res));
		    }
		    else { echo 'error: 2'; }
			break;	
			
		# Проверка email на уникальность
		case 'api/checkAccount': 
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				
				if (!isset($_GET['email'])) { echo 'error 4'; exit; }

				$user = selectrow("SELECT * FROM `users` WHERE email LIKE '".$_GET['email']."' LIMIT 1");
			
				if (!$user) 
				{ 
					$res['error'] = 'USER_NOT_FOUND';
					$res['error_code'] = 5;
				}
				else { $res['user'] = $user; }
				
				header('Content-Type: application/json; charset=utf-8');
				print_r(json_encode($res));
		    }
		    else { echo 'error: 2'; }
			break;
		
		# Логин
		case 'api/login': 
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$data = json_decode(file_get_contents("php://input"), true);
				
				if (!isset($data['email']) || !isset($data['password'])) {
					$res['error'] = 'BAD_PARAMETERS';
					$res['error_code'] = 4; 
					
					header('Content-Type: application/json; charset=utf-8');
					print_r(json_encode($res));
					
					exit;
				}
				
				
				
				$curl = new CurlBasic();
			 	//$curl->url('http://nk5.ru/api/checkAccount?email='.$data['email']);
			 	$curl->url('google.com');
			
				$page = $curl->makerequest();
				print_r($page);
				
				/*
$user = select('users', ['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
				if ($id == 0) {
					$res['error'] = 'ERROR_ADD_TO_DB';
					$res['error_code'] = 6; 
					
					header('Content-Type: application/json; charset=utf-8');w
					print_r(json_encode($res));
				}
				else {
					echo 'sucess registration';	
				}
*/

		    }
		    else { echo 'error: 2'; }
			
			break;	
			
		default: echo 'error: -1';
	}
	
	
?>