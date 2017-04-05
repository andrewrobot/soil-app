<!DOCTYPE html>
<html>
  <head>
    <title>Draw Features & Save Extent</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v3.19.1/css/ol.css" type="text/css">
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v3.19.1/build/ol.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  </head>
  
  <body>
    <details>
      <summary>Prototype Details</summary>
      <ul>
        <li>User can select an AOI by:</li>
        <ul>
          <li>Creating features and saving them. A file will be written to the server with the saved features in GeoJSON format. The feature layer extent will be saved to be used as the AOI.</li>
          <ul>
           <li>The end goal would be to use the saved extent as a parameter in a call to the WCS on GeoServer.</li>
          </ul>
          <li>Uploading a Shapefile. The file will be saved onto the server for further processing</li>
        </ul>
      </ul>
    </details>
    
    <div id="map" class="map"></div>
    
    <form action="07_UserSelectedAOI-upload_v5.php" method="post" enctype="multipart/form-data">
      <h3>Upload a Zipped Shapefile:</h3>
      <input type="file" name="shpUpload" id="shpUplaod">
      <input type="submit" value="Upload" name="submit">
    </form>
    
    
    <h4>or</h4><h3>Specify a Bounding Box:</h3>
    
    <?php
      // define variables & set to empty values
      $error = $xmin = $xmax = $ymin = $ymax = "";
      
      // if submitted, run all var's through test_input & check to see if empty then add error message
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["xmin"]) || empty($_POST["xmax"]) || empty($_POST["ymin"]) || empty($_POST["ymax"]) || !is_numeric($_POST["xmin"]) || !is_numeric($_POST["xmax"]) || !is_numeric($_POST["ymin"]) || !is_numeric($_POST["ymax"])) {
          $errMsg = "<br>* ERROR: All fields are required and must be WGS84 coordinates";
          $error = true;
        } else {
          $sXmin = test_input($_POST["xmin"]);
          $sXmax = test_input($_POST["xmax"]);
          $sYmin = test_input($_POST["ymin"]);
          $sYmax = test_input($_POST["ymax"]);
        }
      }
      
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
    ?>
    
    <form id="coords" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      Lower Left X / X min: <input type="text" name="xmin" id="xmin" value="<?php echo $_POST["xmin"]; ?>">
      Upper Right X / X max: <input type="text" name="xmax" id="xmax" value="<?php echo $_POST["xmax"]; ?>">
      <br><br>
      Lower Left Y / Y min: <input type ="text" name="ymin" id="ymin" value="<?php echo $_POST["ymin"]; ?>">
      Upper Right Y / Y max: <input type="text" name="ymax" id="ymax" value="<?php echo $_POST["ymax"]; ?>">
      <span id="error" style="color:red;"><?php echo $errMsg;?></span>
      <br><br>
      <button type="button" onclick="clearCoords()">Clear Coordinates</button>
      <input type="submit" value="Submit Coordinates">
    </form>
    
    
    <form class="form-inline">
      <h4>or</h4><h3>Create Features:</h3>
      <label>Geometry Type: &nbsp;</label>
      <select id="type">
        <!-- will want to create a buffer around the point -->
        <option value="Point">Point</option>
        <option value="Polygon">Polygon</option>
        <option value="Circle">Circle</option>
        <option value="Square">Square</option>
        <option value="Box">Box</option>
        <option value="None" selected></option>
      </select>
      <br><br>
      <button type="button" onclick="clearClick()">Clear Features</button>
      <button type="button" onclick="writeFeats()">Submit Features</button>   
    </form>
    
    <!-- div to display status of the file save on the server with date and time -->
    <div id="save"></div>
    <!-- div to display # of features saved -->
    <div id="numFeat"></div>
    <!-- div to display layer extent -->
    <div id="extent"></div>
    <!-- div to display GeoJSON output -->
    <div id="geoJ"></div>
    <!-- div to display the entire returned json array for testing and to display BBox extend indicated by user -->
    <div id="result">
      <?php
        if (isset($_POST["xmin"], $_POST["xmax"], $_POST["ymin"], $_POST["ymax"]) && !$error) {
          $fakeExtent = $_POST["xmin"] . "," . $_POST["ymin"] . "," . $_POST["xmax"] . "," . $_POST["ymax"];
          echo "The area of interest was created from the extent of the bounding box you provided:<br>" . $fakeExtent;
        }
      ?>
    </div>
    
    <script>
      // display map section
      
      // raster basemap
      var raster = new ol.layer.Tile({
        source: new ol.source.OSM()
      });
      
      // vector source -> no world wrap
      var source = new ol.source.Vector({wrapX: false});
      
      // vector layer for newly drawn features
      var vector = new ol.layer.Vector({
        source: source
        // if want custom styles, would indicate here (see 01_OL3Exs/draw-features.html)
      });
      
      // map
      var map = new ol.Map({
        layers: [raster, vector],
        target: 'map',
        view: new ol.View({
          center: [-11000000, 8500000],
          zoom: 4
        })
      });
      
      
      // reset div's
      function clearDivs() {
        document.getElementById("save").innerHTML = "";
        document.getElementById("numFeat").innerHTML = "";
        document.getElementById("extent").innerHTML = "";
        document.getElementById("geoJ").innerHTML = "";
        document.getElementById("result").innerHTML = "";
      }
      
      
      // submit coordinates section
      function clearCoords() {
        document.getElementById("coords").reset();
        document.getElementById("xmin").setAttribute("value", "");
        document.getElementById("xmax").setAttribute("value", "");
        document.getElementById("ymin").setAttribute("value", "");
        document.getElementById("ymax").setAttribute("value", "");
        document.getElementById("error").innerHTML = "";
        clearDivs();
      }      
      
      
      // create features section
      
      // draw & type var's
      var typeSelect = document.getElementById('type');
      var typeValue = typeSelect.value //this is to store the current typeSelect value that will be used if a user selects cancel when changing type (see onchange event below)
      var draw; // global so can remove interaction later
      
      // interactions
      function addInteraction() {
        var value = typeSelect.value;
        
        if (value !== 'None') {
          var geometryFunction, maxPoints;
          if (value === 'Square') {
            value = 'Circle';
            geometryFunction = ol.interaction.Draw.createRegularPolygon(4);
          } else if (value === 'Box') {
            value = 'LineString';
            maxPoints = 2;
            geometryFunction = function(coordinates, geometry) {
              if (!geometry) {
                geometry = new ol.geom.Polygon(null);
              }
              var start = coordinates[0];
              var end = coordinates[1];
              geometry.setCoordinates([
                [start, [start[0], end[1]], end, [end[0], start[1]], start]
              ]);
              return geometry;
            };
          }
          
          draw = new ol.interaction.Draw({
            source: source,
            type: (value),
            geometryFunction: geometryFunction,
            maxPoints: maxPoints
          });
          
          map.addInteraction(draw);
        }
      }
      
      // handle change
      typeSelect.onchange = function() {
        // when original is None or no features created, no warning
        if (typeValue == 'None'||vector.getSource().getFeatures().length == 0) {
          changeType();
        } else {
          if (confirm("Your features will be deleted. Are you sure you want to change geometry type?") == true) {
            changeType();
          } else {
            typeSelect.value = typeValue;
          }
        }
      };
      
      addInteraction();
      
      function changeType(){
        map.removeInteraction(draw);
        addInteraction();
        clearFeats();
        typeValue = typeSelect.value;
      }
      
      // handle warning when click Clear Features
      function clearClick() {
        if (typeValue == 'None'||vector.getSource().getFeatures().length == 0) {
        clearFeats();
      } else {
        if (confirm("Your features will be deleted. Press OK to continue or Cancel") == true) {
          clearFeats();
         }
        }
      }
      
      // reset features
      function clearFeats() {
        // clear save, geoJ & extent div
        clearDivs();
        // remove features from source / vector layer
        source.clear();
      }
      

     function writeFeats() {
       
       // create an array of all drawn features
       // returns all features on the source in a random order as {Array.<ol.Feature>} --> same as the array I created, I think --> yes can do either way
       var allFeatures = vector.getSource().getFeatures();
       
       // reset div's
       clearDivs();
       
       //
       if (allFeatures.length == 0) {
         document.getElementById("save").innerHTML = "<p>No features have been created.</p>";
       } else {
         // create GeoJSON format
         var format = new ol.format.GeoJSON();
         // write all features in GeoJSON format
         var svFeats = format.writeFeatures(allFeatures);
       
         // create array to hold feature coordinates
         var featCoords = [];
         
         // initialize extent with extent of 1st feature in allFeatures
         var drawExtent = allFeatures[0].getGeometry().getExtent();
         // loop through allFeatures, get coords and add to array
         // for-in loop didn't work -> couldn't recognize feature as ol.Feature type so .getGeometry() failed
         allFeatures.forEach(function(feature) {
           // use .getExtent() for each feature, then use ol.extent.extend() to add the extents together.
           var featExtent = feature.getGeometry().getExtent();
           drawExtent = ol.extent.extend(drawExtent, featExtent);
           // use .getCoordinates() for testing if not circle (which doesn't have that method)
           if (typeValue !== 'Circle') {
             featCoords.push(feature.getGeometry().getCoordinates());
           }
         });
         
         // jQuery POST to php when user saves features
         var posting = $.post("07_UserSelectedAOI-create_v5.php",
           {
             svFeats: svFeats,
             // need .toString() -> otherwise just sends array and when written to doc actually reads "Array"
             drawExtent: drawExtent.toString(),
             numFeats: allFeatures.length
           },
           function(data, status){
             alert("Status: " + status);
             // give summary report in div's
             $("#save").append("<p>" + data[0] + "</p>");
             // check if file not created and values are 0 (dir issue - does not exist / permissions)
             if (data[1]) {
               $("#numFeat").append("<p>You have saved " + data[1] + " features.</p>");
             }
             if (data[2]) {
               $("#extent").append("<p>The extent of the area of interest you provided is:<br>" + data[2] + "</p>");
             }
             if (data[3]) {
               $("#geoJ").append("<p>Here is a summary of the features you created in GeoJSON format:<br>" + data[3] + "</p>");
             }
             $("#result").append("<p>Returned Results: " + data + "</p>");
             },
           // return data type
           "json"
         );
         
       }
     }
     
     
    </script>
  </body>
</html>
