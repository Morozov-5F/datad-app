<?php 
############################################################################
////		 		Функции для работы с базой даннных 					////
////		 		v 0.3 MySQLi 16.08.2016			 					////
############################################################################


# Устанавливаем подключение
$mysqli = new mysqli('p:localhost','hak','M0x3J7b2G0q3V9k3', 'hak');//'carsautoru');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
$mysqli->set_charset("utf8");


function query($q) {
	global $mysqli;
#	$s = microtime(true);
	$r = $mysqli->query($q);
#	mysqli_query($mysqli,"INSERT INTO `_perfomance_q` SET `time` = '".time()."', `query`= '".antisqlinj($q)."', `time_ex` = '".(microtime(true)-$s)."'");
	return $r;
}

function select($q) {
	$r = query($q);
	$e = [];
	while($row = $r->fetch_array(MYSQLI_ASSOC))
		$e[] = $row;
		
	return $e;
}

function selectRow($q) {
	return query($q)->fetch_array(MYSQLI_ASSOC);
}

function selectOneCol($q){
	return query($q)->fetch_row()[0];
}

function selectTable($table,$id='id'){
	foreach(select("SELECT * FROM `".$table."`") as $r)
		$out[$r[$id]]=$r;
	
	return @$out;
}

function selectWhere($table,$where_array,$limit=0){
	return select("SELECT * FROM `".$table."` WHERE ".array_to_query($where_array).($limit?" LIMIT ".$limit:''));	
}

function selectWhereRow($table,$where_array){
	return selectRow("SELECT * FROM `".$table."` WHERE ".array_to_query($where_array)." LIMIT 1 FOR UPDATE");	
}


function insert($table,$array){
	global $mysqli;
	
	query('INSERT INTO `'.$table.'` SET '.array_to_query($array,','));	

	return $mysqli->insert_id;
}

function update_or_insert($table,$array){
	global $mysqli;
	query('INSERT `'.$table.'` SET '.array_to_query($array,',').' ON DUPLICATE KEY UPDATE '.$q);	
	return $mysqli->insert_id;
}

function update($table,$array,$where_array=0){
	return query('UPDATE `'.$table.'` SET '.array_to_query($array,',').' '.($where_array!=0?'WHERE '.array_to_query($where_array):''));	
}


function db_block () {
	global $mysqli;
	$mysqli->autocommit(false);
}

function db_unblock () {
	global $mysqli;
	$mysqli->autocommit(true);
}



function antisqlinj ($i) {
	global $mysqli;
	return $mysqli->real_escape_string($i);
}


function array_to_query($array,$del = ' AND '){
	foreach($array as $k=>$r)
		$q[]="`".$k."`='".$r."'";	

	return implode($del,$q);
}



?>