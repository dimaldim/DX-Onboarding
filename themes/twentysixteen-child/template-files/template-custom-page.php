<?php
/* Template Name: My custom page template */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			while ( have_posts() ) :
				the_post();
				echo '<span style="display: block; ">' . the_title() . '</span>';
				printf( '%1$s %2$s', _x( 'Posted on', '', 'twentisixteen' ), get_the_date() );
				the_content();
			endwhile;
			?>

		</main>
	</div>
<?php get_footer(); ?>
