<?php

function myConnect($user, $data){
	
	/*
	
	Get the MySQL login information
	
	PLEASE NOTE:  This file will NOT be included in the Git repository
	This file must contain three variables:  $sql_user, $sql_pass, and $sql_db,
	which need to contain the MySQL username, password, and database, respectively.
	
	*/
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/mysql_sensitive_info.php"; 
	
	//Server location.  Shouldn't need changing generally.
	$host='localhost';
	
	//Connect or die
	//$return[0]=mysqli_connect($host, $sql_user, $sql_pass, $sql_db);
	$return[0] = new mysqli($host, $sql_user, $sql_pass, $sql_db);
	
	if($return[0]->connect_errno){
		$return[1]=-1;
		$return[2]="Error: Failed to connect to MySQL: (" . $return[0]->connect_errno . ") " . $return[0]->connect_error;
		$return[0]=NULL;
	}else{
		$return[1]=0;
		$return[2]='OK';
	}
	
	return $return;
	
}

$mySQLConnectResult = myConnect(0,0);
if($mySQLConnectResult[1]!=0){
	echo "MySQL Error! " . $mySQLConnectResult[2];
	die();
}else{
	$mysqli = $mySQLConnectResult[0];
}

?>