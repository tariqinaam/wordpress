<?php

class PagePostcodeFinder_Controller extends Rhino_Controller {

    public function rhino() {

        $this->post_title = 'PostCode Finder';
        $this->slug = isset($this->params[1]) && strtolower($this->params[1]) != 'page' ? $this->params[1] : false;

        $this->postcode = isset($_GET['addressInput']) ? $_GET['addressInput'] : FALSE;
        //$this->post  = $this->getPost($this->slug);

        /* get the postcode if it is in get request
          $postcode = $_GET['addressInput'];

          //get the current page number
          $current_page = get_query_var('paged') ? get_query_var('paged') : 1; //current page

          //if postcode exist in get variable, get the total number of rows returned
          if ($_GET[addressInput]) {
          $total_rows = get_total_rows();
          }
         */
        if ($this->post) {

            //$this->setLayout('/blog/detail');

            $this->post_title = $this->post->post_title;
        } elseif (!isset($this->params[1]) || (isset($this->params[1]) && $this->params[1] == 'page')) {

            //$this->setLayout('/blog/listing');
            $this->setListing();
        } else {

            //$this->setLayout('/blog/404');
        }
    }

    public function get_total_rows($postcode) {
        $result = $this->getLocation($postcode);
        $center_lat = $result[lat]; //"51.394865"; //
        $center_lng = $result[lng]; //"-0.193616"; //
        //global $wpdb;
        if ($center_lat && $center_lng) {
            $query = "SELECT id,( 3959 * acos( cos( radians($center_lat) ) 
            * cos( radians( latitude ) ) * cos( radians( longitude) - radians($center_lng) ) + sin( radians($center_lat) ) 
            * sin( radians( latitude ) ) ) ) AS distance FROM wp_agent HAVING distance < 10 ORDER BY distance";

            //$total_rows = $wpdb->get_row($query);
            $rows = mysql_num_rows(mysql_query($query));
            //die;
            return $rows;
        } else {
            return false;
        }
    }

    public function find_agent($array1, $postcode) {

        $limit = $array1[limit];
        $result = $this->getLocation($postcode);
        // echo $result[lat];exit;
        //vdump($result);exit;
        global $wpdb;
// Get parameters from URL
        $center_lat = $result[lat]; //"51.394865"; //
        $center_lng = $result["lng"]; //"-0.193616"; //
        //$radius = $_GET["radius"];
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // $query = mysql_query("SELECT * ,( 3959 * acos( cos( radians($center_lat) ) * cos( radians( latitude ) ) * cos( radians( longitude) - radians($center_lng) ) + sin( radians($center_lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM wp_agent HAVING distance < 25 ORDER BY distance");
        // $total_rows = mysql_num_rows($query);
        //$pages = new Paginator;
        //echo $page;
        //$limit = 'limit ' . $page  . ',' . $display_per_page;
        $query = "SELECT * ,( 3959 * acos( cos( radians($center_lat) ) * cos( radians( latitude ) ) 
            * cos( radians( longitude) - radians($center_lng) ) + sin( radians($center_lat) ) 
                * sin( radians( latitude ) ) ) ) AS distance FROM wp_agent HAVING distance < 10 ORDER BY distance $limit";
        $results = $wpdb->get_results($query);

        //header("Content-type: text/xml");
        //Iterate through the rows, adding XML nodes for each
        $i = 1;
        foreach ($results as $row) {

            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $row->agentName);
            $newnode->setAttribute("markerpoint", $i);
            $newnode->setAttribute("address", $row->address1);
            $newnode->setAttribute("address2", $row->address2);
            $newnode->setAttribute("lat", $row->latitude);
            $newnode->setAttribute("lng", $row->longitude);
            $newnode->setAttribute("distance", $row->distance);
            $i++;
        }


//$dom1 = $dom->saveXML();
//vdump($dom->saveXML());
        return $dom->saveXML();
        // return $results;
    }

    public function insert_search_data($postcode, $total_rows) {
        global $wpdb;
        $result = $this->getLocation($postcode);
        $lat = $result['lat'];
        $lng = $result['lng'];
        $current_dateTime = current_time(mysql, true);



        $wpdb->insert(
                'wp_geo_data', array(
            'postCode' => $postcode,
            'searchDate' => $current_dateTime,
            'latitude' => $lng,
            'longitude' => $lat,
            'rowsReturned' => $total_rows,
            'isActive' => 1,
                )
        );
    }

    public function getLocation($postcode) {
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";
        $address = $postcode;
        $url = $url . urlencode($address);

        $resp_json = $this->curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if ($resp['status'] == 'OK') {
            return $resp['results'][0]['geometry']['location'];
        } else {
            return false;
        }
    }

    public function curl_file_get_contents($URL) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents)
            return $contents;
        else
            return FALSE;
    }

    public function setListing() {

        global $wpdb;


        $this->rrp = 4;
        $this->page = isset($this->params[2]) ? $this->params[2] : 1;
        $this->count = $wpdb->get_var("select count(1) from {$wpdb->prefix}posts where post_type='post' "); #
        $this->pages = ceil($this->count / $this->rrp);


        //lets work out the limits
        $this->offset = ($this->page - 1) * $this->rrp;
        $this->range = $this->rrp;


        $pagination = new Pagination_Model($this->pages, $this->page, 3);
        $this->pagination = $pagination->no;

        $this->posts = (object) $wpdb->get_results("select * from {$wpdb->prefix}posts where post_type='post' limit $this->offset,$this->range");
    }

    /*
     * Get the post
     */

    public function getPost($slug) {

        if ($slug)
            $res = new WP_Query("name=$slug");

        if (isset($res->post))
            return $res->post;
        else
            return false;
    }

}