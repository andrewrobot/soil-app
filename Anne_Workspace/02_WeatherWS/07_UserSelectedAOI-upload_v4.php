<html>
<body>
<?php
  $targetDir = "01_ClientData/uploads/"; 
  // basename(path) outputs the filename without the rest of the path
  // $_FILES[inputFile][name]: $_FILES = array of submitted files in POST; gives the original name of the file on the client machine
  $targetFile = $targetDir . basename($_FILES["shpUpload"]["name"]); 
  $uploadOk = 1;
  $typeOk = 1;
  $uploadFileType = $_FILES["shpUpload"]["type"];
  
  // checks & limits
  // check if blank
  if ($_FILES["shpUpload"]["size"] == 0) {
    $uploadOk = 0;
    echo "<p>No file selected or file is empty.<br><br>Please press OK and select a file.</p>";
  } else {
  // check if file already exists
    if (file_exists($targetFile)) {
      echo "<p>File already exists.<br><br>Press OK and please rename the file or choose a different file.</p>";
      $uploadOk= 0;
    }
    // only allow zipped formats
    $acceptedTypes = array('application/zip', 'application/x-zip-compressed', 'multiparth/x-zip', 'application/x-compress', 'application/x-tar', 'application/gzip', 'application/x-7z-compressed', 'application/x-gtar');
    foreach($acceptedTypes as $mimeType) {
      if ($mimeType == $uploadFileType) {
        $typeOk = 1;
        break;
      } else {
        $typeOk = 0;
      }
    }
    if ($typeOk == 0) {
      echo "<p>Only compressed files are allowed in the following formats:
        <ul>
          <li>.zip</li>
          <li>.7z</li>
          <li>.tar</li>
          <li>.tar.gz</li>
          <li>.tar.Z</li>
        </ul>
        <br>Press OK and please try again.</p>";
      $uploadOk = 0;
    }
  }
  
  // only save file from memory if no errors
  if ($uploadOk != 0) {
    // move_uploaded_file(file,newloc) moves the specified file to the specified new location --> success returns TRUE, failure returns FALSE; only works with files uploaded via HTTP POST; if destination file already exists it will be overwritten, that is why we test first
    if (move_uploaded_file($_FILES["shpUpload"]["tmp_name"], $targetFile)) {
      echo "<p>The file " . basename($_FILES["shpUpload"]["name"]) . " has been uploaded. The extent will be extracted and associated products will be provided when the prototype is further developed.<br><br>Press OK.</p>";
    } else {
      echo "<p>There was an error uploading the file. Please contact your system administrator.<br><br>Press OK.</p>";
    }
  }
?>
<button type="button" onclick="location.href = '07_UserSelectedAOI_v4.html';">Ok</button>
</body>
</html>
