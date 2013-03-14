<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
//get the postcode if it is in get request
$postcode = $_GET['addressInput'];

//if postcode exist in get variable, get the total number of rows returned
$total_rows = $this->get_total_rows($postcode);

//get the current page number
$current_page = get_query_var('paged') ? get_query_var('paged') : 1; //current page
//instantiate and get pagination
$paginate_obj = new pagination();
$result_per_page = 4;
$pagination_array = $paginate_obj->calculate_pages($total_rows, $result_per_page, $current_page);

//get the results convert the received xml formated data into array obejct
$result = simplexml_load_string($this->find_agent($pagination_array, $postcode));

$this->insert_search_data($postcode, $total_rows);
    if ($result) {
        ?>

        <div id = "map" style = "width: 500px; height: 300px"></div>


        <ul id = "results">
            <?php foreach ($result->marker as $key => $value) {
                ?>

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

            <?php if ($total_rows > $result_per_page) { ?>
                <?php
                if ($current_page != '1') {
                    printf('<a href="/postcode-finder/page/%s/?addressInput=%s"><<<a>', $pagination_array['previous'], $postcode);
                } else {
                    printf('<a href="#"><<<a>');
                }
                foreach ($pagination_array[pages] as $pages) {
                    // echo "<a href='/postcode-finder/?addressInput=$postcode&page=$pages'>$pages<a>"; 

                    printf('<a href="/postcode-finder/page/%s/?addressInput=%s">%s<a>', $pages, $postcode, $pages);
                }
                ?>
                <?php
                if ($current_page != $pagination_array['last']) {
                    printf('<a href="/postcode-finder/page/%s/?addressInput=%s">>><a>', $pagination_array['next'], $postcode);
                } else {
                    printf('<a href="#">>><a>');
                }
                ?>

            <?php } ?>
        </div>

    <?php } else if ($_GET['addressInput']) { ?>
        <div>No result Found. Check your postcode.</div>
        <?php
    
} 