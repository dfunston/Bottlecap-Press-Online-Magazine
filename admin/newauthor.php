<?php

	/**********************************
	**		Author management		 **
	**********************************/
	
	$pageTitle = "New author";
	$boolAdmin = true;
	
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
	//Handle input
	if(isset($_POST["newauthor"])){
		if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])){
			require_once $_SERVER['DOCUMENT_ROOT'] . "/script/ftp.php";
			$uploadResult = fileUpload("authors");
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
			$authName = $_POST['authname'];
			$authBio = $_POST['authbio'];
			
			//TODO: Implement better error handling
			$stmt = $mysqli->prepare('INSERT INTO bcrp_authors (`auth_Name`, `auth_Bio`, `auth_Pic`) VALUES (?, ?, ?)');
			$stmt->bind_param('sss',$authName, $authBio, $fileLoc);
			$stmt->execute();
			$authID = $stmt->insert_id;
			?>
				
			<div class="centerwindow" id="newissue">
				<span class="label">Author "<?php echo $authName; ?>" was successfully added!</span>
				<span class="label">Author ID is <?php echo $authID; ?></span>
				<span class="label">Would you like to <a href="/admin/newstory.php?authID=<?php echo $authID; ?>">adding stories created by <?php echo $authName; ?>?</a></span>
				<span class="label">Or perhaps <a href="/admin/newauthor.php">add another author?</a></span>
			</div>
			
			<?php
			$stmt->close();
		}else{
			echo "There were errors and I am here.";
		}
	}else{
		//Draw page to submit new issue
	
?>

		<div class="centerwindow" id="newauthor">
		<!--
			Things to put here:
				*Issue image and upload script
				*Date picker for go live date
				*Issue name
		-->
		<form method="post" action="<?php echo $self; ?>"  enctype="multipart/form-data">
			<span class="label">Author name</span>
			<input name="authname" type="text" placeholder="Issue name" />
			<span class="label">Author bio</span>
			<textarea placeholder="Type in an author bio here" id="authbio" name="authbio"></textarea>
			<span class="label">Author image</span>
			<input name="file" id="file" type="file" placeholder="Choose image..." />
			<input type="hidden" name="newauthor" value="true" />
			<input type="submit" id="submit" name="submit" value="Submit" />
		</form>
	</div>
	
<?php

	}
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>