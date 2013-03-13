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



<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php
        if (!empty($_GET['flingmatic'])) {
            $tax = $_GET['flingmatic'];
        } else {
            $tax = 'couples';
        }
        $post_type_name = 'experience';
        $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
        //vdump($_GET);
        ?>
        <?php
        $tax_terms = get_terms('demography');
        //vdump($tax_terms);
        ?>
        <div><form action="">
                <select name="flingmatic" id="flingmatic-select" class="flingmatic-select" style="float: left" >
                    <?php
                    $selected = "";
                    $value = "selected = $tax";
                    if ($tax_terms) {

                        foreach ($tax_terms as $tax_terms) {
                            //echo $tax_terms->slug;
                            ?>
                            <?php
                            if ($tax_terms->slug == $tax) {
                                $selected = $value;
                                ?>
                                <option value="<?php echo $tax_terms->slug ?>" <?php echo $selected; ?>><?php echo $tax_terms->name ?></option>
                            <?php } else {
                                ?>
                                <option value="<?php echo $tax_terms->slug ?>"><?php echo $tax_terms->name ?></option>   
                                <?php
                            }
                        }
                    }
                    ?>


                </select>
                <div class="flingmatic-end">
                    <input type="submit" value="Generate" id="flingmatic-button" class="flingmatic-button button ctaButton">
                </div>
            </form>
        </div>
        <?php
        $args1 = array(
            'post_type' => $post_type_name,
            "demography" => $tax,
            'post_status' => 'publish',
            'posts_per_page' => 2,
            'paged' => $current_page
        );
        $experience = new WP_Query($args1);
        $experience->found_posts;
        // vdump($experience);
        ?>
        <?php //if ($experience->have_posts()) { ?>
        <?php while ($experience->have_posts()) : $experience->the_post(); ?>
            <?php //$experience->the_post(); ?>
            <div>   
                <h1 style="margin-bottom: 5px; "><?php the_title(); ?></h1>
                <div>
                    <div style="width: 125px;height: 125px;float: left"><?php the_post_thumbnail(); ?></div>
                    <div><?php the_content(); ?>
                    </div>
                </div>
            </div>

        <?php endwhile; // end of the loop.    ?>
        <?php // } ?>
        <?php
//$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
        //$custom_query = new WP_Query('post_type='. $post_type_name . '&demogarphy=' .$tax. '&posts_per_page=1&paged='.$current_page); 
        //vdump($experience);
        //vdump(new WP_Query($args1));
        ?>
        <div style="margin-top: 115px;">
        <?php wp_paginate(false, new WP_Query($args1)); ?>
        </div>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>