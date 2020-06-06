<?php
    /**
     * Render pages tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_pages_tab() {

        // get stored pages
        $b3_pages = array(
            array(
                'id'      => 'register_page',
                'label'   => esc_html__( 'Register', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_register_page_id', false ),
            ),
            array(
                'id'      => 'login_page',
                'label'   => esc_html__( 'Log In', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_login_page_id', false ),
            ),
            array(
                'id'      => 'logout_page',
                'label'   => esc_html__( 'Log Out', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_logout_page_id', false ),
            ),
            array(
                'id'      => 'forgotpass_page',
                'label'   => esc_html__( 'Forgot Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_forgotpass_page_id', false ),
            ),
            array(
                'id'      => 'resetpass_page',
                'label'   => esc_html__( 'Reset Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_resetpass_page_id', false ),
            ),
            array(
                'id'      => 'account_page',
                'label'   => esc_html__( 'Account', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_account_page_id', false ),
            ),
        );

        $front_end_approval = array(
            'id'      => 'approval_page',
            'label'   => esc_html__( 'Approval page', 'b3-onboarding' ),
            'page_id' => get_option( 'b3_approval_page_id', false ),
        );

        if ( true == get_option( 'b3_front_end_approval', false ) ) {
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
            <h2>
                <?php esc_html_e( 'Pages', 'b3-onboarding' ); ?>
            </h2>

            <p>
                <?php esc_html_e( "Here you can set which pages are assigned for the various 'actions'.", "b3-onboarding" ); ?>
            </p>

            <?php foreach( $b3_pages as $page ) { ?>
                <div class="b3_select-page">
                    <?php b3_get_label_field_open(); ?>
                    <label for="b3_<?php echo $page[ 'id' ]; ?>"><?php echo $page[ 'label' ]; ?></label>
                    <?php b3_get_close(); ?>

                    <div class="b3_select-page__selector">
                        <select name="b3_<?php echo $page[ 'id' ]; ?>_id" id="b3_<?php echo $page[ 'id' ]; ?>">
                            <option value=""> <?php esc_html_e( "Select a page", "b3-user-regiser" ); ?></option>
                            <?php foreach( $all_pages as $active_page ) { ?>
                                <?php $selected = ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>
                                <option value="<?php echo $active_page->ID; ?>"<?php echo $selected; ?>> <?php echo $active_page->post_title; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <?php if ( false == $page[ 'page_id' ] ) { ?>
                        <div class="b3_select-page__create">
                            <a href="<?php echo admin_url( '/post-new.php?post_type=page' ); ?>" target="_blank" rel="noopener" title="<?php esc_html_e( 'Add new', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Add new', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ( false != get_option( 'b3_' . $page[ 'id' ] . '_id' ) ) { ?>
                        <div class="b3_select-page__edit">
                            <a href="<?php echo get_edit_post_link( get_option( 'b3_' . $page[ 'id' ] . '_id' ) ); ?>" target="_blank" rel="noopener" title="<?php esc_html_e( 'Edit', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Edit', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                        <div class="b3_select-page__link">
                            <a href="<?php echo get_the_permalink( get_option( 'b3_' . $page[ 'id' ] . '_id' ) ); ?>" target="_blank" rel="noopener" title="<?php esc_html_e( 'Visit', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Visit', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                    <?php } ?>

                </div>
            <?php } ?>

            <p><small><?php esc_html_e( 'Links open in new window/tab.', 'b3-onboarding' ); ?></small></p>

            <?php b3_submit_button(); ?>

        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }
