<!DOCTYPE html>
<html>
  <head>
    <title>Input BBox Gives Area</title>
  </head>
  
  <body>
    
    <details>
      <summary>Functionality</summary>
      <ul>
        <li>User provides bounding box</li>
        <li>On Submit, the inputs go to the server and the server does a calculation that is then displayed on the original page. The input values also remain on the page.</li>
        <li>Validation:</li>
        <ul>
          <li>strip unnecessary chars - trim()</li>
          <li>remove backslashes - stripslashes()</li>
          <li>stop malicious code - htmlslpecialchars()</li>
          <li>all fields required</li>
        </ul>
      </ul>
    </details>
    
    <?php
      // define variables & set to empty values
      $error = $ulx = $lrx = $uly = $lry = "";
      
      // if submitted, run all var's through test_input & check to see if empty then add error message
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["ulx"]) || empty($_POST["lrx"]) || empty($_POST["uly"]) || empty($_POST["lry"]) || !is_numeric($_POST["ulx"]) || !is_numeric($_POST["lrx"]) || !is_numeric($_POST["uly"]) || !is_numeric($_POST["lry"])) {
          $errMsg = "ERROR: All fields are required and must be numbers!";
          $error = true;
        } else {
          $ulx = test_input($_POST["ulx"]);
          $lrx = test_input($_POST["lrx"]);
          $uly = test_input($_POST["uly"]);
          $lry = test_input($_POST["lry"]);
        }
      }
      
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
    ?>
    
    <h2>Please provide your bounding box</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      Upper Left X: <input type="text" name="ulx" value="<?php echo $_POST["ulx"]; ?>">
      Lower Right X: <input type="text" name="lrx" value="<?php echo $_POST["lrx"]; ?>">
      <br><br>
      Upper Left Y: <input type ="text" name="uly" value="<?php echo $_POST["uly"]; ?>">
      Lower Right Y: <input type="text" name="lry" value="<?php echo $_POST["lry"]; ?>">
      <br><br>
      <input type="submit">
      <br><br><?php echo $errMsg;?>
    </form>
    
    <div id="php">
      <?php
        echo "<h2>The Area of Your Bounding Box is:</h2>";
        if (isset($_POST["lrx"], $_POST["ulx"], $_POST["lry"], $_POST["uly"]) && !$error) {
          $area = ($_POST["lrx"] - $_POST["ulx"]) * ($_POST["lry"] - $_POST["uly"]);
          echo $area;
        }
      ?>
    </div>
    
  <body>
</html>
