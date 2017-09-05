<?php

//This script is LoLzT3HrAnDoM!!! Waffles!


function lolzRandom($numDigits){
	if(!$numDigits){$numDigits = 6;} //Default number of digits, produces over 56 billion results.
	$result = "";
	for($i=0;$i<$numDigits;$i++){
		switch (rand(0, 2)){
			case 0:
				$x = rand(0, 9);
				break;
			case 1:
				$x = chr(rand(65, 90));
				break;
			case 2:
				$x = chr(rand(97, 122));
				break;
		}
		$result .= $x;
	}
	return $result;
	
	
}


?>