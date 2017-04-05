<?php
  // assign the var that came through the request to a new var
  $sSvFeats = $_REQUEST["svFeats"];
  $sDrawExtent = $_REQUEST["drawExtent"];
  $sNumFeats = $_REQUEST["numFeats"];
  $sFileStatus = "An error has occurred. Please contact your system administrator.";
  
  // date/time for file name
  $time = date("Ymdh:i:sa");
  
  // make date / time readable  
  $timeAr = str_split($time);
  
  $dateAr = array_slice($timeAr, 0, 8);
  $yyAr = array_slice($dateAr, 0, 4);
  $mmAr = array_slice($dateAr, 4, 2);
  $ddAr = array_slice($dateAr, 6, 2);
  $yy = implode($yyAr);
  $mm = implode($mmAr);
  $dd = implode($ddAr);
  $date = $dd . "/" . $mm . "/" . $yy ." (dd/mm/yyyy)";
  
  $hmsAr = array_slice($timeAr, 8);
  $hms = implode("", $hmsAr); 
  
  // create input for file
  $input = "Time: " . $date . " at " . $hms . "\n\nAOI Exent: " . $sDrawExtent . "\n\nGeoJSON Features:\n" . $sSvFeats;
  
  // create file on server
  $ftFile = fopen("01_ClientData/inputFts_" . $time . ".txt", "w") or die ($sFileStatus);
  if ($ftFile){
    $sFileStatus = "File created successfully on " . $date . " at " . $hms;
  }
  // write svFeats to file
  fwrite ($ftFile, $input);
  // close file
  fclose($ftFile);
  
  // create array of var's to return to webpage
  /**
    [0] -> $sFileStatus
    [1] -> $sNumFeats
    [2] -> $sDrawExtent
    [3] -> $sSvFeats
   */
  echo json_encode(array($sFileStatus, $sNumFeats, $sDrawExtent, $sSvFeats));
?>
