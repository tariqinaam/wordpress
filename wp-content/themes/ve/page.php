<?php


//pre_dump($this);


$this->getBlock('header'); ?>
<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
                  
				
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php $this->getBlock('footer'); ?>