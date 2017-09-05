<?php

/*

Admin Panel Script

Contains session handlers.  To be included in any admin panel page after Header.

*/

require_once $_SERVER['DOCUMENT_ROOT'] . "/script/info.php";

$response="";
$forgotPass=false;

session_start();


if(isset($_SESSION["token"]) || isset($_SESSION["forgotPassToken"])){
	if(!isset($_SESSION["user"]) || !isset($_SESSION["email"]) || !isset($_SESSION["newUser"])){
		$response="Invalid cookie!  Logging out.";
		$loggedIn=false;
		session_destroy();
	}else if(isset($_POST["logout"])){
		$stmt = $mysqli->prepare("UPDATE `bcrp_admin` SET `adm_Token`='' WHERE `adm_Username`=?");
		$stmt->bind_param('s', $_SESSION["user"]);
		$stmt->execute();
		$stmt->close();
		session_destroy();
		$loggedIn=false;
	}else{
		$stmt = $mysqli->prepare("SELECT `adm_Token`, `adm_ForgotPassToken` FROM `bcrp_admin` WHERE `adm_username`=?");
		$stmt->bind_param('s', $_SESSION["user"]);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows==0){
			//The following is for debug purposes ONLY
			$response="Cookie invalid! Logging out.";
			//Use the following for production
			//$response="Incorrect username or password!";
			$loggedIn=false;
			session_destroy();
		}else{
			while($row=$result->fetch_array(MYSQLI_ASSOC)){
				if($_SESSION["token"]==$row["adm_Token"]){
					$loggedIn=true;
				}else if(isset($_SESSION["forgotPassToken"]) && $_SESSION["forgotPassToken"]==$row["adm_ForgotPassToken"]){
					$loggedIn=true;
					$forgotPass=true;
				}else{
					$response="Invalid session token!  Logging out.";
					$loggedIn=false;
					session_destroy();
				}
			}
		}
		$stmt->close();
	}
}else if(isset($_POST['login'])){
	//All of this only runs if the user has posted information from the login form
	
	$username=$_POST["user"];
	$rawPass=$_POST["pass"];
	/*$query="SELECT * FROM `bcrp_admin` WHERE `adm_Username`='$username' OR `adm_Email`='$username'";
	$result=NULL;
	$result=$mysqli->query($query);*/
	$stmt = $mysqli->stmt_init();
	$stmt->prepare("SELECT * FROM `bcrp_admin` WHERE `adm_Username`=? OR `adm_Email`=?");
	$stmt->bind_param('ss', $username, $username);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows==0){
		//The following is for debug purposes ONLY
		$response="No such username found!";
		//Use the following for production
		//$response="Incorrect username or password!";
		$loggedIn=false;
		session_destroy();
	}else{
		while($row=$result->fetch_array(MYSQLI_ASSOC)){
			$passHash=$row["adm_Password"];
			$username=$row["adm_Username"];
			$email=$row["adm_Email"];
			$newUser=$row["adm_TempPass"];
		}
		if($passHash!=crypt($rawPass, $passHash)){
			//The following is for debug purposes ONLY
			$response.="Incorrect password!";
			//Use the following for production
			//$response="Incorrect username or password!";
			$loggedIn=false;
			session_destroy();
		}else{
			//TODO: start session
			require $_SERVER['DOCUMENT_ROOT'] . "/script/randhash.php";
			//Session constructor
			$token = lolzRandom(25);
			//$query="UPDATE `bcrp_admin` SET `adm_Token`='$token' WHERE `adm_Username`='$username' OR `adm_Email`='$username'";
			$startSession = $mysqli->prepare("UPDATE `bcrp_admin` SET `adm_Token`=? WHERE `adm_Username`=? OR `adm_Email`=?");
			$startSession->bind_param('sss', $token, $username, $username);
			if($startSession->execute()==false){
				$response.="Error: Unable to update token.  Login failed!";
				$loggedIn=false;
				session_destroy();
			}else{
				$_SESSION["user"]=$username;
				$_SESSION["email"]=$email;
				$_SESSION["token"]= $token;
				$response.="Logging in now.";
				$loggedIn=true;
				if($newUser==1){
					//redirect to account page to update password
					$_SESSION["newUser"]=true;
				}else{
					$_SESSION["newUser"]=false;
				}
			}
			$startSession->close();
		}
	}
	$stmt->close();
}else if(isset($_GET["forgotPassToken"]) && $_SERVER["PHP_SELF"] == "/admin/account.php"){
	//Handle forgotten password session here.  This is a delicate operation and must be handled with the utmost care.
	$email = htmlspecialchars_decode($_GET["email"]);
	$forgotPassToken = $_GET["forgotPassToken"];
	$stmt = $mysqli->prepare("SELECT `adm_Username`, `adm_Email`, `adm_ForgotPassToken` FROM `bcrp_admin` WHERE `adm_Email`=?");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows==0){
		//Error out
		$response .= "No such email found!";
		$loggedIn=false;
		session_destroy();
	}else{
		while($row=$result->fetch_array(MYSQLI_ASSOC)){
			if($row["adm_ForgotPassToken"]!=$forgotPassToken){
				//Error out
				$response .= "Invalid token recieved! ";
				$loggedIn=false;
				session_destroy();
				//Consider deleting token from admin table in the future
			}else{
				$_SESSION["user"]=$row["adm_Username"];
				$_SESSION["email"]=$email;
				$_SESSION["token"]="";
				$_SESSION["forgotPassToken"]=$forgotPassToken;
				$_SESSION["newUser"]=true;
				$loggedIn=true;
				$forgotPass=true;
			}
		}
	}
}else{
	$loggedIn=false;
	session_destroy();
}

if($loggedIn==false && $_SERVER["PHP_SELF"]!="/admin/login.php"){
	if($_SERVER["PHP_SELF"]!="/admin/forgotpass.php"){
		//Change when out of debug and into production, as URL will likely change as well.
		header("Location: ". $siteRoot . "admin/login.php");
		die();
	}
}

if($loggedIn==true && $_SESSION["newUser"]=="1" && $_SERVER["PHP_SELF"]!="/admin/account.php"){
	//User needs to update password.  Redirect to account.php
	header("Location: ". $siteRoot . "admin/account.php");
	die();
}

if($loggedIn==true && ($_SERVER["PHP_SELF"]=="/admin/login.php" || $_SERVER["PHP_SELF"]=="/admin/forgotpass.php")){
	//Navigate from login screen to index.php, for we no longer need the login screen
	header("Location: ". $siteRoot . "admin/index.php");
	die();
}

?>