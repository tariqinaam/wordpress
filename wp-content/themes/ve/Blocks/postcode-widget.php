<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
 <?php 
 $postcode = $_GET['addressInput'];
 ?>
            <div>
                Postcode Finder
                <form id="postfinder"  action="/postcode-finder/">
                    <input type="text" name ="addressInput" id ="addressInput" value="<?php echo $postcode ?>">
                    <input type="submit" name="submit" value="Search" >
                </form>
            </div>
