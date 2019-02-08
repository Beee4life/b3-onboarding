<?php
    
    /**
     * Content for the 'settings page'
     */
    function b3_user_register_settings() {
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-login' ) );
        }
        ?>

        <div class="b3 b3__admin">

            <h1 id="b3__admin-title"><?php _e( 'Onboarding settings', 'b3-onboarding' ); ?></h1>

            <div class="b3__tabs">
                <?php
                    if ( isset( $_GET[ 'tab' ] ) ) {
                        $default_tab = $_GET[ 'tab' ];
                    } else {
                        $default_tab = 'pages';
                    }

                    $tabs        = array(
                        array(
                            'id'      => 'pages',
                            'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'pages' ),
                            'icon'    => 'admin-page',
                        ),
                    );

                    $tabs[] = array(
                        'id'      => 'emails',
                        'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                        'content' => b3_render_tab_content( 'emails' ),
                        'icon'    => 'email',
                    );
                    
                    if ( current_user_can( 'manage_options' ) ) {
                        $tabs[] = array(
                            'id'      => 'debug',
                            'title'   => esc_html__( 'Debug info', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'debug' ),
                            'icon'    => 'shield',
                        );
                    }
                ?>
                <div class="b3__tab-header">
                    <?php foreach ( $tabs as $tab ) { ?>
                        <button class="b3__tab-button<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' active' : false; ?>" onclick="openTab(event, '<?php echo $tab[ 'id' ]; ?>')">
                            <?php if ( isset( $tab[ 'icon' ] ) ) { ?>
                                <i class="dashicons dashicons-<?php echo $tab[ 'icon' ]; ?>"></i>
                            <?php } ?>
                            <?php echo $tab[ 'title' ]; ?>
                        </button>
                    <?php } ?>
                </div>

                <div class="tab-contents">
                    <?php foreach ( $tabs as $tab ) { ?>
                        <div id="<?php echo $tab[ 'id' ]; ?>" class="b3__tab-content b3__tab-content--<?php echo $tab[ 'id' ]; ?>"<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' style="display: block;"' : false; ?>>
                            <?php if ( $tab[ 'content' ] ) { ?>
                                <?php echo $tab[ 'content' ]; ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

            </div>

        </div>
    <?php }
