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
			$student_info     = get_post_meta( get_the_ID() );
			$lives_in         = ! empty( $student_info['student_lives_in'][0] ) ? esc_attr( $student_info['student_lives_in'][0] ) : '';
			$address          = esc_attr( $student_info['student_address'][0] );
			$birthdate        = esc_attr( $student_info['student_birthdate'][0] );
			$class            = esc_attr( $student_info['student_class_grade'][0] );
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
				<div class="dx-student-info">
					<?php
					echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
					?>
					<div class="student-info">
						<?php if ( ! empty( $lives_in ) ) : ?>
							<span class=""><strong>Lives In:</strong> <?php echo $lives_in; ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $address ) ) : ?>
							<span><strong>Address:</strong> <?php echo $address; ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $birthdate ) ) : ?>
							<span><strong>Birth Date:</strong> <?php echo $birthdate; ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $class ) ) : ?>
							<span><strong>Class / Grade:</strong> <?php echo $class; ?></span>
						<?php endif; ?>
					</div>
					<?php
					if ( ! empty( $enrolled_classes ) && is_array( $enrolled_classes ) ) :
						?>
						<div class="dx-student-class-info">
							<h3>Enrolled classes: </h3>
							<ul>
								<?php foreach ( $enrolled_classes as $class ) : ?>
									<li><?php echo esc_html( $class->name ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
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
