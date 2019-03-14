<?php
    
    class B3SidebarWidget extends WP_Widget {
        
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
    
            $login_id      = get_option( 'b3_login_page_id' );
            $login_link    = ( false != $login_id ) ? get_permalink( $login_id ) : network_site_url( 'wp-login.php' );
            $account_id    = get_option( 'b3_account_page_id' );
            $account_link  = ( false != $account_id ) ? get_permalink( $account_id ) : false;
            $logout_id     = get_option( 'b3_logout_page_id' );
            $logout_link   = ( false != $logout_id ) ? get_permalink( $logout_id ) : wp_logout_url();
            $register_id   = get_option( 'b3_register_page_id' );
            $register_link = ( false != $register_id ) ? get_permalink( $register_id ) : network_site_url( 'wp-login.php?action=register' );
            
            echo '<ul>';
            if ( ! is_user_logged_in() ) {
                echo '<li><a href="'.$login_link.'">'.__( 'Login', 'b3-onboarding' ).'</a></li>';
                echo '<li><a href="'.$register_link.'">'.__( 'Register', 'b3-onboarding' ).'</a></li>';
            } else {
                if ( false != $account_link ) {
                    echo '<li><a href="' . $account_link . '">' . __( 'Account', 'b3-onboarding' ) . '</a></li>';
                }
                if ( current_user_can( 'manage_options' ) ) {
                    echo '<li><a href="' . admin_url( 'admin.php?page=b3-onboarding' ) . '">' . __( 'Settings', 'b3-onboarding' ) . '</a></li>';
                }
                echo '<li><a href="' . $logout_link . '">' . __( 'Log Out', 'b3-onboarding' ) . '</a></li>';
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
