<?php
/* 
Plugin Name: Custom Mega Menu
Description: A custom plugin that turns the existing menu into a mega menu
Version: 1.0
Author: John Paul Rayco
*/  

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('wp_enqueue_scripts', 'custom_mega_menu_assets');
function custom_mega_menu_assets() {
    wp_enqueue_style('my-mega-menu-style', plugin_dir_url(__FILE__) . 'css/custom-megamenu-style.css');
}

class My_Mega_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl ( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "\n$indent<ul class=\"sub-menu mega-menu\">\n";
        }
        else if ($depth === 1) {
            $output .= "\n$indent<ul class=\"sub-menu-item\">\n";
        }   
        else {
            $output .= "\n$indent<ul class=\"sub-menu sub-menu-level-3\">\n";
        }
    }   

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        if (in_array('menu-item-has-children', $classes) && $depth === 0) {
            $classes[] = 'mega-parent';  
        }
        
        if (in_array('menu-item-has-children', $classes) && $depth === 1) {
            $classes[] = 'sub-menu-item';  
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $output .= "<li class='" . esc_attr($class_names) . "'>";
        $output .= '<a href="' . esc_attr($item->url) . '">' . esc_html($item->title) . '</a>';
    }
}

add_filter('wp_nav_menu_args', 'my_mega_menu_walker');
function my_mega_menu_walker($args) {
    if ($args['theme_location'] == 'aux_nav') {
        $args['walker'] = new My_Mega_Menu_Walker();
    }
    return $args;
}
