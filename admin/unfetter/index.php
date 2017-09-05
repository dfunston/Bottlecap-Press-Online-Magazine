<?php

	/**********************************
	**		 Main Site Index		 **
	**********************************/
	
	$pageTitle 		= "Home";
	$boolAdmin 		= true;
	$previewSite	= true;
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	

	/*
	
		Things I want here:
			Announcement of Bottlecap issues
			author showcase
			newest stories
			Link to buy
			
			Not in that order
			
	
	*/

	$query = "SELECT * FROM `bcrp_index` WHERE `index_key` = 0";
	$content = $mysqli->query($query);
	while($row = $content->fetch_array(MYSQLI_ASSOC)){
		echo $row["index_html"];
	}

	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>