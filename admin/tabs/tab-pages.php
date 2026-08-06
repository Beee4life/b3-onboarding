<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render pages tab
    function b3_render_pages_tab() {
        $b3_pages = b3_default_admin_pages();

        foreach( $b3_pages as $b3_page ) {
            $b3_page_ids[] = (int) $b3_page[ 'page_id' ];
        }

        // get all pages
        $all_pages = get_posts( [
            'post_type'        => 'page',
            'post_status'      => [ 'publish' ],
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'suppress_filters' => false,
        ] );

        $current_language = apply_filters( 'wpml_current_language', null );
        $default_lang     = apply_filters( 'wpml_default_language', null );

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=pages" method="post">
            <input name="b3_pages_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-pages-nonce' ) ); ?>" />
            <?php echo sprintf( '<h2>%s</h2>', esc_html__( 'Pages', 'b3-onboarding' ) ); ?>
            <?php if ( $current_language !== $default_lang ) { ?>
                <?php // translators: 3 different sentences ?>
                <?php echo sprintf( '<p>%1$s %2$s %3$s</p>', esc_html__( "Here you can see which pages are set for the various 'actions'.", 'b3-onboarding' ), sprintf( esc_html__( "Setting these pages is only possible in the default language (%s).", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '#', esc_html( $default_lang ) ) ), esc_html__( "We store 1 central page ID and get the localized pages from it.", 'b3-onboarding' ) ); ?>
            <?php } else { ?>
                <?php echo sprintf( '<p>%s</p>', esc_html__( "Here you can set which pages are assigned for the various 'actions'.", 'b3-onboarding' ) ); ?>
            <?php } ?>

            <?php foreach( $b3_pages as $b3_page ) { ?>
                <div class="b3_select-page">
                    <label for="b3_<?php echo esc_attr( $b3_page[ 'id' ] ); ?>"><?php echo esc_attr( $b3_page[ 'label' ] ); ?></label>

                    <div class="b3_select-page__selector">
                        <?php
                            if ( $current_language !== $default_lang ) {
                                // show what's set in default language with localized items
                                foreach( $all_pages as $active_page ) {
                                    $translated_id = apply_filters( 'wpml_object_id', $active_page->ID, 'page', true, $default_lang );
                                    if ( ! in_array( $translated_id, $b3_page_ids ) ) {
                                        continue;
                                    }

                                    if ( $translated_id === (int) $b3_page[ 'page_id' ] ) {
                                        echo '=> ' . esc_attr( $active_page->post_title );
                                    }
                                }

                            } else {
                                ?>
                                <select name="b3_<?php echo esc_attr( $b3_page[ 'id' ] ); ?>_id" id="b3_<?php echo esc_attr( $b3_page[ 'id' ] ); ?>">
                                    <option value=""><?php esc_attr_e( "Select a page", "b3-onboarding" ); ?></option>
                                    <?php
                                        foreach( $all_pages as $active_page ) {
                                            echo sprintf( '<option value="%d" %s>%s</option>', esc_attr( $active_page->ID ), selected( $active_page->ID, $b3_page[ 'page_id' ], false ), esc_attr( $active_page->post_title ) );
                                        }
                                    ?>
                                </select>
                        <?php } ?>

                        <select class="hidden" name="b3_<?php echo esc_attr( $b3_page[ 'id' ] ); ?>_id" id="b3_<?php echo esc_attr( $b3_page[ 'id' ] ); ?>">
                            <option value=""><?php esc_attr_e( "Select a page", "b3-onboarding" ); ?></option>
                            <?php
                                if ( class_exists( 'SitePress' ) ) {
                                    foreach( $all_pages as $active_page ) {
                                        if ( $current_language !== $default_lang ) {
                                            $translated_page_id = apply_filters( 'wpml_object_id', $active_page->ID, 'page', false, $current_language );
                                            echo sprintf( '<option value="%d" %s>%s</option>', esc_attr( $active_page->ID ), selected( $active_page->ID, $b3_page[ 'page_id' ], false ), esc_attr( get_the_title( $translated_page_id ) ) );
                                        } else {
                                            echo sprintf( '<option value="%d" %s>%s</option>', esc_attr( $active_page->ID ), selected( $active_page->ID, $b3_page[ 'page_id' ], false ), esc_attr( $active_page->post_title ) );
                                        }
                                    }
                                } else {
                                    foreach( $all_pages as $active_page ) {
                                        echo sprintf( '<option value="%d" %s>%s</option>', esc_attr( $active_page->ID ), selected( $active_page->ID, $b3_page[ 'page_id' ], false ), esc_attr( $active_page->post_title ) );
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <?php if ( false == $b3_page[ 'page_id' ] ) { ?>
                        <div class="b3_select-page__create">
                            <a href="<?php echo esc_url( admin_url( '/post-new.php?post_type=page' ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Add new', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Add new', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                    <?php } ?>

                    <?php
                        $stored_page_id = get_option( 'b3_' . $b3_page[ 'id' ] . '_id' );
                        if ( class_exists( 'SitePress' ) ) {
                            if ( $current_language !== $default_lang ) {
                                $local_page_id = apply_filters( 'wpml_object_id', $stored_page_id, 'page', false, $current_language );
                                if ( $local_page_id ) {
                                    $page_id = $stored_page_id;
                                } else {
                                    $page_id = 0;
                                }
                            } else {
                                $page_id = $stored_page_id;
                            }
                        } else {
                            $page_id = $active_page->ID;
                        }

                        if ( isset( $page_id ) && get_post( (int) $page_id ) instanceof WP_Post ) {
                            ?>
                            <div class="b3_select-page__edit">
                                <a href="<?php echo esc_url( get_edit_post_link(  $page_id ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Edit', 'b3-onboarding' ); ?>">
                                    <?php esc_html_e( 'Edit', 'b3-onboarding' ); ?>
                                </a>
                            </div>
                            <div class="b3_select-page__link">
                                <a href="<?php echo esc_url( get_the_permalink( $page_id ) ); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e( 'Visit', 'b3-onboarding' ); ?>">
                                    <?php esc_html_e( 'Visit', 'b3-onboarding' ); ?>
                                </a>
                            </div>
                    <?php } ?>
                </div>
            <?php } // end foreach b3_pages ?>

            <?php echo sprintf( '<p><small>%s</small></p>', esc_html__( 'Links open in new tab/window.', 'b3-onboarding' ) ); ?>

            <?php
                // Note: don't show button otherwise page ids would be overwritten
                if ( $current_language === $default_lang ) {
                    b3_get_submit_button();
                }
            ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
