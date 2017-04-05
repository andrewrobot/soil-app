<?php

/*

PHP script for inclusion with the Web Map Application prototype. 
Developed by Andrew Roberts 2017, for Professor X. 

FirePHP console commands for logging php action:
FB::log('Log Message');
FB::info('Info Message');
FB::warn('Warn Message');
FB::error('Error Message');
FB::trace('Simple Trace');

*/

// Include the FirePHP debugging script
require('../Script/FirePHPCore/fb.php');
$errMsg = "";



/***************************  Safety first, verify the data  **************************/
/***************************  Setup file writing functions   **************************/



// Secures submited data against malicious script injection
//
function secure_input($data) {
    // strip unnecessary chars (extra space, tab, newline, etc)
    $data = trim($data);
    // remove backslashes (\)
    $data = stripslashes($data);
    // save as HTML escaped code --> any scripts in input data will not run
    $data = htmlspecialchars($data);
    return $data;
}


// Function to write extent coordinates and error message to file (/temp) 
//
function writeToFile($array, $error) {
    $fileName = "temp/extent" . date("Y-m-dThisa") . ".txt";
    $newFile = fopen($fileName, "w") or die("Unable to open file!");
    $txt = "New Extent: " . json_encode($array) . "\n" . "Error Msg: " . $error;    
    fwrite($newFile, $txt);
    fclose($newFile);
}



/******************  Process (round coords to 4 decimal places)   *********************/
/******************  Process (write coords to file)               *********************/



// Check that all input is present, numeric, and safe, then trim and write
// to an array
//
if (empty($_REQUEST["minX"]) || empty($_REQUEST["minY"]) || empty($_REQUEST["maxX"]) || empty($_REQUEST["maxY"]) || !is_numeric($_REQUEST["minX"]) || !is_numeric($_REQUEST["minY"]) || !is_numeric($_REQUEST["maxX"]) || !is_numeric($_REQUEST["maxY"])) {
    
    // Throws error if inputs aren't entered or numeric. 
    $errMsg = "*Oops, all fields are required and must be WGS84 coordinates.";
    
} else{
    
    // Validate and secure input data
    $minX = secure_input($_REQUEST["minX"]);
    $minY = secure_input($_REQUEST["minY"]);
    $maxX = secure_input($_REQUEST["maxX"]);
    $maxY = secure_input($_REQUEST["maxY"]);
    
    // Write data to an array, then adjust decimal places
    $coords = array($minX, $minY, $maxX, $maxY);
    $length = count($coords);
    for ($x = 0; $x < $length; $x++) {
        $coords[$x] = number_format((float)$coords[$x], 4, '.', '');
    }
    writeToFile($coords, $errMsg);
}



/**********************  Return (corrected coordiantes)  ************************/



// Send the secure, processed data back to be AJAXed in
echo json_encode(array($errMsg, $coords));

?>














