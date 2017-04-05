<?php
  // assign the var that came through the request to a new var
  $sSvFeats = $_REQUEST["svFeats"];
  $sDrawExtent = $_REQUEST["drawExtent"];
  $sNumFeats = $_REQUEST["numFeats"];
  $sFileStatus = "Unable to create file on the server!";
  
  // date/time for file name
  $time = date("Ymdh:i:sa");
  $input = "Time: " . $time . "\nAOI Exent: " . $sDrawExtent . "\n\nGeoJSON Features:\n" . $sSvFeats;
   
  // create file on server
  $ftFile = fopen("01_ClientData/inputFts_" . $time . ".txt", "w") or die ("An error has occurred. Please contact your system administrator.");
  if ($ftFile){
    echo "File created successfully at " . $time;
  }
  // write svFeats to file
  fwrite ($ftFile, $input);
  // close file
  fclose($ftFile);
  
  echo "\nSummary Report...";
  
?>
