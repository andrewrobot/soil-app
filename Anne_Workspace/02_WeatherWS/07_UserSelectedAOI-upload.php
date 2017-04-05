<html>
<body>
<?php
  $targetDir = "01_ClientData/uploads/"; 
  // basename(path) outputs the filename without the rest of the path
  // $_FILES[inputFile][name]: $_FILES = array of submitted files in POST; gives the original name of the file on the client machine
  $targetFile = $targetDir . basename($_FILES["shpUpload"]["name"]); 
  $uploadOk = 1;
  $typeOk = 1;
  // pathinfo(path,arrayElements-extensionOnly)
  //$uploadFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
  $uploadFileType = $_FILES["shpUpload"]["type"];
  
  echo "File Name: " . basename($_FILES["shpUpload"]["name"]) . "<br>";
  echo "Target File: " . $targetFile . "<br>";
  echo "Uploaded File Type: " . $uploadFileType . "<br>";
  
  // checks & limits
  // check if file already exists
  if (file_exists($targetFile)) {
    echo "<p>File already exists.</p>";
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
    echo "<p>Only compressed files are allowed. Please try again.</p>";
    $uploadOk = 0;
  }
  
  // check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "<p>The file could not be uploaded.</p>";
  // if there was no error, try to upload file
  } else {
    // move_uploaded_file(file,newloc) moves the specified file to the specified new location --> success returns TRUE, failure returns FALSE; only works with files uploaded via HTTP POST; if destination file already exists it will be overwritten, that is why we test first
    if (move_uploaded_file($_FILES["shpUpload"]["tmp_name"], $targetFile)) {
      echo "<p>The file " . basename($_FILES["shpUpload"]["name"]) . " has been uploaded.</p>";
    } else {
      echo "<p>There was an error uploading the file.</p>";
    }
  }
?>
<button type="button" onclick="location.href = '07_UserSelectedAOI.html';">Ok</button>
</body>
</html>
