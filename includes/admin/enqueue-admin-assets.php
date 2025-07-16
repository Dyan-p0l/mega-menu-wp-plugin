<?php

if (!defined('ABSPATH')) exit;

add_action('admin_enqueue_scripts', 'custom_mm_admin_scripts');
function custom_mm_admin_scripts ($hook) {
    if ($hook !== 'nav-menus.php' && $hook !== 'toplevel_page_custom-mega-menu-settings') return;
    wp_enqueue_script('custom_mm_admin', plugin_dir_url(dirname(__DIR__)) . 'js/custom_mm_admin.js', array('jquery', 'jquery-ui-dialog'), '1.0', true);
    wp_enqueue_style('my_admin_style', plugin_dir_url(dirname(__DIR__)) . 'css/custom-mm-admin-style.css');
    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_media();

    wp_enqueue_style( 'wp-color-picker' );
}

add_filter('nav_menu_css_class', 'add_custom_mega_bar_class', 10, 2);
function add_custom_mega_bar_class($classes, $item) {
    if (did_action('wp_nav_menu')) {
        global $wp_current_nav_menu_args;
        if (!empty($wp_current_nav_menu_args['theme_location']) && $wp_current_nav_menu_args['theme_location'] === 'aux_nav') {
            if (!in_array('custom-mega-bar', $classes)) {
                $classes[] = 'custom-mega-bar';
            }
        }
    }
    return $classes;
}