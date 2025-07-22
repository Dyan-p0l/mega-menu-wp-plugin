<?php

if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', 'custom_mega_menu_assets');    
function custom_mega_menu_assets() {
    wp_enqueue_style('my-mega-menu-style', plugin_dir_url(dirname(__DIR__)) . 'css/custom-megamenu-style.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
    wp_enqueue_script(
        'custom-mega-menu-js',
        plugin_dir_url(dirname(__DIR__)) . 'js/custom_mm_frontend.js',
        array('jquery'),
        '1.0.0',
        true
    );

    $bg = get_option('custom_megamenu_bg_color', '#ffffff');
    $text = get_option('custom_megamenu_text_color', '#000000');
    $bg_hover = get_option('custom_megamenu_bg_color:hover', '#000000');
    $text_hover = get_option('custom_megamenu_text_color:hover', '#ffffff');
    $submenu_bg_hover = get_option('custom_submenu_bg_color:hover', '#000000');
    $submenu_text_hover = get_option('custom_submenu_text_color:hover', '#ffffff');
    $custom_css = "

        .mega-content-container {
            background-color: {$bg} !important;
            color: {$text} !important;
        }
        .show-for-large {
            background-color: {$bg} !important;
            color: {$text} !important;
            padding: 0 ! important;
            transform: none !important;
            position: relative !important;
        }
        .show-for-large li a {
            color: {$text} !important;
        }   
        .mega-parent {
            background-color: {$bg} !important;
            color: {$text} !important;
        }
        .page-sub-menu-item {
            background-color: {$bg} !important;
            color: {$text} !important;
        }   
        .mega-menu {
            background-color: {$bg} !important;
            color: {$text} !important;
            z-index: 9999 !important;  
            width: 300px !important; 
        }
        .mega-menu-wrapper {
            background-color: {$bg} !important;
        }
        .mega-menu li {
            background-color: {$bg} !important;

        }
        .mega-menu a {
            color: {$text} !important;
        }
        .mega-menu li:hover>a {
            background-color: {$submenu_bg_hover} !important;
            color: {$submenu_text_hover} !important;
        }
        .mega-menu li.hovered>a {
            background-color: {$submenu_bg_hover} !important;
            color: {$submenu_text_hover} !important;
        }
        .dropdown>li:hover>a {
            background-color: {$bg_hover} !important;
            color: {$text_hover} !important;
        }
        .sub-menu-item li:hover a {
            background-color: {$bg_hover} !important;
            color: {$text_hover} !important;
        }
        
    ";

    wp_add_inline_style('my-mega-menu-style', $custom_css);

}

