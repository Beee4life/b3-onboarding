<?php
    /**
     * Render pages tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_render_pages_tab() {
        // get stored pages
        $b3_pages = array(
            array(
                'id'      => 'register_page',
                'label'   => esc_html__( 'Register', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_register_page_id' ),
            ),
            array(
                'id'      => 'login_page',
                'label'   => esc_html__( 'Log In', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_login_page_id' ),
            ),
            array(
                'id'      => 'logout_page',
                'label'   => esc_html__( 'Log Out', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_logout_page_id' ),
            ),
            array(
                'id'      => 'lost_password_page',
                'label'   => esc_html__( 'Lost Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_lost_password_page_id' ),
            ),
            array(
                'id'      => 'reset_password_page',
                'label'   => esc_html__( 'Reset Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_reset_password_page_id' ),
            ),
            array(
                'id'      => 'account_page',
                'label'   => esc_html__( 'Account', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_account_page_id' ),
            ),
        );

        $front_end_approval = array(
            'id'      => 'approval_page',
            'label'   => esc_html__( 'Approval page', 'b3-onboarding' ),
            'page_id' => get_option( 'b3_approval_page_id' ),
        );

        if ( true == get_option( 'b3_front_end_approval' ) ) {
            $b3_pages[] = $front_end_approval;
        }

        // get all pages
        $all_pages = get_posts( array(
            'post_type'      => 'page',
            'post_status'    => array( 'publish', 'pending', 'draft' ),
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=pages" method="post">
            <input name="b3_pages_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-pages-nonce' ); ?>" />
            <?php echo sprintf( '<h2>%s</h2>', esc_html__( 'Pages', 'b3-onboarding' ) ); ?>
            <?php echo sprintf( '<p>%s</p>', esc_html__( "Here you can set which pages are assigned for the various 'actions'.", 'b3-onboarding' ) ); ?>

            <?php foreach( $b3_pages as $page ) { ?>
                <div class="b3_select-page">
                    <?php b3_get_label_field_open(); ?>
                    <label for="b3_<?php echo esc_attr( $page[ 'id' ] ); ?>"><?php echo esc_attr( $page[ 'label' ] ); ?></label>
                    <?php b3_get_close(); ?>

                    <div class="b3_select-page__selector">
                        <select name="b3_<?php echo esc_attr( $page[ 'id' ] ); ?>_id" id="b3_<?php echo esc_attr( $page[ 'id' ] ); ?>">
                            <option value=""><?php esc_attr_e( "Select a page", "b3-user-regiser" ); ?></option>
                            <?php if ( class_exists( 'SitePress' ) ) { ?>
                                <?php $default_lang = apply_filters( 'wpml_default_language', null ); ?>
                                <?php foreach( $all_pages as $active_page ) { ?>
                                    <?php if ( function_exists( 'wpml_get_language_information' ) ) { ?>
                                        <?php $post_language_information = wpml_get_language_information( '', $active_page->ID ); ?>
                                        <?php if ( $post_language_information[ 'language_code' ] == $default_lang ) { ?>
                                            <?php $selected = ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>
                                            <option value="<?php echo $active_page->ID; ?>"<?php echo $selected; ?>> <?php echo $active_page->post_title; ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php $selected = ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>
                                        <option value="<?php echo $active_page->ID; ?>"<?php echo $selected; ?>> <?php echo $active_page->post_title; ?></option>
                                    <?php } ?>

                                <?php } ?>
                            <?php } else { ?>
                                <?php foreach( $all_pages as $active_page ) { ?>
                                    <?php $selected = ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>
                                    <option value="<?php echo $active_page->ID; ?>"<?php echo $selected; ?>> <?php echo $active_page->post_title; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>

                    <?php if ( false == $page[ 'page_id' ] ) { ?>
                        <div class="b3_select-page__create">
                            <a href="<?php echo esc_url( admin_url( '/post-new.php?post_type=page' ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Add new', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Add new', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ( false != get_option( 'b3_' . $page[ 'id' ] . '_id' ) ) { ?>
                        <div class="b3_select-page__edit">
                            <a href="<?php echo esc_url( get_edit_post_link( get_option( 'b3_' . $page[ 'id' ] . '_id' ) ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Edit', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Edit', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                        <div class="b3_select-page__link">
                            <a href="<?php echo esc_url( get_the_permalink( get_option( 'b3_' . $page[ 'id' ] . '_id' ) ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Visit', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Visit', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                    <?php } ?>

                </div>
            <?php } ?>

            <p><small><?php esc_html_e( 'Links open in new tab.', 'b3-onboarding' ); ?></small></p>

            <?php b3_get_submit_button(); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
