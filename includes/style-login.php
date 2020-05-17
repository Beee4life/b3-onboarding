<?php
    require '../../../../wp/wp-load.php';
    // get background color
    $logo = get_option( 'b3_loginpage_logo' );
    $bg_color = get_option( 'b3_loginpage_bg_color' );

    header('Content-type: text/css');
?>

<?php if ( $bg_color ) { ?>
body {
    background: <?php echo $bg_color; ?>;
}
<?php } ?>

<?php if ( $logo ) { ?>
.login h1 a {
    background-image: url(<?php echo $logo; ?>);
    background-image: none, url(<?php echo $logo; ?>);
    background-size:     84px;
    background-position: center top;
    background-repeat:   no-repeat;
    height:              84px;
    margin:              0 auto 25px;
    padding:             0;
    width:               84px;
}
<?php } ?>
