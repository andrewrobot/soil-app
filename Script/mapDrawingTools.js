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
                            removeLastFeature();
                    }
                    lastFeature = e.feature;
                        console.log(draw.a);
                });
                
                // Finally, add the draw interaction to the map
                map.addInteraction(draw);
            } else 
                source.clear();                       
        };
        
        addInteraction(); 
        
        // Handle drawing tool change
        $('.drawing-dropdown').change( function() { 
            selectType = $('.drawing-dropdown').dropdown('get value');
            map.removeInteraction(draw);
            addInteraction();
        });