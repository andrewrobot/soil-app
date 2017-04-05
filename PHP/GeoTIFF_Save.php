<?php

/*

PHP script for inclusion with the Web Map Application prototype. 
Developed by Andrew Roberts 2017, for Professor X. 

FirePHP console commands for logging php action:
FB::log('Log Message');
FB::info('Info Message');
FB::warn('Warn Message');
FB::error('Error Message');
FB::trace('Simple Trace');

*/

// Include the FirePHP debugging script
require('../Script/FirePHPCore/fb.php');
$errMsg = "";

$ch = curl_init("http://www.example.com/");

$fp = fopen("temp/example.txt", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
//fb($ch);
curl_exec($ch);
curl_close($ch);
fclose($fp);

/*
<?xml version="1.0" encoding="UTF-8"?>
<ogc:GetMap xmlns:ogc="http://www.opengis.net/ows"
            xmlns:gml="http://www.opengis.net/gml"
   version="1.1.1" service="WMS">
   <StyledLayerDescriptor version="1.0.0">
      <NamedLayer>
        <Name>Canada:Canada</Name>
        <NamedStyle><Name>CECSOL</Name></NamedStyle> 
      </NamedLayer> 
   </StyledLayerDescriptor>
   <BoundingBox srsName="http://www.opengis.net/gml/srs/epsg.xml#4326">
      <gml:coord><gml:X>-180</gml:X><gml:Y>90</gml:Y></gml:coord>
      <gml:coord><gml:X>-35</gml:X><gml:Y>40</gml:Y></gml:coord>
   </BoundingBox>
   <Output>
      <Format>image/png</Format>
      <Size><Width>550</Width><Height>250</Height></Size>
   </Output>
</ogc:GetMap>
*/



/*$geoTiffXML = '<?xml version="1.0" encoding="UTF-8"?><GetCoverage version="1.0.0" service="WCS"      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opengis.net/wcs" xmlns:ows="http://www.opengis.net/ows/1.1" xmlns:gml="http://www.opengis.net/gml" xmlns:ogc="http://www.opengis.net/ogc" xsi:schemaLocation="http://www.opengis.net/wcs http://schemas.opengis.net/wcs/1.0.0/getCoverage.xsd"><sourceCoverage>Canada:Canada</sourceCoverage><domainSubset><spatialSubset><gml:Envelope srsName="EPSG:4326"><gml:pos>-141.00000623999998 40.7033408</gml:pos><gml:pos>-51.83335383999999 87.36999999999999</gml:pos></gml:Envelope><gml:Grid dimension="2"><gml:limits><gml:GridEnvelope><gml:low>0 0</gml:low><gml:high>42800 22400</gml:high></gml:GridEnvelope></gml:limits><gml:axisName>x</gml:axisName><gml:axisName>y</gml:axisName></gml:Grid></spatialSubset></domainSubset><output><crs>EPSG:4326</crs><format>GeoTIFF</format></output></GetCoverage>';*/

?>



