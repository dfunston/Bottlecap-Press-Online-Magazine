<?php

$pageTitle = "Register new user";
$boolAdmin = true;
require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";

if(isset($_POST["register"])){
	
	$success=true;
	$errors=NULL;
	
	$reguser=$_POST["username"];
	$regemail=$_POST["email1"];

/*	$return=myConnect(0,0);
	
	if($return[1]!=0){
		$errors = "Error connecting to MySQL. " . $return[2];
		$success=false;
	}else{
		$myCon=$return[0];*/
	$query="SELECT 'adm_Username', 'adm_Email' FROM `bcrp_admin` WHERE 'adm_Username'=$reguser OR 'adm_Email'=$regemail";
	$userResults=$mysqli->query($query);

	if($userResults!=NULL){
		//Test if email or username is taken
		foreach($userData as $userResults){
			if($regemail == $userData['adm_Email']){
				$success = false;
				$errors = "Email address has already been used. Have you <a href='forgotpass.php'>forgotten your password?</a>";
			}else if($reguser == $userData['adm_Username']){
				$success = false;
				$errors = "Sorry, this username has already been used!";
			}
		}
	}//TODO: Gotta test this shiz. Dunno if it works.

	if($success==true){
		//Insert all data into the table
		require_once $_SERVER['DOCUMENT_ROOT'] . "/script/randhash.php";
		$password=lolzRandom(10);
		$passHash=crypt($password);
		
		if(!($stmt = $mysqli->prepare("INSERT INTO `bcrp_admin` (`adm_Username`, `adm_Password`, `adm_Email`, `adm_TempPass`)
				VALUES (?, ?, ?, 1)"))){
			//Handle this better
			$errors = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$success = false;
		}elseif(!($stmt->bind_param('sss', $reguser, $passHash, $regemail))) {
			//Handle this better
			$errors = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			$success = false;
		}elseif(!($stmt->execute())) {
			//Handle this better
			$errors = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			$success = false;
			$stmt->close();
		}else{
		$stmt->close();
		
		/*$query="INSERT INTO `bcrp_admin` (`adm_Username`, `adm_Password`, `adm_Email`, `adm_TempPass`)
				VALUES ('$reguser', '$passHash', '$regemail', '1')";
		if(mysqli_query($myCon, $query)==false){
			$errors = "Error: " . mysql_error();
			$success = false;
		}else{*/
			//Email user with login info
			$url="http://www.bottlecap.com/admin/login.php";
			$msg = "$reguser,\r\nCongratulations!  You have been added as an administrator at Bottlecap Press.  To complete the registration process, you will need to confirm your email address and update your password by clicking the link below, or copying and pasting it in to the address bar of your web browser.\r\n$url\r\nYou will need your username and temporary password to log in, at which point we will have you update your password.\r\nTemporary password: $password\r\nIf you were not expecting this email, you may either contact us or simply disregard this email.";
			if(mail($regemail, "Admin registration on Bottlecap", $msg)===false){
				$errors = "Error: Mail failed to send.";
				$success = false;
			}
		}
	}
	//mysqli_close($myCon);
	//}
}


if(isset($_POST["register"]) && $success==true){
	
	?>
		<div id="success">
			<h3>Registered Successfully!</h3>
			<p>Account was created successfully.  we have sent an email to <?php echo $_POST["email1"]; ?> with a confirmation of registration, a temporary password, and a link to finish the registration process.</p>
			<p>NOTE: Much of the above is currently a lie, because Daniel is still working on this shit.</p>
		</div>
	<?php

}else{

//TODO: Create javascript verification of entered information
?>
	<div id="regform">
	
	
	<?php
		if(isset($errors)){
			?><p id="error"><?php echo $errors; ?></p><?php
		}
	?>
	
	</p>
	<table>

		<form id="regForm" method="Post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	
			<tr>
				<td class="user">Username:</td>
				<td><input type="text" id="username" name="username" /></td>
			</tr>
			
			<!-------------------------------------------------------------------------------->
			
			<!-- There used to be password generation here, but that's going to be automated now. -->
			
			<!-------------------------------------------------------------------------------->			
			
			<tr>
				<td class="email">Email:</td>
				<td><input type="email" id="email1" name="email1" /></td>
			</tr>
			
			<tr>
				<td class="email">Repeat email:</td>
				<td><input type="email" id="email2" name="email2" /></td>
			</tr>
			
			<!-------------------------------------------------------------------------------->			
			
			<tr>
				<td colspan="2" align="center"><input type=button onClick="verify()" value="Submit" /></td>
			</tr>
		
			<input type="hidden" name="register" value="true" />
		
		</form>
	
	</table>
	
	</div>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script type="text/javascript">

	var verify = function()
	{
	
		//TODO: Get more checks in place
		/*
		
			Ideas for checks:
			
			*Verify email address is valid (account for periods and + text in email)
			*Password rulesets (min length, possibly rules for numbers, letters, and symbols)
			*Verify username and password are not identical
			
		
		*/

		var subFlag=true;
		var err="";
			
		var user=document.getElementById("username");
		//Passwords removed from account creation and made server side instead.
		//var pass1=document.getElementById("password1");
		//var pass2=document.getElementById("password2");
		var email1=document.getElementById("email1");
		var email2=document.getElementById("email2");

		if(user.value==""){
			err+="Please enter a username!<br>";
			//document.getElementsByClassName("user").style.color="red";
			$('td.user').css('color','red');
			subFlag=false;
		}
		/*
		//Password creation removed		
		if(pass1.value!=pass2.value){
			err+="Passwords do not match!<br>";
			//document.getElementsByClassName("pass").style.color="red";
			$('td.pass').css('color','red');
			subFlag=false;
		}else if(pass1.value==""){
			err+="Please enter a password!<br>";
			$('td.pass').css('color','red');
		} //TODO: check for password length here
		*/		
		if(email1.value!=email2.value){
			err+="Emails do not match!<br>";
			//document.getElementsByClassName("email").style.color="red";
			$('td.email').css('color','red');
			subFlag=false;
		}else if(email1.value==""){
			err+="Please enter an email address!<br>";
			$('td.email').css('color','red');
		} //TODO: Verify email here
			
		console.log(subFlag);			
		console.log(err);
			
		if(subFlag==true){
			//document.getElementById("regForm").submit;
			$('#regForm').submit();
		}else{
			$('p#error').html(err);
			console.log('Errors should be updated.');
		}
		
		//return 0;
	
	}

</script>

<?php
}
?>

</body>
</html>