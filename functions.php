<?php
define('JU_DEV_MODE', true);

add_action( 'wp_enqueue_scripts', 'sin_enqueue_dependencies' );

function sin_enqueue_dependencies(){
    /* Styles */
    wp_enqueue_style( 'main', get_theme_file_uri() . '/assets/css/main.css', false );
    wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', false );

    /* Scripts */
    wp_enqueue_script( '0d4692f1c3', 'https://kit.fontawesome.com/0d4692f1c3.js', false );
    wp_enqueue_script("jquery");
    wp_enqueue_script( 'popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', false );
    wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', false );
    
}