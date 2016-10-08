<?php
	require './support/dataBase.php';
	require './support/curl.php';
	
	//errors
	//1 - не правильный запрос (нету api/)
	//2 - Bad request
	
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
				
				insert('users', ['name' => $data['name'], 'login' => $data['login'], 'email' => $data['email'], 'password' => $data['password']]);
			    print_r($data);
		    }
		    else { echo 'error: 2'; }
			break;
		default: echo 111;
	}
	
	
	
?>