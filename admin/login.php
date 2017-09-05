<?php

	/**********************************
	**		Admin Login Page		 **
	**********************************/
	
	$pageTitle = "Bottlecap Admin Login";
	$boolAdmin = true;
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
?>

<div class="centerwindow" id="login">
	<form method="Post" action="<?php echo $self; ?>">
		<p id="login">Please log in</p>
		<?php if(isset($response) && $response != ""){ ?> <p id="response" style="position:relative; z-index: 1000 !important;"><?php echo $response; ?></p><?php } ?>
		<input id="username" type="text" placeholder="Username" name="user" />
		<input id="password" type="password" placeholder="Password" name="pass" />
		<p id="forgotpass"><a href="/admin/forgotpass.php">Forgot password?</a></p>
		<input type="hidden" name="login" value="true" />
		<input id="submit" type="submit" name="submit" value="Submit" />	
	</form>
</div>

<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>