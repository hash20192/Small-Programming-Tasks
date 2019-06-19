/* 
------------------------------------------------------------------------------------------------------------------
// A PHP script will convert a standard input of 1’s and 0’s to a black and white (monochrome) PNG image.  -
// Where 1 represents a black pixel and 0 represents a white pixel. 						 -
// The image will be saved to the filename indicated in the first argument.					 -
*/----------------------------------------------------------------------------------------------------------------

<?php

$line = fopen("php://stdin","r");
$line1 = "";
$check = 0;

// Checks total number of arguements given 
if(count($argv)!=2)
{
	echo "No arguements provided".PHP_EOL;
	exit();
}

// Gets all input given and stores in $line1 variable
while($f = fgets(STDIN))
{
	global $line1;
	$line1 = $line1.($f);
}

// array that contains 1's and 0's created from string $line1
$pixel_array = explode("\n",trim($line1));

// total width of image
$sx = count(str_split($pixel_array[0]));

// total height of image
$sy = count($pixel_array);

// checking size of all rows of the given input
for($i=0;$i<count($pixel_array);$i++)
{
	if(count(str_split($pixel_array[$i]))!=$sx)
	{
		echo "Input rows are not all the same size".PHP_EOL;
		exit();
	}	
}

// creating blank canvas
$my_img = imagecreatetruecolor($sx,$sy);

// for loop setted to total number of rows or height to cover
for($i=0;$i<$sy;$i++)
{
	// A perticular row of 1's n 0's created from string to characters array
	$chunk = str_split($pixel_array[$i]);
	
	// for loop setted to total number of columns or width 
	for($j=0;$j<$sx;$j++)
	{
		// checking character of array, if it is 1 then setting black color
		if($chunk[$j]===1)
		{
		imagesetpixel($my_img,$j,$i,imagecolorallocate($my_img,0,0,0));	
		}
		// checking character of array, if it is 0 then setting white color 
		else if($chunk[$j]===0)
		{
		imagesetpixel($my_img,$j,$i,imagecolorallocate($my_img,255,255,255));	
		}
		// making sure that all characters should be only 1's and 0's.
		else
		{
		echo "Input provided is not binary".PHP_EOL;
		exit();	
		}
	}
}

// saving image using arguement given by user
imagepng($my_img,$argv[1]);
fclose($line);

?>


