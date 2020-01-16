<?php
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$args          = array(
	'post_type'      => 'student',
	'posts_per_page' => 2,
	'paged'          => $paged,
	'meta_key'       => 'student_status',
	'meta_value'     => 1,
);
$student_query = new WP_Query( $args );
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php if ( $student_query->have_posts() ) : ?>
			<header class="page-header"><h2><?php _e( 'Students', 'dx-students' ); ?></h2></header>
			<?php
			// Start the Loop.
			while ( $student_query->have_posts() ) :
				$student_query->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header><!-- .entry-header -->
					<?php
					echo '<div style="float: left; margin-right: 5px;">' . get_the_post_thumbnail( get_the_ID(), 'thumbnail' ) . '</div>';
					the_excerpt();
					?>
					<div class="entry-content">
						<?php
						wp_link_pages(
							array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
								'separator'   => '<span class="screen-reader-text">, </span>',
							)
						);
						?>
					</div>
				</article>
				<?php
			endwhile; // end loop.
			the_posts_pagination(
				array(
					'prev_text'          => __( 'Previous page', 'dx-students' ),
					'next_text'          => __( 'Next page', 'dx-students' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'dx-students' ) . ' </span>',
				)
			);
		else :
			_e( 'Nothing found so far, sorry', 'dx-students' );

		endif;
		?>

	</main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
