<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Render pages tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_pages_tab() {
        $b3_pages = b3_default_admin_pages();

        // get all pages
        $all_pages = get_posts( [
            'post_type'      => 'page',
            'post_status'    => [ 'publish' ],
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=pages" method="post">
            <input name="b3_pages_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-pages-nonce' ); ?>" />
            <?php echo sprintf( '<h2>%s</h2>', esc_html__( 'Pages', 'b3-onboarding' ) ); ?>
            <?php echo sprintf( '<p>%s</p>', esc_html__( "Here you can set which pages are assigned for the various 'actions'.", 'b3-onboarding' ) ); ?>

            <?php foreach( $b3_pages as $page ) { ?>
                <div class="b3_select-page">
                    <label for="b3_<?php echo esc_attr( $page[ 'id' ] ); ?>"><?php echo esc_attr( $page[ 'label' ] ); ?></label>

                    <div class="b3_select-page__selector">
                        <select name="b3_<?php echo esc_attr( $page[ 'id' ] ); ?>_id" id="b3_<?php echo esc_attr( $page[ 'id' ] ); ?>">
                            <option value=""><?php esc_attr_e( "Select a page", "b3-user-regiser" ); ?></option>
                            <?php if ( class_exists( 'SitePress' ) ) { ?>
                                <?php $default_lang = apply_filters( 'wpml_default_language', null ); ?>
                                <?php foreach( $all_pages as $active_page ) { ?>
                                    <?php if ( function_exists( 'wpml_get_language_information' ) ) { ?>
                                        <?php $post_language_information = wpml_get_language_information( '', $active_page->ID ); ?>
                                        <?php if ( $post_language_information[ 'language_code' ] === $default_lang ) { ?>
                                            <option value="<?php echo $active_page->ID; ?>"<?php echo selected($active_page->ID, $page[ 'page_id' ]); ?>> <?php echo $active_page->post_title; ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="<?php echo $active_page->ID; ?>"<?php echo selected($active_page->ID, $page[ 'page_id' ]); ?>> <?php echo $active_page->post_title; ?></option>
                                    <?php } ?>

                                <?php } ?>
                            <?php } else { ?>
                                <?php foreach( $all_pages as $active_page ) { ?>
                                    <option value="<?php echo $active_page->ID; ?>"<?php echo selected($active_page->ID, $page[ 'page_id' ]); ?>> <?php echo $active_page->post_title; ?></option>
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
                    <?php $page_id = get_option( 'b3_' . $page[ 'id' ] . '_id' ); ?>
                    <?php if ( false != $page_id ) { ?>
                        <?php if ( get_post( $page_id ) instanceof WP_Post ) { ?>
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
                    <?php } ?>

                </div>
            <?php } ?>

            <?php echo sprintf( '<p><small>%s</small></p>', esc_html__( 'Links open in new tab.', 'b3-onboarding' ) ); ?>

            <?php b3_get_submit_button(); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
