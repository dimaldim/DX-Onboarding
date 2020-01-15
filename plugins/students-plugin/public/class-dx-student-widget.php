<?php
if ( ! class_exists( 'DX_Student_Widget' ) ) {
	/**
	 * Class DX_Student_Widget
	 */
	class DX_Student_Widget extends WP_Widget {
		/**
		 * DX_Student_Widget constructor.
		 */
		public function __construct() {
			parent::__construct(
				'dx-student-widget',
				__( 'DX Student Widget', 'dx-students' ),
				array(
					'description' => __( 'DX Student widget for displaying students', 'dx-students' ),
				)
			);
		}

		public function widget( $args, $instance ) {
			$title          = apply_filters( 'widget_title', __( 'Students', 'dx-students' ) );
			$student_status = 'active' === $instance['dx_student_widget_student_status'] ? 1 : 0;
			$per_page       = $instance['dx_student_widget_posts_per_page'];

			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$student_args = array(
				'post_type'      => 'student',
				'posts_per_page' => $per_page,
				'meta_key'       => 'student_status',
				'meta_value'     => $student_status,
			);
			$query        = new WP_Query( $student_args );

			if ( $query->have_posts() ) {
				echo '<ul>';
				while ( $query->have_posts() ) {
					$query->the_post();
					echo '<li><a href="' . get_the_permalink( $query->post->ID ) . '">' . get_the_title() . '</a></li>';
				}
				echo '</ul>';
			} else {
				echo __( 'No students to display.', 'dx-students' );
			}

			wp_reset_postdata();

			echo $args['after_widget'];
		}

		public function form( $instance ) {
			$dx_student_widget_posts_per_page = isset( $instance['dx_student_widget_posts_per_page'] ) ? $instance['dx_student_widget_posts_per_page'] : '';
			$dx_student_widget_student_status = isset( $instance['dx_student_widget_student_status'] ) ? $instance['dx_student_widget_student_status'] : '';
			?>
			<p>
				<label for="<?php echo $this->get_field_name( 'dx_student_widget_posts_per_page' ); ?>">
					<?php _e( 'Posts per page: ', 'dx-students' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name( 'dx_student_widget_posts_per_page' ); ?>"
						id="<?php echo $this->get_field_name( 'dx_student_widget_posts_per_page' ); ?>">
					<option value="2" <?php selected( $dx_student_widget_posts_per_page, 2, true ); ?>>2</option>
					<option value="5" <?php selected( $dx_student_widget_posts_per_page, 5, true ); ?>>5</option>
					<option value="10" <?php selected( $dx_student_widget_posts_per_page, 10, true ); ?>>10</option>
					<option value="15" <?php selected( $dx_student_widget_posts_per_page, 15, true ); ?>>15</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_name( 'dx_student_widget_student_status' ); ?>">
					<?php _e( 'Student status: ', 'dx-students' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name( 'dx_student_widget_student_status' ); ?>"
						id="<?php echo $this->get_field_name( 'dx_student_widget_student_status' ); ?>">
					<option value="active" <?php selected( $dx_student_widget_student_status, 'active', true ); ?>>
						Active
					</option>
					<option value="disabled" <?php selected( $dx_student_widget_student_status, 'disabled', true ); ?>>
						Disabled
					</option>
				</select>
			</p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance                                     = array();
			$instance['dx_student_widget_posts_per_page'] = ! empty( $new_instance['dx_student_widget_posts_per_page'] )
				? sanitize_text_field( $new_instance['dx_student_widget_posts_per_page'] ) : '';
			$instance['dx_student_widget_student_status'] = ! empty( $new_instance['dx_student_widget_student_status'] )
				? sanitize_text_field( $new_instance['dx_student_widget_student_status'] ) : '';

			return $instance;
		}
	}
}