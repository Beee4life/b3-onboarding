<?php
    
    /**
     * Content for the 'settings page'
     */
    function b3_user_register_settings() {
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html( __( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-login' ) ) );
        }
        ?>

        <div class="wrap">

            <h1>Login settings</h1>

            <div class="b3__tabs">
                <?php $default_tab = 'pages'; ?>
                <?php
                    $default_tab = 'pages';
                    $tabs        = array(
                        array(
                            'id'      => 'main',
                            'title'   => 'Main',
                            'content' => b3_render_tab_content( 'main' ),
                        ),
                        array(
                            'id'      => 'pages',
                            'title'   => 'Pages',
                            'content' => b3_render_tab_content( 'pages' ),
                        ),
                        array(
                            'id'      => 'settings',
                            'title'   => 'Settings',
                            'content' => b3_render_tab_content( 'settings' ),
                        ),
                        // array(
                        //     'id'      => 'recaptcha',
                        //     'title'   => 'Recaptcha',
                        //     'content' => b3_render_tab_content( 'recaptcha' ),
                        // ),
                        array(
                            'id'      => 'debug',
                            'title'   => 'Debug info',
                            'content' => b3_render_tab_content( 'debug' ),
                        ),
                    );
                ?>
                <div class="b3__tab-header">
                    <?php foreach ( $tabs as $tab ) { ?>
                        <button class="b3__tab-button<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' active' : false; ?>" onclick="openTab(event, '<?php echo $tab[ 'id' ]; ?>')">
                            <?php echo $tab[ 'title' ]; ?>
                        </button>
                    <?php } ?>
                </div>

                <div class="tab-contents">
                    <?php foreach ( $tabs as $tab ) { ?>
                        <div id="<?php echo $tab[ 'id' ]; ?>" class="b3__tab-content"<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' style="display: block;"' : false; ?>>
                            <div class="entry-content">
                                <?php if ( $tab[ 'content' ] ) { ?>
                                    <?php echo $tab[ 'content' ]; ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>

        </div><!-- end .wrap -->
    <?php }
