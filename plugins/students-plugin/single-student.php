<?php
get_header();
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();
		$enrolled_classes = get_the_category();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
				<div style="float: left;">
					<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); ?>
					<h3>Enrolled classes: </h3>
					<ul>
						<?php foreach( $enrolled_classes as $class ) : ?>
						<li><?php echo esc_html( $class->name ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php
			// End of the loop.
		endwhile;
		?>

	</main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
