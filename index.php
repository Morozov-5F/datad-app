<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	//errors
	//1 - не правильный запрос (нету api/)
	//2 - Bad request
	//3 - ошибка добавления в базу данных
	//4 - нету обязательных параметров
	
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
				
				$id = insert('users', ['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
				if ($id == 0) { echo 'error: 3'; }
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
				
				if ($users) {	
					$res['count'] = count($users);
					$res['users'] = $users;
				}
				else { $res['count'] = 0; }
				
				header('Content-Type: application/json; charset=utf-8');
				print_r(json_encode($res));
		    }
		    else { echo 'error: 2'; }
			break;	
			
		# Метод для получения пользователя по id
		case 'api/users.getByID': 
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				$ids = [];
				
				if (!isset($_GET['ids'])) { echo 'error 4'; exit; }

				$users = select("SELECT * FROM `users` WHERE id IN (".$_GET['ids'].")");
			
				$res['count'] = count($users);
				$res['users'] = $users ?? 123;


				header('Content-Type: application/json; charset=utf-8');
				print_r(json_encode($res));
		    }
		    else { echo 'error: 2'; }
			break;	
			
		default: echo 111;
	}
	
	
?>