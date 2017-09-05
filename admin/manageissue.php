<?php

	/**********************************
	**		Issue management 		 **
	**********************************/
	
	$pageTitle = "New issue";
	$boolAdmin = true;
	
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
	//Handle input
	if(isset($_POST["newissue"])){
		//if(!empty($_FILES)){
		if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])){
			require_once $_SERVER['DOCUMENT_ROOT'] . "/script/ftp.php";
			$uploadResult = fileUpload("issues");
			if($uploadResult[0] != 0){
				//Errors
				// Echo uploadResult[1] or something.  Figure that out.
				echo $uploadResult[1];
				$continue = false;
			}else{
				//Success
				$fileLoc = $uploadResult[2];
				$continue = true;
			}
		}else{
			$fileLoc = NULL;
			$continue = true;
		}
		if($continue == true){
			$issueName = $_POST['issuename'];
			$goLiveDate = $_POST['golivedate'];
			//echo $goLiveDate;
			//echo strtotime($goLiveDate);
			//$dateTimestamp = strtotime($goLiveDate);
			//$sqlQuery = "INSERT INTO bcrp_issues (`issue_Name`, `issue_Post_Date`, `issue_Image`) VALUES (`$sanatizeIssueName`, `$dateTimestamp`, `$sanatizeFileLoc`);";
			//echo $sqlQuery;
			
			//TODO: Implement better error handling
			$stmt = $mysqli->prepare('INSERT INTO bcrp_issues (`issue_Name`, `issue_Post_Date`, `issue_Image`) VALUES (?, ?, ?)');
			$stmt->bind_param('sss',$issueName, $goLiveDate, $fileLoc);
			$stmt->execute();
			$issueID = $stmt->insert_id;
			?>
				
			<div class="centerwindow" id="newissue">
				<span class="label">Bottlecap issue "<?php echo $issueName; ?>" was successfully created!</span>
				<span class="label">Issue ID is <?php echo $issueID; ?></span>
				<span class="label">Would you like to start <a href="/admin/newstory.php?issueID=<?php echo $issueID; ?>">adding stories?</a></span>
			</div>
			
			<?php
			$stmt->close();
		}else{
			echo "There were errors and I am here.";
		}
	}else{
		//Draw page to submit new issue
?>

	<div class="centerwindow" id="newissue">
		<!--
			Things to put here:
				*Issue image and upload script
				*Date picker for go live date
				*Issue name
		-->
		<form method="post" action="<?php echo $self; ?>"  enctype="multipart/form-data">
			<span class="label">Issue name</span>
			<input name="issuename" type="text" placeholder="Issue name" />
			<span class="label">Go live date</span>
			<input name="golivedate" type="date" placeholder="Go live date" />
			<span class="label">Issue image</span>
			<input name="file" id="file" type="file" placeholder="Choose image..." />
			<input type="hidden" name="newissue" value="true" />
			<input type="submit" id="submit" name="submit" value="Submit" />
		</form>
	</div>
	
<?php
	}
	//TODO: jQuery handler to verify that all input is filled in
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>