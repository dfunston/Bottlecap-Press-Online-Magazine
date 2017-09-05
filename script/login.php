<?php

	/**********************************
	**		Admin Login Page		 **
	**********************************/
	
	$pageTitle = "Bottlecap Admin Login";
	$boolAdmin = true;
	
	//TODO:  Admin login/session code insert here
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
?>

<div id="account">

	<table id="login">
		<tr>
			<td colspan="2" align="center">
				<p id="response"><?php echo $response; ?></p>
			</td>
		</tr>
		<form method="Post" action="<?php echo $self; ?>">
	
		<tr>
			<td>Username:</td><td><input type="text" name="user" /></td>
		</tr>
		<tr>
			<td>Password:</td><td><input type="password" name="pass" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="hidden" name="sub" value="true" />
				<input type="submit" name="submit" value="Submit." />
			</td>
		</tr>
		
		</form>
	</table>

</div>

<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>