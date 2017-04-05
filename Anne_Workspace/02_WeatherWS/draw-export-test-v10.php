<?php
  // assign the var that came through the request to a new var
  $ServerSF = $_REQUEST["svFeats"];
  // create file on server
  $ftFile = fopen("/home/hagermanan/data/02_Workspace/02_SSS/newFtFile.txt", "w") or die ("Unable to open file!");
  // write svFeats to file
  fwrite ($ftFile, $ServerSF);
  // close file
  fclose($ftFile);
?>
