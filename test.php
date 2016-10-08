<?php
	require './support/dataBase.php';
	
	$cat_id = '';
	$socialID = '';
	$price = 12000000;
	$limit = 100; //дефолтный лимит
	$after = 0;
	
	$str = '';
	if ($socialID != '') {
		$str = "`socialID` IN (".$socialID.") AND";
		
		if ($cat_id != '') {
			$str .= "`categoryID` IN (".$cat_id.") AND";
		}
	}
	else if ($cat_id != '') {
			$str = "`categoryID` IN (".$cat_id.") AND";
	}
	
	$users = select("SELECT * FROM `providers` WHERE ".$str." `price` <= ".$price." LIMIT ".$after.", ".$limit);
	
	$res['count'] = count($users);
	$res['users'] = $users;

	print_r($res);
	
?>