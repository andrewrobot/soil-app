/****************************  Add Map Drawing Tools      *****************************/

       

// Setup some globals and fetch the selected draw tool
var draw, lastFeature;
var selectType = $('.drawing-dropdown').dropdown('get value');



// Create main interaction function for adding and removing
// drawings on the map page
//
function addInteraction() {
    var value = selectType;
    console.log
    // Setup some geometry conditionals
    if(value !== 'None') {
        var geometryFunction, maxPoints;
        if(value === 'Square') {
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

        // Create the draw interaction with defined geometry function
        draw = new ol.interaction.Draw({
            source: source,
            type: (value),
            geometryFunction: geometryFunction,
            maxPoints: maxPoints
        });

        // Handle drawing feature removal
        var removeLastFeature = function() {
            if (lastFeature) 
                source.removeFeature(lastFeature);
        };

        // Remove feature when begining all drawings except Points 
        draw.on('drawstart', function(e) {
            shapefileSource.clear();
            if (selectType !== 'Point') {
                source.clear();
            }
        });

        // Remove feature when ending drawing IF it's a Point and IF
        // there's a feature to remove
        draw.on('drawend', function (e) {
            if (selectType == 'Point') {
                if (source.getFeatures().length > 0)
//                    getDrawingExtent();
                    removeLastFeature();                    
            }
            lastFeature = e.feature;
            getDrawingExtent();
        });

        // Finally, add the draw interaction to the map
        map.addInteraction(draw);      

            

        
//        $('#feature-extent').
        
    } else 
        source.clear();  
        $('.coord-box').html(" - - &emsp; - -")
};

addInteraction(); 

// Handle drawing tool change
$('.drawing-dropdown').change( function() { 
    selectType = $('.drawing-dropdown').dropdown('get value');
    map.removeInteraction(draw);
    addInteraction();
});


function getDrawingExtent() {  
    if (lastFeature !== undefined) { 
        console.log(lastFeature);
        var html, 
            newCoord, 
            extent = lastFeature.getGeometry().getExtent();
        
        newCoord = ol.proj.transformExtent([extent[0], extent[1], extent[2], extent[3]], 'EPSG:3857', 'EPSG:4326');
        html = '<div class="ui label">' +
                '<i class="checkmark icon"></i> Extent in WGS84: ' +
                '<div class="coord-box detail">' + 
                    newCoord[0].toFixed(2) + ', ' + 
                    newCoord[1].toFixed(2) + '&emsp;' +
                    newCoord[2].toFixed(2) + ', ' + 
                    newCoord[3].toFixed(2) +
               '</div></div>';
        
        $('#feature-extent').html(html).slideDown(500);        
    } 
//    if (map.getLayers().a[1].getSource().getFeatures()[0]) {
//        console.log("why..");
//        var html, 
//            newCoord, 
//            extent = map.getLayers().a[1]
//                        .getSource()
//                        .getFeatures()[0]
//                        .getGeometry()
//                        .getExtent();
//        
//        newCoord = ol.proj.transformExtent([extent[0], extent[1], extent[2], extent[3]], 'EPSG:3857', 'EPSG:4326');
//        html = '<div class="ui label">' +
//                '<i class="checkmark icon"></i> Extent in WGS84: ' +
//                '<div class="detail">' + 
//                    newCoord[0].toFixed(2) + ', ' + 
//                    newCoord[1].toFixed(2) + ', ' +
//                    newCoord[2].toFixed(2) + ', ' + 
//                    newCoord[3].toFixed(2) + ', ' +
//               '</div></div>';
//        
//        $('#feature-extent').html(html).slideDown(500);        
//    }    
};
























