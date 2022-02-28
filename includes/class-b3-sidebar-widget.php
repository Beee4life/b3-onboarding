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
        function __construct() {
            parent::__construct(
                'b3-widget',
                'B3 User menu',
                array(
                    'classname'   => 'b3__widget--user',
                    'description' => __( 'Custom user widget', 'b3-onboarding' ),
                )
            );
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
            $count_errors       = array();
            $count_setting      = 0;
            $main_logo          = get_option( 'b3_main_logo' );
            $show_account       = ! empty( $instance[ 'show_account' ] ) ? $instance[ 'show_account' ] : false;
            $show_widget        = true;
            $show_register_link = false;
            $show_settings      = false;
            $use_popup          = get_option( 'b3_use_popup' );

            if ( $show_account ) {
                $account_id    = b3_get_account_url( true );
                $account_title = esc_html__( 'Account', 'b3-onboarding' );
                $account_url   = b3_get_account_url();
                if ( false == $account_id ) {
                    $count_errors[] = 'account';
                } else {
                    $account_title = get_the_title( $account_id );
                    if ( false == $account_title ) {
                        $count_errors[] = 'account_title';
                    }
                    $account_url   = get_the_permalink( $account_id );
                    if ( false == $account_url ) {
                        $count_errors[] = 'account_link';
                    }
                }
                $count_setting++;
            }

            $show_login = ! empty( $instance[ 'show_login' ] ) ? $instance[ 'show_login' ] : false;
            if ( $show_login ) {
                $login_id    = b3_get_login_url( true );
                $login_title = esc_html__( 'Login', 'b3-onboarding' );
                $login_url   = b3_get_login_url();
                if ( false == $login_id ) {
                    $count_errors[] = 'login';
                } else {
                    $login_title = get_the_title( $login_id );
                    $login_url   = get_the_permalink( $login_id );
                }
                $count_setting++;
            }

            $show_logout = ! empty( $instance[ 'show_logout' ] ) ? $instance[ 'show_logout' ] : false;
            if ( $show_logout ) {
                $logout_url = b3_get_logout_url();
                if ( false == $logout_url ) {
                    $count_errors[] = 'logout';
                }
                $count_setting++;
            }

            $show_register = ! empty( $instance[ 'show_register' ] ) ? $instance[ 'show_register' ] : false;
            if ( $show_register ) {
                $register_id       = b3_get_register_url( true );
                $registration_type = get_option( 'b3_registration_type' );

                if ( 'none' != $registration_type ) {
                    if ( false == $register_id ) {
                        $count_errors[] = 'register';
                    } else {
                        if ( 'blog' == $registration_type && is_user_logged_in() ) {
                            $show_register_link = true;
                        } elseif ( ! is_user_logged_in() ) {
                            $show_register_link = true;
                        }

                        if ( true == $show_register_link ) {
                            $register_title = get_the_title( $register_id );
                            $register_url   = get_the_permalink( $register_id );
                        }
                    }
                    $count_setting++;
                }
            }

            if ( current_user_can( 'manage_options' ) ) {
                $show_settings = ( false != $instance[ 'show_settings' ] ) ? $instance[ 'show_settings' ] : false;
                if ( $show_settings ) {
                    $count_setting++;
                }

                $show_user_approval = ! empty( $instance[ 'show_approval' ] ) ? $instance[ 'show_approval' ] : false;
                if ( $show_user_approval ) {
                    $count_setting++;
                }
            }

            if ( 0 == $count_setting ) {
                $show_widget = false;
                if ( current_user_can( 'manage_options' ) ) {
                    echo $args[ 'before_widget' ];
                    if ( ! empty( $instance[ 'title' ] ) ) {
                        echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
                    }
                    echo sprintf( '<p class="widget-no-settings">%s</p>', sprintf( __( "You haven't set any widget settings. Configure them %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'widgets.php' ) ), esc_html__( 'here', 'b3-onboarding' ) ) ) );
                    echo $args[ 'after_widget' ];
                }
            }

            $custom_links = apply_filters( 'b3_widget_links', array() );

            if ( ! isset( $login_url ) && ! isset( $register_url ) && ! isset( $logout_url ) && empty( $custom_links ) ) {
                $show_widget = false;
                if ( false != $show_settings && current_user_can( 'manage_options' ) ) {
                    $show_widget = true;
                }
            }

            if ( true === $show_widget ) {
                echo $args[ 'before_widget' ];

                if ( ! empty( $instance[ 'title' ] ) ) {
                    echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
                }

                echo '<ul>';
                if ( ! is_user_logged_in() ) {
                    if ( $show_login ) {
                        echo '<li>';
                        if ( true == $use_popup ) {
                            echo '<a href="#login-form" rel="modal:open">' . $login_title . '</a>';
                            echo '<div id="login-form" class="modal">';
                            if ( false != $main_logo ) {
                                echo sprintf( '<div class="modal__logo"><img src="%s" alt="" /></div>', $main_logo );
                            }
                            echo do_shortcode('[login-form]');
                            echo '</div>';
                        } else {
                            echo '<a href="' . $login_url . '">' . $login_title . '</a>';
                        }
                        echo '</li>';
                    }
                    if ( isset( $register_url ) && true == $show_register_link ) {
                        echo '<li><a href="' . $register_url . '">' . $register_title . '</a></li>';
                    }
                    if ( is_array( $custom_links ) && ! empty( $custom_links ) ) {
                        foreach( $custom_links as $link ) {
                            echo '<li><a href="' . $link[ 'link' ] . '">' . $link[ 'label' ] . '</a></li>';
                        }
                    }
                } else {
                    if ( isset( $register_url ) && true == $show_register_link ) {
                        echo '<li><a href="' . $register_url . '">' . $register_title . '</a></li>';
                    }
                    if ( isset( $account_url ) && false != $account_url ) {
                        echo '<li><a href="' . $account_url . '">' . $account_title . '</a></li>';
                    }
                    if ( true == $show_settings && current_user_can( 'manage_options' ) ) {
                        echo '<li><a href="' . admin_url( 'admin.php?page=b3-onboarding' ) . '">B3 ' . esc_html__( 'Settings', 'b3-onboarding' ) . '</a></li>';
                    }

                    if ( is_array( $custom_links ) && ! empty( $custom_links ) ) {
                        foreach( $custom_links as $link ) {
                            echo '<li><a href="' . $link[ 'link' ] . '">' . $link[ 'label' ] . '</a></li>';
                        }
                    }
                    if ( isset( $logout_url ) && false != $logout_url ) {
                        echo '<li><a href="' . $logout_url . '">' . esc_html__( 'Log Out', 'b3-onboarding' ) . '</a></li>';
                    }
                }
                echo '</ul>';

                echo $args[ 'after_widget' ];
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
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_register' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_register' ) ); ?>" type="checkbox" value="1"<?php if ( 1 == $show_register ) { echo ' checked="checked"'; } ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_register' ) ); ?>"><?php esc_attr_e( 'Show register link', 'b3-onboarding' ); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_login' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_login' ) ); ?>" type="checkbox" value="1"<?php if ( 1 == $show_login ) { echo ' checked="checked"'; } ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_login' ) ); ?>"><?php esc_attr_e( 'Show login link', 'b3-onboarding' ); ?></label>
            </p>
            <b><?php esc_html_e( 'Logged In', 'b3-onboarding' ); ?></b>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_account' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_account' ) ); ?>" type="checkbox" value="1"<?php if ( 1 == $show_account ) { echo ' checked="checked"'; } ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_account' ) ); ?>"><?php esc_attr_e( 'Show account link', 'b3-onboarding' ); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_logout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_logout' ) ); ?>" type="checkbox" value="1"<?php if ( 1 == $show_logout ) { echo ' checked="checked"'; } ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_logout' ) ); ?>"><?php esc_attr_e( 'Show log out link', 'b3-onboarding' ); ?></label>
            </p>
            <b><?php esc_html_e( 'Admin only', 'b3-onboarding' ); ?></b>
            <?php if ( 'request_access' == $registration_type ) { ?>
                <p>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_approval' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_approval' ) ); ?>" type="checkbox" value="1"<?php if ( 1 == $show_user_approval ) { echo ' checked="checked"'; } ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'show_approval' ) ); ?>"><?php esc_attr_e( 'Show user management link', 'b3-onboarding' ); ?></label>
                </p>
            <?php } ?>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_settings' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_settings' ) ); ?>" type="checkbox" value="1"<?php if ( 1 == $show_settings ) { echo ' checked="checked"'; } ?>>
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
            $instance                    = array();
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
