<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
?>
<script>
 
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
            var directory = '/wp-content/themes/twentytwelve/';
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
        };
        
        
        

        // Set the center 
        map.setCenter( centre );
	
        // Add listner to add makers when its ready
        google.maps.event.addListenerOnce( map, 'tilesloaded', function(){addMarkers();} );
    

    }
</script>
<body onload="googleMaps()">
    <div id="primary" class="site-content">
        <div id="content" role="main">

            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('content', 'page');
                ?>


                <div>
                    <form id="postfinder" >
                        <input type="text" name ="addressInput" id ="addressInput">
                        <input type="submit" name="submit" value="Search" >
                    </form>
                </div>

                <div id="map" style="width: 500px; height: 300px"></div>
                <?php
                $current_page = get_query_var('paged') ? get_query_var('paged') : 1; //current page


                $p = new pagination();
                $x = get_total_rows();

                $arr = $p->calculate_pages($x, 2, $current_page);
                //vdump($arr);


                $result = find_agent($arr);
                ?>
                <?php $xml = simplexml_load_string($result);
                ?>
                <ul id="results">
                    <?php foreach ($xml->marker as $key => $value) { ?>

                        <li data-lat="<?php echo $value['lat'] ?>" data-lng="<?php echo $value['lng'] ?>" data-mark ="<?php echo $value['markerpoint']; ?>" >
                            <span class="marker"><?php echo $value['markerpoint']; ?></span>
                            <a href ="#na" class="fn"><?php echo $value['name']; ?></a>
                            <span class="address1"><?php echo $value['address']; ?></span>
                            <span class="address2"><?php echo $value['address2']; ?></span>
                            <span class="city"><?php echo $value['city']; ?></span>
                        </li>
                    <?php } ?>
                </ul>
                <div>

                    <?php
                    $postcode = $_GET['addressInput'];
                    foreach ($arr[pages] as $pages) {
                        // echo "<a href='/postcode-finder/?addressInput=$postcode&page=$pages'>$pages<a>"; 

                        printf('<a href="/postcode-finder/page/%s/?addressInput=%s">%s<a>', $pages, $postcode, $pages);
                    }
                    ?>
                </div>
                <?php comments_template('', true); ?>
            <?php endwhile; // end of the loop.  ?>

        </div><!-- #content -->
    </div><!-- #primary -->
</body>

<?php get_sidebar(); ?>
<?php get_footer(); ?>