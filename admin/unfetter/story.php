<?php

	//Hold up, before we do anything, check to see if $_GET has the story ID, else go back to the Index page
	if(!isset($_GET["storyID"])){
		//Redirect
		header("Location: http://www.bottlecap.com/admin/unfetter/index.php");
		die();
	}
	
	/**********************************
	**		Story display page		 **
	**********************************/
	
	/*
	
		Doing things a bit backwards here.  Getting the story before the header so we can insert the story title as the title of the page
	
	*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . "/script/mysql.php";
	
	$storyID = $_GET["storyID"];
	
	$query = "SELECT A1.story_Name, A1.story_Body, A2.auth_Name, A3.issue_Name, A3.issue_ID
		FROM `bcrp_stories` A1, `bcrp_authors` A2, `bcrp_issues` A3
		WHERE A1.story_ID = ? 
		AND A1.auth_ID = A2.auth_ID 
		AND A1.issue_ID = A3.issue_ID";
	$stmt = $mysqli->stmt_init();
	$stmt->prepare($query);
	$stmt->bind_param('s', $storyID);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	if($result->num_rows==0){
		$storyFound=false;
		$pageTitle = "No story found!";
	}else{
		$storyFound=true;
		while($row=$result->fetch_array(MYSQLI_ASSOC)){
			$storyTitle = $row["story_Name"];
			$storyBody = $row["story_Body"];
			$authName = $row["auth_Name"];
			$issueName = $row["issue_Name"];
			$issueID = $row["issue_ID"];
			$pageTitle = '"' . $storyTitle . '" by ' . $authName . " | " . $issueName . " ";
		}
	}
	
	$boolAdmin = true;
	$previewSite = true;
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
	if($storyFound==true){
?>
<div id="storycontainer">

<div id="storyheader">
<h3><?php echo $storyTitle; ?></h3>
<h5><?php echo $authName; ?></h5>
</div>
<div id="storycontent">
<?php echo $storyBody; ?>
</div>
</div>
<?php	
	}else{
?>
	<h3>No story found!</h3>
	<p>We didn't find anything.  Sorry about that!  Maybe try going <a href="/index.php">back to the main page.</a></p>
<?php
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>