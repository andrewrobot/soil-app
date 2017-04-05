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
      </ul>
    </details>
    
    <h2>Please provide your bounding box</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      Upper Left X: <input type="text" name="ulx" value="<?php echo $_POST["ulx"]; ?>">
      Lower Right X: <input type="text" name="lrx" value="<?php echo $_POST["lrx"]; ?>">
      <br><br>
      Upper Left Y: <input type ="text" name="uly" value="<?php echo $_POST["uly"]; ?>">
      Lower Right Y: <input type="text" name="lry" value="<?php echo $_POST["lry"]; ?>">
      <br><br>
      <input type="submit">
    </form>
    
    <div id="php">
      <?php
        echo "<h2>The Area of Your Bounding Box is:</h2>";
        $area = ($_POST["lrx"] - $_POST["ulx"]) * ($_POST["lry"] - $_POST["uly"]);
        echo $area;
      ?>
    </div>
    
  <body>
</html>
