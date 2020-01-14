<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<p>
			<?php
			printf(
				'%1$s <a href="%2$s">%3$s</a> on %4$s',
				_x( 'Posted by', '', 'twentisixteen' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author(),
				get_the_date( 'l j.m.Y' )
			);
			?>
		</p>
	</header><!-- .entry-header -->

	<?php
	if ( 8 === get_the_ID() ) {
		//the_excerpt();
		twentysixteen_excerpt();
	}
	?>

	<?php twentysixteen_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content();
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

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}
			?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //twentysixteen_entry_meta(); ?>
		<?php
//			edit_post_link(
//				sprintf(
//					/* translators: %s: Post title. */
//					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
//					get_the_title()
//				),
//				'<span class="edit-link">',
//				'</span>'
//			);
			?>
	</footer><!-- .entry-footer -->
</article><!-- #post--->
