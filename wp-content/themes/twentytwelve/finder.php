<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
//include("../../../wp-load.php");


    global $wpdb;
// Get parameters from URL
$center_lat = $_GET["lat"]; //"51.394865"; //
$center_lng = $_GET["lng"];//"-0.193616"; //
$radius = $_GET["radius"];
    $dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

    $results = $wpdb->get_results( "SELECT * , ( 3959 * acos( cos( radians($center_lat) ) * cos( radians( latitude ) ) * cos( radians( longitude) - radians($center_lng) ) + sin( radians($center_lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM wp_agent HAVING distance < $radius ORDER BY distance LIMIT 0 , 20");
    //echo "x";
    //vdump($sql);
    header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
    $i=1;
    foreach ($results as $row) {
   
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name", $row->AgentName);
  $newnode->setAttribute("markerpoint", $i);
  $newnode->setAttribute("address", $row->Address1);
  $newnode->setAttribute("lat", $row->Latitude);
  $newnode->setAttribute("lng", $row->Longitude);
  $newnode->setAttribute("distance", $row->distance);

  $i++;
}

echo $dom->saveXML();


