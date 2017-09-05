<?php

	/**********************************
	**			  New Story 		 **
	**********************************/
	
	$pageTitle = "New story";
	$boolAdmin = true;
	//$textAreaLoader = true;
	
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
	/*
	
		How this page gon' work:
		1) check to see if story has been submitted.  If yes, run the upload.  If no, goto step 2
		2) Check to see if $_GET is set for authID or issueID.  If yes, defualt is set for select box.  If no, select the default non-option
		3) Get list of authors and issues, build select boxes from them.
	
	*/
	
	//Handle input
	if(isset($_POST["newstory"])){
		//Modify all of this for story and not author
		$storyTitle = $_POST['storyname'];
		$storyBody = $_POST['storybody'];
		$author = $_POST["authList"];
		$issue = $_POST["issueList"];
		
		if($author==0 || $issue==0 || $storyTitle=="" || $storyBody==""){
			$posted = false;
			$response = "";
			if($storyTitle==""){
				$response .= "Please enter story title! ";
			}
			if($storyBody==""){
				$response .= "Please enter story content! ";
			}
			if($author==0){
				$response .= "Please select author! ";
			}
			if($issue==0){
				$response .= "Please select issue! ";
			}
		}else{
			
			//TODO: Implement better error handling
			$stmt = $mysqli->prepare('INSERT INTO `bcrp_stories`(`story_Name`, `story_Body`, `auth_ID`, `issue_ID`) VALUES (?, ?, ?, ?)');
			$stmt->bind_param('ssii', $storyTitle, $storyBody, $author, $issue);
			$stmt->execute();
			$storyID = $stmt->insert_id;
			?>
				
			<div class="centerwindow" id="newissue">
				<span class="label">"<?php echo $storyTitle; ?>" was successfully added!</span>
				<span class="label">Story ID is <?php echo $storyID; ?></span>
				<span class="label">Would you like to <a href="/admin/newstory.php">add another story?</a></span>
			</div>
			
			<?php
			$stmt->close();
		
			$posted=true;
		
		}
	}
	if(!isset($posted) || $posted==false){
		//Run both of the queries now, store the results in two separate result arrays, and do what needs to be done after
		//This will make things easier
		$stmt = $mysqli->stmt_init();
		$stmt->prepare("SELECT `auth_ID`, `auth_Name` FROM `bcrp_authors` ORDER BY `auth_ID` ASC");
		$stmt->execute();
		$authorList = $stmt->get_result();
		$stmt->prepare("SELECT `issue_ID`, `issue_Name` FROM `bcrp_issues` ORDER BY `issue_ID` ASC");
		$stmt->execute();
		$issueList = $stmt->get_result();
		$stmt->close();
		
		//Check to make sure author and issue results lists are not empty
		if($issueList->num_rows==0){
			?>
			<div class="centerwindow">
				<span class="label">No issues found!  Please <a href="/admin/newissue.php">add an issue before continuing!</a></span>
			</div>
			<?php
		}else if($authorList->num_rows==0){
			?>
			<div class="centerwindow">
				<span class="label">No authors found!  Please <a href="/admin/newauthor.php">add an author before continuint!</a></span>
			</div>
			<?php
		}else{
			//Check to see if there is any $_GET information, else get default values
			
			if(isset($_POST["authList"])){
				$defAuth=$_POST["authList"];
			}else if(isset($_GET["authID"])){
				$defAuth=$_GET["authID"];
			}else{
				$defAuth=0;
			}
			
			if(isset($_POST["issueList"])){
				$defAuth=$_POST["issueList"];
			}else if(isset($_GET["issueID"])){
				$defIss=$_GET["issueID"];
			}else{
				$defIss=0;
			}
			
			//Get the lists of authors and issues, put them into variables we will echo later
			
			$issueSelect = "<select name='issueList' id='issueList'>";
			if($defIss==0){
				$issueSelect .= "<option value='0' selected='selected'>-- Select Issue --</option>";
			}else{
				$issueSelect .= "<option value='0'>-- Select Issue --</option>";
			}
			while($row = $issueList->fetch_array(MYSQLI_ASSOC)){
				if($row["issue_ID"]==$defIss){
					$issueSelect .= "<option value='" . $row["issue_ID"] . "' selected='selected'>" . $row["issue_Name"] . "</option>";
				}else{
					$issueSelect .= "<option value='" . $row["issue_ID"] . "'>" . $row["issue_Name"] . "</option>";
				}
			}
			$issueSelect .= "</select>";
			
			$authSelect = "<select name='authList' id='authList'>";
			if($defAuth==0){
				$authSelect .= "<option value='0' selected='selected'>-- Select Author --</option>";
			}else{
				$authSelect .= "<option value='0'>-- Select Author --</option>";
			}
			while($row = $authorList->fetch_array(MYSQLI_ASSOC)){
				if($row["auth_ID"]==$defAuth){
					$authSelect .= "<option value='" . $row["auth_ID"] . "' selected='selected'>" . $row["auth_Name"] . "</option>";
				}else{
					$authSelect .= "<option value='" . $row["auth_ID"] . "'>" . $row["auth_Name"] . "</option>";
				}
			}
			$authSelect .= "</select>";
		}
?>
		
		<div class="centerwindow" id="newstory">
		<!--
			Things to put here:
				*Story title
				*Story body
				*Author select / Dropdown
				*Issue select / Dropdown
		-->
		<form method="post" action="<?php echo $self; ?>">
			<span class="label">Story title</span>
			<input name="storyname" type="text" placeholder="Story title..." <?php if(isset($_POST["storyname"])){ echo "value='" . $_POST["storyname"] . "'"; } ?>/>
			<span class="label">Story body</span>
			<textarea placeholder="Story body..." id="storybody" name="storybody"><?php if(isset($_POST["storybody"])){ echo "value='" . $_POST["storybody"] . "'"; } ?></textarea>
			
			<?php //Echo select boxes ?>
			<span class="label">Select Associated Issue</span>
			<?php echo $issueSelect; ?>
			<span class="label">Select Author</span>
			<?php echo $authSelect; ?>
			
			<input type="hidden" name="newstory" value="true" />
			<input type="submit" id="submit" name="submit" value="Submit" />
			
			
			
		</form>
	</div>
	
<?php
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>