
    function googleMaps(){
        // Check Maps is available
        if( typeof(google) == "undefined" || typeof(google.maps.MapTypeId) == "undefined" )
            return false;
    
        var infoWindow = new google.maps.InfoWindow();
        var  mapOptions = {
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var bound = new google.maps.LatLngBounds();
        
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
       
        var centre = new google.maps.LatLng( 51.394865,-0.193616 );
        
        function addMarkers() {
            var directory = '/wp-content/themes/ve/';
            // sub function to create a marker
            var createMarker = function( latLng, markerChar, id, text, title, i, infotext )
            {
                //console.log(markerChar);
                
                // Make a marker image object
                var image = new google.maps.MarkerImage(
                directory+'inc/lib/img/design/pin_'+markerChar+'.png',
                new google.maps.Size( 30,28 ), // Size
                new google.maps.Point( 0, 0 ) // Origin
            ),
                shadow = new google.maps.MarkerImage(
                directory+'inc/lib/img/design/pinshade.png',
                new google.maps.Size( 30,28 ), // Size
                new google.maps.Point( 0, 0 ) // Origin
            ),
                marker = new google.maps.Marker({
                    position: latLng,
                    zoom:11,
                    map: map,
                    title: title,
                    id: id,
                    icon: image,
                    shadow: shadow,
                    animation: google.maps.Animation.DROP
                });
                bound.extend(marker.getPosition());
                    
                // Bind an event listner to the marker for when its clicked
                var clickFunction = function() { 
                    // Set content of the existing infowindow object
                    infoWindow.setContent( infotext );
                    
                    //map.setCenter(latLng);
                    
                    //open it
                    infoWindow.open( map, marker );
                    
                    map.setZoom(11);
                    
                };
                
                google.maps.event.addListener( marker, 'click', clickFunction );
                
                return clickFunction;
            
            }
            $resultsList = $('#results').find('li');
            console.log($resultsList);
            $resultsList.each( function( i, el ){
                console.log(this);
                var $that = $(this),
                lat = $that.data('lat'),
                lng = $that.data('lng'),
                markerChar = $that.data('mark');
                //$that.find('span.marker').text();
                infotext = '<h4>' + $that.find( '.fn' ).html() + '</h4>' + '<p>' + $that.find( '.address1' ).html() + '<br/>' + $that.find( '.address2' ).html() + '</p>' + '<p>' + $that.find( '.city' ).html() + '</p>';
                    
                if( lat && lng ){
                    var latLng = new google.maps.LatLng( lat, lng ),
                    title = $that.find('a').html(),
                    id = this.id,
                    text = $that.html();

                    // Make a marker and bind it to the map    
                    var clickFunction = createMarker( latLng, markerChar, id, text, title, i, infotext);
                    $that.find('a.fn').click( function( event ){
                       
                        clickFunction();
                        event.preventDefault();
                    } );
                    
                }
            } );
           
            // Loop thru locations, and create a marker for them
             
            map.fitBounds(bound);
            var zoomset=map.getZoom();
            if(zoomset>10) {
                map.setZoom(10);
            }
        }
        
        
        

        // Set the center 
        map.setCenter( centre );
	
        // Add listner to add makers when its ready
        google.maps.event.addListenerOnce( map, 'tilesloaded', function(){addMarkers();} );
    

    }
