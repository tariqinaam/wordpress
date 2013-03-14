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
$this->getBlock('header');
?>
<script src="<?php echo rel_js; ?>/map.js" type="text/javascript"></script>

<body onload="googleMaps()">
    <div id="primary" class="site-content">
        <div id="content" role="main">
            <?php
            //get postcode finder widget
            $this->getBlock('postcode-widget');
            if ($_GET[addressInput]) {
                //get map
                $this->getBlock('map');
            } else {
                ?>
                <div>Please type your postcode.</div>
<?php } ?>
        </div><!-- #content -->
    </div><!-- #primary -->
</body>

<?php // get_sidebar();    ?>
<?php get_footer(); ?>