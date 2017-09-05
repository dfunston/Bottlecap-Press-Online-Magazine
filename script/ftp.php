<?php

/*
	TODO:
		Create function
		Create filename from random hash function
		Ensure random hash is unique
		
*/

require_once $_SERVER['DOCUMENT_ROOT'] . "/script/randhash.php";

//File upload script

function fileUpload($subDir=NULL){
	//Copied from Inebriated Studios video site
	//Modified for use with Bottlecap
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$temp = explode(".", $_FILES['file']["name"]);
	$extension = strtolower(end($temp));
	
	if($subDir != NULL){
		$dir = "/img/" . $subDir . "/";
	}else{
		$dir = "/img/";
	}
	
	if(in_array($extension, $allowedExts))
	{
		if($_FILES['file']["error"]!=0)
		{
			$return[0]=1;
			$return[1]="Error: " . $_FILES['file']["error"] . "<br>";
			$return[2]=NULL;
		}else{
			//Debug
			/*
				echo "Upload: " . $_FILES['file']["name"] . "<br>";
				echo "Type: " . $_FILES['file']["type"] . "<br>";
				echo "Size: " . $_FILES['file']["size"] . "<br>";
				echo "Stored in: " . $_FILES['file']["tmp_name"] . "<br>";
			*/
			//Get random generated hash for filename:
			$flagUnique = false;
			while($flagUnique==false){
				$identifier = lolzRandom(10);
				// Check if file already exists
				$fileLoc = $_SERVER['DOCUMENT_ROOT'] . $dir . $identifier . "." . $extension;
				if (!file_exists($fileLoc)) {
					$flagUnique = true;
				}
			}
			//echo $fileLoc;
			//echo $_FILES['file']["tmp_name"];
			move_uploaded_file($_FILES['file']["tmp_name"], $fileLoc);
			$return[0]=0;
			$return[1] = "OK";
			$return[2] = $fileLoc; 
			//Toggle this when done debugging
			//echo "Stored in: " . $fileLoc;
		}	
	}else{
		$return[0]=-1;
		$return[1]= "Invalid file type! Files should be JPG/JPEG, GIF, or PNG!";
		$return[3]=NULL;
	}
	return $return;
	/*
		
		return[0] will contain basic flag for error catching, IE 0 if all good, 1 for general file error, -1 for incorrect file type
		return[1] will contain more verbose error messages, or OK if file uploaded successfully.
		return[2] will contain the file location if the upload succeeded.
		
	*/
	
	
}

?>