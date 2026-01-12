<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $current_language = empty( get_option( 'WPLANG' ) ) ? 'en' : get_option( 'WPLANG' );
    $languages        = b3_get_languages();

    if ( 1 < count( $languages ) ) {
?>

<div class="b3_form-element b3_form-element--language">
    <label class="b3_form-label" for="language"><?php _e( 'Language', 'b3-onboarding' ); ?></label>
    <select id="language" name="language">
        <?php foreach( $languages as $code => $name ) { ?>
            <?php echo sprintf( '<option value="%s"%s>%s</option>', $code, selected( $code, $current_language ), $name ); ?>
        <?php } ?>
    </select>
</div>
<?php } ?>
