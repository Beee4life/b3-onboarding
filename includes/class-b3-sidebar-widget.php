<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Class B3_Sidebar_Widget
     */
    class B3_Sidebar_Widget extends WP_Widget {
        /**
         * B3_Sidebar_Widget constructor.
         */
        public function __construct() {
            parent::__construct(
                'b3-widget',
                'B3 User menu',
				[
					'classname'   => 'b3__widget--user',
					'description' => esc_html__( 'Custom user widget', 'b3-onboarding' ),
				] );
        }


        /**
         * Front-end display of widget.
         *
         * @since 1.0.0
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {
            $custom_links       = apply_filters( 'b3_widget_links', [] );
            $main_logo          = get_option( 'b3_main_logo' );
            $show_account       = ! empty( $instance[ 'show_account' ] ) ? $instance[ 'show_account' ] : false;
            $show_login         = ! empty( $instance[ 'show_login' ] ) ? $instance[ 'show_login' ] : false;
            $show_logout        = ! empty( $instance[ 'show_logout' ] ) ? $instance[ 'show_logout' ] : false;
            $show_register      = ! empty( $instance[ 'show_register' ] ) ? $instance[ 'show_register' ] : false;
            $show_widget        = false;
            $show_register_link = false;
            $show_settings      = false;
            $use_popup          = get_option( 'b3_use_popup' );
            $is_user_logged_in  = is_user_logged_in();

            if ( $show_account ) {
                $account_url = b3_get_account_url();
            }

            if ( $show_login ) {
                $login_url = b3_get_login_url();
            }

            if ( $show_logout ) {
                $logout_url = b3_get_logout_url();
            }

            if ( $show_register ) {
                $register_url      = b3_get_register_url();
                $registration_type = get_option( 'b3_registration_type' );

				if ( 'none' !== $registration_type && false !== $register_url ) {
                    if ( 'blog' === $registration_type && $is_user_logged_in ) {
                        $show_register_link = true;
                    } elseif ( ! $is_user_logged_in ) {
                        $show_register_link = true;
                    }
                }
            }

            if ( current_user_can( apply_filters( 'b3_user_cap', 'manage_options' ) ) ) {
                $show_settings = ( '1' == $instance[ 'show_settings' ] ) ? true : false;
            }

            if ( isset( $login_url ) || isset( $register_url ) || isset( $logout_url ) || empty( $custom_links ) ) {
                $show_widget = true;
            }
            if ( false !== $show_settings ) {
                $show_widget = true;
            }

            if ( true === $show_widget ) {
				$widget_links = [];
                if ( ! $is_user_logged_in && $show_login ) {
                    if ( true == $use_popup ) {
                        $logo = ( false != $main_logo ) ? sprintf( '<div class="modal__logo"><img src="%s" alt="" /></div>', $main_logo ) : false;
                        $link = sprintf('<a href="#login-form" rel="modal:open">%s</a>', esc_html__( 'Login', 'b3-onboarding' ) );
                        $link .= sprintf( '<div id="login-form" class="modal">%s%s</div>', $logo, do_shortcode('[login-form title="Login"]') );
                    } else {
                        $link = sprintf( '<a href="%s">%s</a>', esc_url( $login_url ), esc_html__( 'Login', 'b3-onboarding' ) );
                    }
					$widget_links[] = $link;
                }

				if ( isset( $register_url ) && true === $show_register_link ) {
					$widget_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $register_url ), esc_html__( 'Register', 'b3-onboarding' ) );
                }

                if ( $is_user_logged_in && isset( $account_url ) && false !== $account_url ) {
					$widget_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $account_url ), esc_html__( 'Account', 'b3-onboarding' ) );
                }

                if ( true === $show_settings ) {
					$widget_links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding' ) ), 'B3 ' . esc_html__( 'Settings', 'b3-onboarding' ) );
                }

                if ( is_array( $custom_links ) && ! empty( $custom_links ) ) {
                    foreach( $custom_links as $link ) {
						$widget_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $link[ 'link' ] ), $link[ 'label' ] );
                    }
                }

                if ( $is_user_logged_in ) {
                    if ( isset( $logout_url ) && false !== $logout_url ) {
						$widget_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $logout_url ), esc_html__( 'Log Out', 'b3-onboarding' ) );
                    }
                }

				if ( ! empty( $widget_links ) ) {
	                echo $args[ 'before_widget' ];

					if ( ! empty( $instance[ 'title' ] ) ) {
						echo $args[ 'before_title' ];
						echo apply_filters( 'widget_title', $instance[ 'title' ] );
						echo $args[ 'after_title' ];
					}

					foreach( $widget_links as $link ) {
						echo sprintf( '<li>%s</li>', $link );
					}

					echo $args[ 'after_widget' ];
				}

            }
        }


        /**
         * Back-end widget form.
         *
         * @since 1.0.0
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form( $instance ) {
            $registration_type  = get_option( 'b3_registration_type' );
            $show_account       = ! empty( $instance[ 'show_account' ] ) ? $instance[ 'show_account' ] : '';
            $show_login         = ! empty( $instance[ 'show_login' ] ) ? $instance[ 'show_login' ] : '';
            $show_logout        = ! empty( $instance[ 'show_logout' ] ) ? $instance[ 'show_logout' ] : '';
            $show_register      = ! empty( $instance[ 'show_register' ] ) ? $instance[ 'show_register' ] : '';
            $show_settings      = ! empty( $instance[ 'show_settings' ] ) ? $instance[ 'show_settings' ] : '';
            $show_user_approval = ! empty( $instance[ 'show_approval' ] ) ? $instance[ 'show_approval' ] : '';
            $title              = ! empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'b3-onboarding' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <b><?php esc_html_e( 'Logged Out', 'b3-onboarding' ); ?></b>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_register' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_register' ) ); ?>" type="checkbox" value="1"<?php checked($show_register); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_register' ) ); ?>"><?php esc_attr_e( 'Show register link', 'b3-onboarding' ); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_login' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_login' ) ); ?>" type="checkbox" value="1"<?php checked($show_login); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_login' ) ); ?>"><?php esc_attr_e( 'Show login link', 'b3-onboarding' ); ?></label>
            </p>
            <b><?php esc_html_e( 'Logged In', 'b3-onboarding' ); ?></b>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_account' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_account' ) ); ?>" type="checkbox" value="1"<?php checked($show_account); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_account' ) ); ?>"><?php esc_attr_e( 'Show account link', 'b3-onboarding' ); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_logout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_logout' ) ); ?>" type="checkbox" value="1"<?php checked($show_logout); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_logout' ) ); ?>"><?php esc_attr_e( 'Show log out link', 'b3-onboarding' ); ?></label>
            </p>
            <b><?php esc_html_e( 'Admin only', 'b3-onboarding' ); ?></b>
            <?php if ( 'request_access' == $registration_type ) { ?>
                <p>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_approval' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_approval' ) ); ?>" type="checkbox" value="1"<?php checked($show_user_approval); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'show_approval' ) ); ?>"><?php esc_attr_e( 'Show user management link', 'b3-onboarding' ); ?></label>
                </p>
            <?php } ?>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_settings' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_settings' ) ); ?>" type="checkbox" value="1"<?php checked($show_settings); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_settings' ) ); ?>"><?php esc_attr_e( 'Show settings link', 'b3-onboarding' ); ?></label>
            </p>
            <?php
        }


        /**
         * Sanitize widget form values as they are saved.
         *
         * @since 1.0.0
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update( $new_instance, $old_instance ) {
            $instance                    = [];
			$instance[ 'show_account' ]  = ( ! empty( $new_instance[ 'show_account' ] ) ) ? $new_instance[ 'show_account' ] : '';
            $instance[ 'show_approval' ] = ( ! empty( $new_instance[ 'show_approval' ] ) ) ? $new_instance[ 'show_approval' ] : '';
            $instance[ 'show_login' ]    = ( ! empty( $new_instance[ 'show_login' ] ) ) ? $new_instance[ 'show_login' ] : '';
            $instance[ 'show_logout' ]   = ( ! empty( $new_instance[ 'show_logout' ] ) ) ? $new_instance[ 'show_logout' ] : '';
            $instance[ 'show_settings' ] = ( ! empty( $new_instance[ 'show_settings' ] ) ) ? $new_instance[ 'show_settings' ] : '';
            $instance[ 'show_register' ] = ( ! empty( $new_instance[ 'show_register' ] ) ) ? $new_instance[ 'show_register' ] : '';
            $instance[ 'title' ]         = ( ! empty( $new_instance[ 'title' ] ) ) ? sanitize_text_field( $new_instance[ 'title' ] ) : '';

            return $instance;
        }
    }
    register_widget( 'B3_Sidebar_Widget' );
