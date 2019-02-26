<?php
    
    class B3WidgetSidebar extends WP_Widget {
        
        function __construct() {
            parent::__construct(
                'b3-widget',
                'B3: User menu',
                array(
                    'classname'   => 'b3__widget--user',
                    'description' => 'Custom user menu'
                )
            );
        }
    
        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {
            echo $args[ 'before_widget' ];
            
            if ( ! empty( $instance[ 'title' ] ) ) {
                echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
            }
            
            echo '<ul>';
            if ( ! is_user_logged_in() ) {
                echo '<li><a href="">Login</a></li>';
                echo '<li><a href="">Register</a></li>';
            } else {
                if ( current_user_can( 'manage_options' ) ) {
                    echo '<li><a href="'.admin_url( 'admin.php?page=b3-onboarding' ) .'">Settings</a></li>';
                }
                echo '<li><a href="">Account</a></li>';
                echo '<li><a href="">Log out</a></li>';
            }
            echo '</ul>';
            
            echo $args[ 'after_widget' ];
        }
    
        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form( $instance ) {
            $title = ! empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : esc_html__( 'User menu', 'b3-onboarding' );
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'b3-onboarding' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <?php
        }
    
        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update( $new_instance, $old_instance ) {
            $instance            = array();
            $instance[ 'title' ] = ( ! empty( $new_instance[ 'title' ] ) ) ? sanitize_text_field( $new_instance[ 'title' ] ) : '';
        
            return $instance;
        }
    }
