<?php

//This script is LoLzT3HrAnDoM!!! Waffles!

/*****************************************************************
**	The following is for debugging purposes.  Remove when done!	**
*****************************************************************/

echo microtime(true) . "<br />";
$toEcho = lolzRandom(1000);
echo microtime(true) . "<br />";

//echo $toEcho[0] . "<br />";

randTest($toEcho[1]);

/******************************************************************
**							End debugging code					 **
******************************************************************/

function randTest($randArray){
	$y = 0;
	$i = 0;
	while($y < strlen($randArray)){
		$x = substr($randArray, $y, 1);
		if($x != ","){
			$resultArray[$i] .= $x;
		}else{
			//echo $resultArray[$i] . "<br />";
			$i++;
			$y++;
		}
		$y++;
	}
	$max = count($resultArray);
	for($numdigits = 0; $numdigits < 3; $numdigits++){
		$y = 0;
		for($reseti = 0; $reseti < 9; $reseti++){
			$i[$reseti]=0;
		}
		while($y < $max){	
			$n = substr($resultArray[$y], (($x + 1) * -1), 1);
			$subArray[$n][$i[$n]] = $resultArray[$y];
			$i[$n]++;
		}
		$y = 0;
		for($zeroNine = 0; $zeroNine <= 9; $zeroNine++){
			for($subNum = 0; $subNum < count($subArray[$zeroNine]); $subNum++){
				$resultArray[$y] = $subArray[$zeroNine][$subNum];
				$y++;
			}
		}
	}
	for($y = 0; $y < $max; $y++){
		echo $resultArray[$y] . "<br />";
	}
}

function lolzRandom($numDigits){
	if(!$numDigits){$numDigits = 5;} //Default number of digits, produces over 56 billion results.
	for($i=0;$i<=$numDigits;$i++){
		$x = rand(48,110);
		if($x > 57 && $x < 83){
			$x += 8;
		}else if($x > 84){
			$x += 12;
		}
		$result[0] .= chr($x);
		$result[1] .= $x . ", ";
	}
	return $result;
	
	
}


?>