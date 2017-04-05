<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GeoTIFF Save v1.2</title>
    <link rel="stylesheet" href="../Script/ol/v3.19.1-dist/ol.css" type="text/css"> 
    <link rel="stylesheet" href="../Styles/template_base.css" type="text/css">
    <link rel="stylesheet" href="../Styles/GeoTIFF_Save.css" type="text/css">
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="../Script/ol/v3.19.1-dist/ol.js"></script>   
</head>
<body> 
    
    <?php
    // Include the FirePHP library to help with debugging php in browser consol
    require('../Script/FirePHPCore/fb.php');
    
    $extentErr = "";
    $minX = $minY = $maxX = $maxY = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["minX"])) {
            $extentErr = "Extent box unkown, please try again.";
        }
        else {
            $minX = $_POST["minX"];
            $minY = $_POST["miny"];
            $maxX = $_POST["maxX"];
            $maxY = $_POST["maxY"];
        }
    }
    
    ?>
    
    <div id="container">        
        <div id="map" class="map">
            <div id="status" class="status">ready</div>
        </div>
        <div id="infoWrap">
            <ul class="tab">
                <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'Selection')">Selection</a></li>
                <li><a href="javascript:void(0)" id="result-link" class="tablinks" onclick="openTab(event, 'Results')">Results</a></li>
            </ul>
            <div id="Selection" class="tabcontent selection">
                <h3 class="header">Draw an Extent</h3>
                <p class="description">Hold down Shift and left-click to drag the marker to define an extent. Hold Shift again to move the marker and resize your current extent, or create a brand new one.</p>
                <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <fieldset class="fieldset">
                        <legend class="legend">Extent Info:</legend>
                        minx:<br>
                        <input type="text" name="minX" id="minX" class="form-data" value=""><br>
                        miny:<br>
                        <input type="text" name="minY" id="minY" class="form-data" value=""><br>
                        maxx:<br>
                        <input type="text" name="maxX" id="maxX" class="form-data" value=""><br>
                        maxy:<br>
                        <input type="text" name="maxY" id="maxY" class="form-data" value=""><br>
                        <input type="submit" id="button-submit" class="button-submit" value="Submit">
                    </fieldset>
                </form>
            </div>
            <div id="Results" class="tabcontent">
                <h3 class="header">Extent Coords</h3>
                <p>The coordinates of your extent are listed below in WGS84 Web Mercator</p>     
                <div class="data">
                    <div class="data-tag">Pixel Value:</div>
                    <div id="pixelValue" class="data-input"><?php echo $maxX; ?></div>    
                </div>
            </div> 
        </div>
        <button class="accordion" onclick="openAcc()">Functionality</button>
        <div class="panel">
          <p>Here, on form submit, I'm echoing the php from this very page. However, it reloads the page and that is <br> creating an undesireable effect. </p>
        </div>
    </div>
    <script src="../Script/templateFunctions.js"></script>
    <script>        
        
        /****************************  Define Map Layers and Map  *****************************/
               
        
        // Basemap layer, OSM source
        //
        var OSM_layer = new ol.layer.Tile({
            source: new ol.source.OSM()
        });  
        
        // Set view and zoom, set for Canada
        //
        var view = new ol.View({
            center: [-10997148, 9919099],
            zoom: 3
        });
        
        // Create map, point to target div
        //
        var map = new ol.Map({
            interactions: ol.interaction.defaults({doubleClickZoom: false}),
            layers: [OSM_layer],
            target: 'map',
            view: view,            
        })
        
              
        /****************************  Create Extent Manipulation  *****************************/
        
        
        var extent = new ol.interaction.Extent({
            condition: ol.events.condition.platformModifierKeyOnly
        });
        
        map.addInteraction(extent);
        extent.setActive(false);

        //Enable interaction by holding shift
        this.addEventListener('keydown', function(event) {
            if (event.keyCode == 16) {
                extent.setActive(true);
            }
        });
        
        var newCoord;
        this.addEventListener('keyup', function(event) {
            if (event.keyCode == 16) {
                extent.setActive(false);
                console.log(extent);
                console.log(OSM_layer.getSource().getProjection().getCode());
                console.log(extent.j[0]);
                var oldCode = OSM_layer.getSource().getProjection().getCode();
                newCoord = ol.proj.transformExtent([extent.j[0], extent.j[1], extent.j[2], extent.j[3]], oldCode, 'EPSG:4326' );
                console.log(newCoord);
                displayExtent();
//                openResultTab();
            }
        });
        
        function displayExtent() {
            document.getElementById("minX").value = newCoord[0];
            document.getElementById("minY").value = newCoord[1];
            document.getElementById("maxX").value = newCoord[2];
            document.getElementById("maxY").value = newCoord[3];
        };
        
        
        /*************************  Switch to Result Tab with submit  **************************/
        
        
//        var submitButton = document.getElementById("button-submit");
//        submitButton.onclick = openResultTab;
        
        document.getElementById("button-submit").onclick = openResultTab;
//        openResultTab();
        
    </script>
    
</body>
</html>

