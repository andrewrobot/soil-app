 /****************************  Add user defined bbox draw *****************************/
        
        
        // Validate user inputs, display any warnings, then on success
        // add a polygon from the Bounding Box user input
        //        
        function addUsersCoords() {
            source.clear();
            var minX, minY, maxX, maxY, epsg, error, coords;
            
            error= 0;
            coords = [ 
                [minX, '#minX'], 
                [minY, '#minY'], 
                [maxX, '#maxX'], 
                [maxY, '#maxY'], 
                [epsg, '#epsg'] 
            ];
            
            $.each(coords, function(){
                this[0] = parseFloat($(this[1]).val());
            })            
            
            for (i = 0; i < coords.length; i++){            
                if (!$.isNumeric(coords[i][0])){
                    $(coords[i][1]).closest('.field').addClass('error');
                    error += 1;
                } else 
                    $(coords[i][1]).closest('.field').removeClass('error');                
            }

            if (error == 0) {                
                var polygon = new ol.Feature({
                    geometry: new ol.geom.Polygon([
                        [
                            //  Example coords
    //                        [-61.933352224,45.886673304000006],
    //                        [-61.933352224,47.053339784],
    //                        [-64.450018488,47.053339784],
    //                        [-64.450018488,45.886673304000006],
    //                        [-61.933352224,45.886673304000006]
                            [coords[2][0], coords[1][0]],
                            [coords[2][0], coords[3][0]],
                            [coords[0][0], coords[3][0]],
                            [coords[0][0], coords[1][0]],
                            [coords[2][0], coords[1][0]]

                        ]
                    ])
                });
                
                // Doesn't work, gotta catch the error!
                try {
                    polygon.getGeometry().transform('EPSG:' + coords[4][0], 'EPSG:3857');
                    source.clear();
                    
                    // added this
                    shapefileSource.clear();
                    
                    vectorDraw.getSource().addFeature(polygon);
                    var extent = vectorDraw.getSource().getExtent();
                    map.getView().fit(extent, map.getSize());
                    lastFeature = polygon;
                    $('.bbox-form').removeClass('warning');
                }
                catch(err) {
                    $('.bbox-form').addClass('warning');
                }               
            } 
        };

        // Add users feature with onlick
        $('.preview-bbox').click( function(){
            $('.drawing-dropdown').dropdown('set selected', "None");
            addUsersCoords();
        });