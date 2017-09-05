<?php

	/**********************************
	**	Admin forgot password Page	 **
	**********************************/
	
	$pageTitle = "Bottlecap Admin Forgot Password";
	$boolAdmin = true;
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
	
	//Reset password code, modified from login script
	
	
	if(isset($_POST['forgotpass'])){
		$success = false;
		//All of this only runs if the user has posted information from the login form
		$email=$_POST["email"];
		$stmt = $mysqli->stmt_init();
		$stmt->prepare("SELECT * FROM `bcrp_admin` WHERE `adm_Email`=?");
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows==0){
			//The following is for debug purposes ONLY
			$response.="No user found for specified email address!";
			//Use the following for production
			//$response.="Incorrect username or password!";
			$success=false;
		}else{
			/*
			
				Okay, I'm going to use this space to make sense of my thoughts here.
				Basically, I'm going to make a... 100 character string of random letters and numbers using RandHash and email a link to the client that redirects to /admin/account.php
				/script/adminpanel.php will verify email and the 100 character string match (the string being stored in adm_ForgotPass).  
				As long as everything is good, it will consider the client logged in, and adm_TempPass will be set to 1 so that client absolutely must change their password before continuing.
				
			*/
			echo "Found user ";
			require $_SERVER['DOCUMENT_ROOT'] . "/script/randhash.php";
			$forgotPassToken = lolzRandom(100);
			$forgotPass = $mysqli->prepare("UPDATE `bcrp_admin` SET `adm_ForgotPassToken`=? WHERE `adm_Email`=?");
			$forgotPass->bind_param('ss', $forgotPassToken, $email);
			if($forgotPass->execute()==false){
				$response.="Error: Unable to update information.  Password reset failed!";
				$success=false;
			}else{
				$success=true;
				//Mail user password reset instructions
				$urlemail=htmlspecialchars($email);
				$url="http://www.bottlecap.com/admin/account.php?email=$urlemail&forgotPassToken=$forgotPassToken";
				$msg = "
					Someone has requested a password reset for your account on Bottlecap.  If this was you, use the following link to perform the password reset:
					$url
					If this was not you, you may disregard this email, though it is advised you change your password to ensure nobody may get into your account.
					Sincerely,
					Bottlecap Admin
					";
				if(mail($email, "Bottlecap: Forgot Password", $msg)===false){
					$response .= "Error: Mail failed to send.";
					$success = false;
				}
			}
			$forgotPass->close();
		}
		$stmt->close();
	}
	
	
	if(isset($success) && $success == true){
		?>
		
		<div class="centerwindow" id="account">
			<p>Password reset successfully.  Please check your email for the next step!</p>
		</div>
		
		<?php
	}else{
	?>
	
	<div class="centerwindow" id="account">
		<form method="Post" action="<?php echo $self; ?>">
			<p id="login">Enter email address</p>
			<?php if(isset($response) && $response != ""){ ?> <p id="response" style="position:relative; z-index: 1000 !important;"><?php echo $response; ?></p><?php } ?>
			<input id="email" type="email" placeholder="email address" name="email" />
			<input type="hidden" name="forgotpass" value="true" />
			<input id="submit" type="submit" name="submit" value="Submit." />	
		</form>
	</div>

	<?php
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>