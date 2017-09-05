<?php

	/**********************************
	**		Homepage management		 **
	**********************************/
	
	$pageTitle = "Manage homepage";
	$boolAdmin = true;
	
	
	require $_SERVER['DOCUMENT_ROOT'] . "/script/header.php";
	
	if(isset($_POST["submitted"])){
		$stmt = $mysqli->prepare("UPDATE `bcrp_index` SET `index_html` = ? WHERE `index_key` = 0");
		$stmt->bind_param('s', $_POST["homepage"]);
		$stmt->execute();
		$stmt->close();
		?>
		
		<div class="centerwindow" id="newstory">
			<span class="label">Homepage updated sucessfully!</span>
		</div>
		
		<?php
	}else{
		$query = "SELECT * FROM `bcrp_index` WHERE `index_key` = 0";
		$content = $mysqli->query($query);
		while($row = $content->fetch_array(MYSQLI_ASSOC)){
			$homepage = $row["index_html"];
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
				<span class="label">Homepage content</span>
				<textarea placeholder="Homepage content..." id="homepage" name="homepage">
				<?php
					if(isset($_POST["homepage"])){
						echo $_POST["homepage"];
					}else if(isset($homepage)){ 
						echo $homepage; 
					} 
				?>
				</textarea>
				
				<input type="hidden" name="submitted" value="true" />
				<input type="submit" id="submit" name="submit" value="Submit" />
				
			</form>
		</div>
	
		<?php
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/script/footer.php";
?>