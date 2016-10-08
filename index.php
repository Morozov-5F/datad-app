<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	//errors
	//1 - не правильный запрос (нету api/)
	//2 - Bad request
	//3 - ошибка добавления в базу данных
	
	//echo '<pre>';
	//print_r(json_encode($_SERVER['REQUEST_METHOD']));
	//echo json_encode($_POST);
	//print_r($_GET['q']);
	
		

	preg_match("#api/(.*)#", $_GET['q'], $matches);
	if (empty($matches)) { echo 'error 1'; exit; }
	
	switch ($_GET['q']) {
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
			
		case 'api/getUsers': 
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				$limit = 100; //дефолтный лимит
				$after = 0;
				if (isset($_GET['limit']) && $_GET['limit'] != 0) {
					$limit = $_GET['limit'];
				}
				
				$users = select("SELECT * FROM `users` LIMIT ".$after.", ".$limit);
				
				header('Content-Type: application/json; charset=utf-8');
				print_r(json_encode($users));
		    }
		    else { echo 'error: 2'; }
			break;	
			
		default: echo 111;
	}
	
	
?>