<?php

/*

PHP script for inclusion with the Web Map Application prototype. 
Developed by Andrew Roberts 2017, for Professor X. 

FirePHP console commands for logging php activity:
FB::log('Log Message');
FB::info('Info Message');
FB::warn('Warn Message');
FB::error('Error Message');
FB::trace('Simple Trace');

*/

// Include the FirePHP debugging script
require('../Script/FirePHPCore/fb.php');
$errMsg = "";
 


/***************************  Safety first, verify the data     ***********************/
/***************************  Setup file writing error handling ***********************/



// Secure submited data against malicious script injection
function secure_input($data) {
    // strip unnecessary chars (extra space, tab, newline, etc)
    $data = trim($data);
    // remove backslashes (\)
    $data = stripslashes($data);
    // save as HTML escaped code --> any scripts in input data will not run
    $data = htmlspecialchars($data);
    return $data;
} // close secure_input()


// Write extent coordinates and error message to file (/temp) 
function writeToFile($array, $error) {
    $fileName = "temp/extent" . date("Y-m-dThisa") . ".txt";
    $newFile = fopen($fileName, "w");
    if (!$newFile){        
        return "*Hmmm, something went wrong with your file save..";
    } else {
        $txt = "New Extent: " . json_encode($array) . "\n" . "Error Msg: " . $error;    
        fwrite($newFile, $txt);
        fclose($newFile);
        return "Success! Your coords file was saved";
    }
} // close writeToFile   


// Fetch GeoTIF from GeoServer using cURL, save to file
function curlPostGeoServer($minX, $minY, $maxX, $maxY) {
//    $url = "http://127.0.0.1:8080/geoserver/wcs";
    FB::info($degree_pixel_Xaxis = 0.0020834260100897); 
    FB::info($degree_pixel_Yaxis = 0.002083381677142); 
    FB::info($bbox_Xaxis = $maxX - $minX);
    FB::info($bbox_Yaxis = $maxY - $minY);
    FB::info($dimensionX = round($bbox_Xaxis / $degree_pixel_Xaxis));
    FB::info($dimensionY = round($bbox_Yaxis / $degree_pixel_Yaxis));
    $url = "http://ulysses.agr.gc.ca:8080/geoserver/wcs";
    $post = '<?xml version="1.0" encoding="UTF-8"?>
    <GetCoverage version="1.0.0" service="WCS" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opengis.net/wcs" xmlns:ows="http://www.opengis.net/ows/1.1" xmlns:gml="http://www.opengis.net/gml" xmlns:ogc="http://www.opengis.net/ogc" xsi:schemaLocation="http://www.opengis.net/wcs
        http://schemas.opengis.net/wcs/1.0.0/getCoverage.xsd">
        <sourceCoverage>Canada:Canada_SNDPPT_M_sl1_250m_ll</sourceCoverage>
        <domainSubset>
            <spatialSubset>
                <gml:Envelope srsName="EPSG:4326">
                    <gml:pos>' . $minX . ' ' . $minY . '</gml:pos>
                    <gml:pos>' . $maxX . ' ' . $maxY . '</gml:pos>
                </gml:Envelope>
                <gml:Grid dimension="2">
                    <gml:limits>
                        <gml:GridEnvelope>
                            <gml:low>0 0</gml:low>
                            <gml:high>' . $dimensionX . ' ' . $dimensionY . '</gml:high>
                        </gml:GridEnvelope>
                    </gml:limits>
                    <gml:axisName>x</gml:axisName>
                    <gml:axisName>y</gml:axisName>
                </gml:Grid>
            </spatialSubset>
        </domainSubset>
        <output>
            <crs>EPSG:4326</crs>
            <format>GeoTIFF</format>
        </output>
    </GetCoverage>'; // Setup the headers and post options, then execute curlPOST 
    
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml')); curl_setopt($ch, CURLOPT_HEADER, 0); curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $ch_result = curl_exec($ch); 
    
    // Setup error check. Returned cURL data should be a GeoTIFF if successful. 
    // If there's an error, GeoServer returns an XML. Here, we check for XML since 
    // PHP can't check (easily) for validity (incase of corruption) of .tif. 
    // 
    // Create XML reader object and load in returned cURL data 
    $reader = new XMLReader(); $reader->xml($ch_result); 
    
    // If XMLReader can read(returns TRUE), cURL data was XML and we send an error report 
    if($reader->read()){ 
        // Write error report to error log, including returned XML 
        $errMsg = "\n\n " . date("Y-m-d H:i:s ") . " ERROR " . $_SERVER["REQUEST_TIME "] . "\n\t " . __FILE__ . "\n\t Request XML error: POST request returned without a valid GeoTIFF.\n\t Refer to XML for info:\n\t XML Response:\n\t url: {$url}\n\t xml:\n\t {$ch_result} "; file_put_contents("error/errorlog.txt ", $errMsg, FILE_APPEND); return("*GeoTiff save failed - Contact Server Administrator "); 
    } else { 
        // Apply time-stamp to file and save 
        $fileName = "../PHP/temp/geotiff ". date("Y-m-dThisa ") . ".tif "; file_put_contents($fileName, $ch_result); return($fileName); 
    } 
} // close curlPostGeoServer() 


function runSubProcess(){ 
    $result = exec('python ../Script/helloworld.py "Hello from python! "'); 
    if ($result) { 
        return($result); 
    } else { 
        return("*Sub Process Failed "); 
    } 
} 

/****************** Process (round coords to 4 decimal places) *********************/ 
/****************** Process (write coords to file) *********************/ 
/****************** Process (tiff to file) *********************/ 

// Check that all input is present, numeric, and safe, then trim and write 
// to an array 
// 
if (empty($_REQUEST["minX "]) || empty($_REQUEST["minY "]) || empty($_REQUEST["maxX "]) || empty($_REQUEST["maxY "]) || !is_numeric($_REQUEST["minX "]) || !is_numeric($_REQUEST["minY "]) || !is_numeric($_REQUEST["maxX "]) || !is_numeric($_REQUEST["maxY "])) { 
    // Throws error if inputs aren't entered or numeric. 
    $errMsg = "*Oops, all fields are required and must be WGS84 coordinates. "; 
    
} else {     
    // Validate and secure input data 
    $minX = secure_input($_REQUEST["minX "]); 
    $minY = secure_input($_REQUEST["minY "]); 
    $maxX = secure_input($_REQUEST["maxX "]); 
    $maxY = secure_input($_REQUEST["maxY "]); 
    FB::info("coords: " . $minX . ", " . $minY . ", " . $maxX . ", " . $maxY); 
    
    // Write data to an array, then adjust decimal places 
    $coords = array($minX, $minY, $maxX, $maxY); 
    $length = count($coords); 
    for ($x = 0; $x < $length; $x++) { 
        $coords[$x]=n umber_format((float)$coords[$x], 4, '.', ''); 
    } 
    // Try write coords to file, send msg on success 
    $fileStatus=w riteToFile($coords, $errMsg); 
    // Try curlPostGeoServer 
    $postStatus=c urlPostGeoServer($minX, $minY, $maxX, $maxY); 
    // Try run a sub process 
    $pythonStatus=r unSubProcess(); 
} 

/********************** Return (corrected coordiantes) ************************/ 
// Send the secure, processed data back to be AJAXed in 
echo json_encode(array($errMsg, $coords, $fileStatus, $postStatus, $pythonStatus)); 

?>