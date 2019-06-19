<?php
//-----------------------------------------------------------------------------------------------------------------------------------
// http://php.net/manual/en/fann.examples-1.php#117242 										    -															    -
//															    -
// PHP script to train an artificial neural network on images from two sample sets and then classify a third set of images. -
//-----------------------------------------------------------------------------------------------------------------------------------



function evalImage($filename, $binaryArray, $positiveName, $negativeName, $ann, $ann1) {
    echo PHP_EOL;
    echo "Image Name: $filename" . PHP_EOL;
    $calc_out = fann_run($ann, $binaryArray);
    $calc_out1 = fann_run($ann1, $binaryArray);
    $temp = 0;
    $parameter=$negativeName;
    // taking best of both and decaring resut accodring to like
    // if car trained FNN has big number then it will have outcome recognised as car
    if($calc_out>$calc_out1)
    {
	$temp = $calc_out;
	$parameter = $positiveName;
    }
    else{
    	$temp = $calc_out1;
    }
    echo 'Raw: ' .  ($temp[0]) . PHP_EOL;
    echo 'Rounded: ' . (round($temp[0])) . PHP_EOL;
    echo 'Outcome: ';

    for($i = 0; $i < 1; $i++) {
        if( round($temp[0])) {
            echo $positiveName;
        }
	else{
                echo $negativeName;
        }
    }
    
    echo '' . PHP_EOL;
    
}


// run a sub process sending command and optionally standard input
// returns standard output
function runprocs($command, $input = "") { 

  // build up channels
  $descriptorspec = array( 
    0 => array("pipe", "r"),  // stdin 
    1 => array("pipe", "w"),  // stdout 
    2 => array("pipe", "w")   // stderr ?? instead of a file 
  ); 
  $process = proc_open($command, $descriptorspec, $pipes); 
  if (is_resource($process)) { 
    fwrite($pipes[0], $input); 
    fclose($pipes[0]); 

    $output = "";

    // get stdout
    while($s= fgets($pipes[1], 1024)) { 
          // read from the pipe 
          $output .= $s; 
    } 
    fclose($pipes[1]); 

    // get stderr
    while($s= fgets($pipes[2], 1024)) { 
      $output.= "\nError: $s\n\n"; 
    } 
    fclose($pipes[2]); 
  } 
  return $output; 
} 

// Setting up arguements as a folder paths
$cats = $argv[1];
$cars = $argv[2];
$unsorted = $argv[3];

$testFolder = "bwCats";
$testFolder1 = "bwCars";
$testFolder2 = "bwunsorted";

// Scanning directory for files
$catFiles = scandir($cats);
$carFiles = scandir($cars);
$unsortedFiles = scandir($unsorted);

// creating test folders for all cats, cars and unsorted files of monochrome
$test = runprocs('mkdir '.$testFolder);
$test = runprocs('mkdir '.$testFolder1);
$test = runprocs('mkdir '.$testFolder2);

// setting initial size of images taking each image file from given folders
$size = array(getimagesize($cats."".$catFiles[2]),getimagesize($cars."".$carFiles[2]),getimagesize($unsorted."".$unsortedFiles[2]));

// creating array of three folder systems
$files = array($catFiles,$carFiles,$unsortedFiles);

// storing names of folders in to array
$folderNames = array($cats,$cars,$unsorted);

// storing names of testfolders into array
$testFolders = array($testFolder,$testFolder1,$testFolder2);

// loop for three folders to generate relevent files
for($k=0;$k<3;$k++)
{

	// temp file to store data
	
	$trainFile = fopen("training_input".$k.".dat","w");
	
	// total number of length of image
	$picSize = $size[$k][0]*$size[$k][1];
	
	// setting up the first line of training_input.dat skipping for unsorted folder
	if($k<2)
	{
		fwrite($trainFile,(count($files[$k])-2)." ".$picSize." 1\n");
	}
	// Converting images from color to monochrome using workshop 6
	for($i=2;$i<count($files[$k]);$i++)
	{
		$test = runprocs('node workshop6.js '.$folderNames[$k].'/'.$files[$k][$i].' '.$testFolders[$k].'/'.$files[$k][$i]);
	}

	// scanning testfolders for files
	$bw = scandir($testFolders[$k]);
	
	// Convertng images from monochrome to binary and after binary to edge detected binary form
	for($i=2;$i<count($bw);$i++)
	{
		$test = runprocs('node workshop7.js '.$testFolders[$k].'/'.$bw[$i].' '.'test'.$k.'.dat | cat test'.$k.'.dat | ruby workshop5.rb training_input'.$k.'.dat ');
	}

	fclose($trainFile);
}

// example training code
$test_File = fopen('training_input0.dat','r');
fgets($test_File);
$lengthFull=count(str_split(trim(fgets($test_File))));
fclose($test_File);
echo $lengthFull;

set_time_limit ( 300 ); // do not run longer than 5 minutes (adjust as needed)
$num_input = $lengthFull;
$num_output = 1;
$num_layers = 3;
$num_neurons_hidden = 107;
$desired_error = 0.00001;
$max_epochs = 5000000;
$epochs_between_reports = 10;
$cats_ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
$cars_ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);

// training both data
if ($cats_ann && $cars_ann) 
{
    echo 'Training ANN... '; 
    fann_set_activation_function_hidden($cats_ann, FANN_SIGMOID_SYMMETRIC);
    fann_set_activation_function_output($cats_ann, FANN_SIGMOID_SYMMETRIC);
    fann_set_activation_function_hidden($cars_ann, FANN_SIGMOID_SYMMETRIC);
    fann_set_activation_function_output($cars_ann, FANN_SIGMOID_SYMMETRIC);
        
    $filecat = dirname(__FILE__) . "/training_input0.dat";
    $filecar = dirname(__FILE__) . "/training_input1.dat";
    if (fann_train_on_file($cats_ann, $filecat, $max_epochs, $epochs_between_reports, $desired_error))
        fann_save($cats_ann, dirname(__FILE__) . "/training_data0.net");
    fann_destroy($cats_ann);
    if (fann_train_on_file($cars_ann, $filecar, $max_epochs, $epochs_between_reports, $desired_error))
        fann_save($cars_ann, dirname(__FILE__) . "/training_data1.net");
    fann_destroy($cats_ann);
}


// example usage once trained



$test_File = fopen('training_input2.dat','r');
$trackr = 1;
$train_file = (dirname(__FILE__) . '/training_data0.net');
$train_file1 = (dirname(__FILE__) . '/training_data1.net');

if (!is_file($train_file) && !is_file($train_file1))
    die('The training data file has not been created!\n' . PHP_EOL);
$trained_ann = fann_create_from_file($train_file);
$trained_ann1 = fann_create_from_file($train_file1);

if ($trained_ann && $trained_ann1){
	while($line = fgets($test_File))
	{
		$line = trim($line);
		$line = str_split(trim($line));
    
	        evalImage('unsorted'.$trackr.'.png', $line, "Cat", "Car", $trained_ann, $trained_ann1);
    		$trackr++;
	}
        fann_destroy($trained_ann);
        fann_destroy($trained_ann1);
	
} 
else 
{
    die("Training data is in invalid format....\n" . PHP_EOL);
}

?>
