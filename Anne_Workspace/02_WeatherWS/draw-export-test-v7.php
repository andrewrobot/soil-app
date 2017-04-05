<!DOCTYPE html>
<html>
  <head>
    <title>Draw Features & Save</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v3.19.1/css/ol.css" type="text/css">
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v3.19.1/build/ol.js"></script>
  </head>
  
  <body>
    <details>
      <summary>Prototype Details</summary>
      <ul>
        <li>Same user functionality as v6</li>
        <li>Changed to .php so test php script runs</li>
      </ul>
    </details>
    
    <div id="map" class="map"></div>
    
    <form class="form-inline">
      <label>Geometry Type &nbsp;</label>
      <select id="type">
        <!-- will want to create a buffer around the point -->
        <option value="Point">Point</option>
        <option value="Polygon">Polygon</option>
        <option value="Circle">Circle</option>
        <option value="Square">Square</option>
        <option value="Box">Box</option>
        <option value="None" selected></option>
      </select>
    </form>
    
    <div id="buttons">
      <button onclick="clearFeats()">Clear Features</button>
      <button onclick="writeFeats()">Save Features</button>
    </div>
    
    <div id="phpTest">
      <?php
        echo "PHP script test = WORKING!";
      ?>
    </div>
    
    <!-- create div to display gml -->
    <div id="gml"></div>
    
    <script>
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
      
      // draw & type var's
      var typeSelect = document.getElementById('type');
      var typeValue = typeSelect.value //this is to store the current typeSelect value that will be used if a user selects cancel when changing type (see onchange event below)
      var draw; // global so can remove interaction later
      var drawnFeats = []; // global so can reset onchange
      
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
        // when original is None, no warning
        if (typeValue == 'None'||vector.getSource().getFeatures().length == 0) {
          changeType();
        } else {
          if (confirm("Your features will be deleted.Are you sure you want to change geometry type?") == true) {
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
      
      // reset features
      function clearFeats() {
        // clear gml div
        document.getElementById("gml").innerHTML = "";
        // remove the drawn features from the vector source
        source.clear();
      }
      
      // create a button that will then display the array of features in GeoJSON format into the div
     function writeFeats() {
       // returns all features on the source in a random order as {Array.<ol.Feature>} --> same as the array I created, I think --> yes can do either way
       var allFeatures = vector.getSource().getFeatures();
       var format = new ol.format.GeoJSON();
       var svFeats = format.writeFeatures(allFeatures);
       console.log(svFeats);
       document.getElementById("gml").innerHTML = "";
       if (allFeatures.length == 0) {
         document.getElementById("gml").innerHTML = "<p>No features have been created.</p>";
       } else {
         document.getElementById("gml").innerHTML = "<p>You have saved " + allFeatures.length.toString() + " features. See the GeoJSON format below:</p><p>" + svFeats + "</p>";
       }
     }
     
     // php script to create file with GML
     <?php
       
     ?>
     
    </script>
  </body>
</html>
