<html>
    <title>Return test</title>
    <body>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        Name: <input type="text" name="name"><br>
        Fav farm creature: <input type="text" name="creature"><br>
        <input type="submit">
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_REQUEST['name'];
    $creature = $_REQUEST['creature'];
    echo "Your name is " . $name . "<br>";
    echo "and your favorite animal is a " . $creature;    
    }
        
    function returnStr() {
        echo "I worked";
        $string = "Some shit";
        return $string;
    }
    
    returnStr();    
    ?>
        
    </body>
</html>

'<?xml version="1.0" encoding="UTF-8"?><GetCoverage version="1.0.0" service="WCS" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opengis.net/wcs" xmlns:ows="http://www.opengis.net/ows/1.1" xmlns:gml="http://www.opengis.net/gml" xmlns:ogc="http://www.opengis.net/ogc" xsi:schemaLocation="http://www.opengis.net/wcs http://schemas.opengis.net/wcs/1.0.0/getCoverage.xsd">
  <sourceCoverage>Canada:Canada</sourceCoverage>
  <domainSubset>
    <spatialSubset>
      <gml:Envelope srsName="EPSG:4326">
        <gml:pos>-141.00000623999998 40.7033408</gml:pos>
        <gml:pos>-51.83335383999999 87.36999999999999</gml:pos>
      </gml:Envelope>
      <gml:Grid dimension="2">
        <gml:limits>
          <gml:GridEnvelope>
            <gml:low>0 0</gml:low>
            <gml:high>42800 22400</gml:high>
          </gml:GridEnvelope>
        </gml:limits>
        <gml:axisName>x</gml:axisName>
        <gml:axisName>y</gml:axisName>
      </gml:Grid>
    </spatialSubset>
  </domainSubset>
  <output>
    <crs>EPSG:4326</crs>
    <format>GeoTIFF</format>
  </output>
</GetCoverage>'