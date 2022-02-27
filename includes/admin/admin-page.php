<?php
    /**
     * Content for the 'settings page'
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_user_register_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-onboarding' ) );
        }
        ?>

        <div class="wrap b3 b3__admin">

            <?php if ( ! empty( $_GET[ 'preview' ] ) ) { ?>
                <?php
                    if ( 'styling' == $_GET[ 'preview' ] ) {
                        $page_title = esc_html__( 'Styling preview', 'b3-onboarding' );
                    } elseif ( 'template' == $_GET[ 'preview' ] ) {
                        $page_title = esc_html__( 'Template preview', 'b3-onboarding' );
                    } else {
                        $page_title = esc_html__( 'Email preview', 'b3-onboarding' );
                    }

                    echo sprintf( '<h1 id="b3__admin-title">%s</h1>', $page_title );

                    include 'preview.php';
                ?>

            <?php } else { ?>
                <?php
                    $default_tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : 'settings';
                    $tabs        = b3_get_admin_tabs();

                    echo sprintf( '<h1 id="b3__admin-title">%s</h1>', esc_html__( 'B3 OnBoarding settings', 'b3-onboarding' ) );

                    B3Onboarding::b3_show_admin_notices();

                    if ( is_array( $tabs ) ) {
                ?>
                    <div class="b3_tabs">
                        <div class="b3_tab-header">
                            <?php foreach ( $tabs as $tab ) { ?>
                                <button class="b3_tab-button b3_tab-button--<?php echo $tab[ 'id' ]; ?><?php echo ( $tab[ 'id' ] == $default_tab ) ? ' active' : false; ?>" onclick="openTab(event, '<?php echo $tab[ 'id' ]; ?>')">
                                    <?php if ( isset( $tab[ 'icon' ] ) ) { ?>
                                        <i class="dashicons dashicons-<?php echo $tab[ 'icon' ]; ?>"></i>
                                    <?php } ?>
                                    <?php echo $tab[ 'title' ]; ?>
                                </button>
                            <?php } ?>
                        </div>

                        <div class="tab-contents">
                            <?php foreach ( $tabs as $tab ) { ?>
                                <div id="<?php echo $tab[ 'id' ]; ?>" class="b3_tab-content b3_tab-content--<?php echo $tab[ 'id' ]; ?>"<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' style="display: block;"' : false; ?>>
                                    <?php if ( $tab[ 'content' ] ) { ?>
                                        <?php echo $tab[ 'content' ]; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } // end tabs ?>
            <?php } // end if preview ?>
        </div>
    <?php }
