<?php 
/*
 * Get search query, if not redirect to home page
 */

if(!isset($_GET['s'])){
    header('location: /');
    die();
}

$rpp = 10; //results per page
$cur = get_query_var('paged')?get_query_var('paged'):1; //current page

$query_args = array(
    'paged' => $cur,
    's' => urldecode($_GET['s']),
    'posts_per_page'=>$rpp);

$res = new WP_Query($query_args );
$pgs = $res->max_num_pages;
$ttl = $res->found_posts;
$obj = $res->posts;


$list = new Ibro_General_Listing($obj);
$list->setRepeaterHTML('<li><h4><a href="{{link}}">{{title}}</a></h4><p class="date">{{date}}</p>{{image}}<p>{{content}}</p></li>');

?>

<?php get_block('header');?>


<div class="content content-page">
    <?php get_block('navLeft');?>
    <div id="body-content">
        <h2>Search results for "<?php echo isset($_GET['s'])?$_GET['s']:''; ?>"</h2>
        <div class="listpage">
            
            <?php if($ttl>0){?>
            <ol class="listing">
                    <?php $list->renderList();?>
            </ol>
            <div class="pagination">
                <?php echo RR_pagenation($pgs);?>
                <br/>
            </div>
            <?php }else {?>
                <h2>Your search query had no results</h2>
            <?php }?>
        </div>
    </div>
</div>

<?php get_block('footer'); ?>