<?php
	require './lib/db.php';
	
	$phone = '8 (918) 344-38-15';
	
	echo $phone.'<br/><br/>';
	
	preg_match_all("#8 \((\d+)\) (.*)#", $phone, $mas);
	$code = $mas[1][0];
	
	preg_match_all("#\d#", $mas[2][0], $ph);
	$phone_str = '';
	foreach ($ph[0] as $num) {
		$phone_str .= $num;
	}
	
	echo 'Code: '.$code.'<br/>';
	echo 'Phone: '.$phone_str.'<br/><br/>';
	
 	$phoneRow = selectrow("SELECT * FROM `phone_code` WHERE `phone_code` LIKE '".$code."' LIMIT 1");
	if (!$phoneRow) 
	{ 
		echo 'Код не найден<br/>';
		$phone_id = insert('phone_code', ['phone_code' => $code]);
		
	}
	else {
		echo 'Код найден<br/>';
		$phone_id = $phoneRow['phone_code_id'];
	}
	echo 'code_id: '.$phone_id;
 	update('adv_info', ['phone' => $phone_str, 'phone_code_id' => $phone_id], ['id' => 846493718]);
 	?>	