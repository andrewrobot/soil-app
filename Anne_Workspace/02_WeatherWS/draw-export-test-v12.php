<?php
  // assign the var that came through the request to a new var
  $ServerSF = $_REQUEST["svFeats"];
  // date/time for file name
  $time = date("Ymdh:i:sa");
  $input = "Features:\n" . $ServerSF . "\nTime: " . $time;
  // create file on server
  $ftFile = fopen("01_ClientData/inputFts_" . $time . ".txt", "w") or die ("Unable to open file!");
  if ($ftFile){
    echo "File Created Successfully";
  }
  // write svFeats to file
  fwrite ($ftFile, $input);
  // close file
  fclose($ftFile);
?>
