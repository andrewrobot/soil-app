<?php

//require('../../Script/FirePHPCore/fb.php');
//
//fb('Test');
//FB::log('Log Message');
//FB::info('Info Message');
//FB::warn('Warn Message');
//FB::error('Error Message');
FB::trace('Simple Trace');

$minX = $_REQUEST["minX"];
$minY = $_REQUEST["miny"];
$maxX = $_REQUEST["maxX"];
$maxY = $_REQUEST["maxY"];


return FB::trace('Simple Trace');

//$coords = array($minX, $minY, $maxX, $maxY);
//
//foreach ($coords as $coord) {
//    $newCoord = number_format((float)$coord, 3, '.', '');
//    $coord = $newCoord;
//    FB::info($newCoord);
//}




?>