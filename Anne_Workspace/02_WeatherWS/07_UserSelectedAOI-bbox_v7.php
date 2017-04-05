<?php
  $errMsg = "";
  
  if (empty($_REQUEST["xmin"]) || empty($_REQUEST["xmax"]) || empty($_REQUEST["ymin"]) || empty($_REQUEST["ymax"]) || !is_numeric($_REQUEST["xmin"]) || !is_numeric($_REQUEST["xmax"]) || !is_numeric($_REQUEST["ymin"]) || !is_numeric($_REQUEST["ymax"])) {
    $errMsg = "<br>* ERROR: All fields are required and must be WGS84 coordinates";
  } else {
    $sXmin = secure_input($_REQUEST["xmin"]);
    $sXmax = secure_input($_REQUEST["xmax"]);
    $sYmin = secure_input($_REQUEST["ymin"]);
    $sYmax = secure_input($_REQUEST["ymax"]);
    /** create fake extent
      [0] -> xMin
      [1] -> yMin
      ([0], [1]) -> Bottom Left coordinate of extent
      [2] -> xMax
      [3] -> yMax
      ([2], [3]) -> Top Right coordinate of extent
     */
    $sFakeExtent = array($sXmin, $sYmin, $sXmax, $sYmax);
  }
  
  function secure_input($data) {
    // strip unnecessary chars (extra space, tab, newline, etc)
    $data = trim($data);
    // remove backslashes (\)
    $data = stripslashes($data);
    // save as HTML escaped code --> any scripts in input data will not run
    $data = htmlspecialchars($data);
    return $data;
  }
  
  // create array of data return
  echo json_encode(array($errMsg, $sFakeExtent));
?>
