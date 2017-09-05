<?php

	/**********************************
	**		Account management		 **
	**********************************/
	
	$pageTitle = "Account management";
	$boolAdmin = true;
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
if(isset($_POST["update"])){
	require_once $_SERVER['DOCUMENT_ROOT'] . "/script/mysql.php";
	
	$success=true;
	$response=NULL;
	
	$updateUser=$_SESSION["user"];
	if(isset($_POST["email1"])){
		$updateEmail=true;
		$email=$_POST["email1"];
	}else{
		$updateEmail=false;
	}
	
	if(isset($_POST["password1"])){
		$updatePassword=true;
		$updatePass=crypt($_POST["password1"]);
	}else{
		$updatePassword=false;
	}
	
	if($updateEmail==false && $updatePassword==false){
		//fail
	}else{
		//TODO: FIGURE OUT WHY FORGOTPASS IS NOT BEING SET WHEN REQUESTING PASSWORD CHANGE
		if($forgotPass==false){
			$stmt = $mysqli->prepare("SELECT * FROM `bcrp_admin` WHERE `adm_Username`=?");
			$stmt->bind_param('s', $updateUser);
			$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows==0){
				$response .= "Username was somehow incorrect.  This should never happen.  Critical failure.";
			}else{
				while($row=mysqli_fetch_array($result)){
					$passHash=$row["adm_Password"];
				}
				if($passHash!=crypt($_POST["currpass"], $passHash)){
					$response .= "Password was incorrect!";
				}else{
				
					//Start new code
					if($updateEmail==true && $updatePassword==true){
						if(!($stmt->prepare("UPDATE `bcrp_admin` SET `adm_Email`=?, `adm_Password`=?, `adm_TempPass`='0', `adm_ForgotPassToken`='' WHERE `adm_Username`=?"))){
							//TODO: error catch
						}elseif(!($stmt->bind_param('sss', $email, $updatePass, $updateUser))){
							//TODO: error catch
						}
					}elseif($updateEmail==false && $updatePassword==true){
						if(!($stmt->prepare("UPDATE `bcrp_admin` SET `adm_Password`=?, `adm_TempPass`='0', `adm_ForgotPassToken`='' WHERE `adm_Username`=?"))){
							//TODO: error catch
						}elseif(!($stmt->bind_param('ss', $updatePass, $updateUser))){
							//TODO: error catch
						}
					}else if($updateEmail==true && $updatePassword==false){
						if(!($stmt->prepare("UPDATE `bcrp_admin` SET `adm_Email`=?, `adm_TempPass`='0', `adm_ForgotPassToken`='' WHERE `adm_Username`=?"))){
							//TODO: error catch
						}elseif(!($stmt->bind_param('ss', $email, $updateUser))){
							//TODO: error catch
						}
					}
					if(!$stmt->execute()) {
						echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					}else{
						$response .= "Information was updated succeffully!";
						$_SESSION["newUser"]=0;
					}
					$stmt->close();
				}
			}
		}else{
			$stmt = $mysqli->prepare("UPDATE `bcrp_admin` SET `adm_Password`=?, `adm_TempPass`='0', `adm_ForgotPassToken`='' WHERE `adm_Username`=?");
			$stmt->bind_param('ss', $updatePass, $updateUser);
			$stmt->execute();
			$stmt->close();
			$_SESSION["fogotPassToken"]=NULL;
		}
	}
}
?>
	<div class="centerwindow">
		<?php if(isset($response)){ echo "<p id='error'>$response</p>"; } ?>
		<form id="updateForm" method="Post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			
			<!-------------------------------------------------------------------------------->	
			
			<?php if($_SESSION["newUser"]==true){ ?>
				<p class="label">Please update your current password before continuing:</p>
			<?php } ?>
			<?php if($forgotPass==false){ ?>
				<p class="label">Current Password:</p>
				<input type="password" id="currpass" name="currpass" />
			<?php } ?>
			
			<p class="label">New Password:</p>
			<input type="password" id="password1" name="password1" />
				
			<p class="label">Repeat password:</p>
			<input type="password" id="password2" name="password2" />
			
			<!-------------------------------------------------------------------------------->
			<?php
			if($_SESSION["newUser"]==false){
			?>
			<p class="label">Email:</p>
			<input type="email" id="email1" name="email1" />
			
			<p class="label">Repeat email:</p>
			<input type="email" id="email2" name="email2" />
			<?php } ?>
			
			<!-------------------------------------------------------------------------------->	
			<input type="hidden" name="update" value="true" />
			
			<input type=button onClick="verify()" value="Submit" />
			
		
		</form>
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
		
		//Cannot change username after creation	
		//var user=document.getElementById("username");
		<?php if($forgotPass==false){ ?>
		var currpass=document.getElementById("currpass");
		<?php } ?>
		var pass1=document.getElementById("password1");
		var pass2=document.getElementById("password2");
		var upPass=true;
		<?php if($_SESSION["newUser"]==false){ ?>
		var email1=document.getElementById("email1");
		var email2=document.getElementById("email2");
		var upEmail=true;
		<?php } ?>

		/*
		//Removed username verification
		if(user.value==""){
			err+="Please enter a username!<br>";
			//document.getElementsByClassName("user").style.color="red";
			$('td.user').css('color','red');
			subFlag=false;
		}*/
		<?php if($forgotPass==false){ ?>
		if(currpass.value==""){
			err+="Enter your password to confirm changes!<br>";
			$('td.currpass').css('color','red');
			subFlag=false;
		}
		<?php } ?>
		if(pass1.value != "" && pass1.value!=pass2.value){
			err+="Passwords do not match!<br>";
			$('td.pass').css('color','red');
			subFlag=false;
		}else if(pass1.value == ""){
			upPass==false;
		<?php if($forgotPass==false){ ?>
		}else if(pass1.value == currpass.value){
			err += "Please use a different password than your current one!<br>";
			$('td.pass').css('color','red');
			$('td.currpass').css('color','red');
			subFlag=false;
		}
		<?php }else{ ?>
		}
		<?php } ?>
		<?php if($_SESSION["newUser"]==false){ ?>
		if(email1.value != "" && email1.value!=email2.value){
			err+="Emails do not match!<br>";
			//document.getElementsByClassName("email").style.color="red";
			$('td.email').css('color','red');
			subFlag=false;
		}else if(email1.value == ""){
			upEmail==false;
		}
		if(upPass==false && upEmail==false){
			err+="Need to update at least one item before submitting!<br />";
			$('td.pass').css('color','red');
			$('td.email').css('color','red');
			subFlag=false;
		}
		<?php } ?>
			
		console.log(subFlag);			
		console.log(err);
			
		if(subFlag==true){
			//document.getElementById("regForm").submit;
			$('#updateForm').submit();
		}else{
			$('p#error').html(err);
			console.log('Errors should be updated.');
		}
		
		//return 0;
	
	}

	</script>
<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>
