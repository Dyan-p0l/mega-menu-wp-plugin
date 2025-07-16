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


require_once plugin_dir_path(__FILE__) . 'includes/ajax/save-submenu-order.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax/get-submenu-items.php';


require_once plugin_dir_path(__FILE__) . 'includes/admin/enqueue-admin-assets.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/meta-boxes.php';

require_once plugin_dir_path(__FILE__) . 'includes/admin/settings-page.php';

require_once plugin_dir_path(__FILE__) . 'includes/admin/modals.php';
require_once plugin_dir_path(__FILE__) . 'includes/front-end/enqueue-assets.php';
require_once plugin_dir_path(__FILE__) . 'includes/front-end/inline-style.php';


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

        $is_mega = get_post_meta($item->ID, '_custom_mega_menu', true);
        $image_url = get_post_meta($item->ID, '_custom_menu_image', true);
        $icon_class = get_post_meta($item->ID, '_custom_menu_icon', true);

        if ($is_mega && $depth === 0) {
            $classes[] = 'mega-parent';  
        }

        if (in_array('menu-item-has-children', $classes) && $depth === 1) {
            $classes[] = 'sub-menu-item';  
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "<li{$class_names}>";
        if ($depth === 1){
            $output .= '<a href="' . esc_attr($item->url) . '" style="display: flex; flex-direction: column; align-items:center;">';
        } else {
            $output .= '<a href="' . esc_attr($item->url) . '">';
        }
        
        if ($image_url && $depth === 1){
            $output .= '<img  src="' . esc_url($image_url). '" alt="" style="width:200px; height: 180px;" />';
        }
        else if ($image_url) {
            $output .= '<img src="' . esc_url($image_url) . '" alt="" style="width:70px; height:70px; vertical-align:middle; margin-right:8px;" />';
        }

        if ($icon_class) {
            $output .= '<i class="' . esc_attr($icon_class) . '" style="margin-right:5px;"></i>';
        }

        $output .= esc_html($item->title) . '</a>';

        if ($item->object === 'page' && $depth === 0 && $is_mega) {
            $page = get_post($item->object_id);
            
            if ($page && has_shortcode($page->post_content, 'skip-mega')) {
                return; 
            }

            $matches = [];
            preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/', $page->post_content, $matches, PREG_SET_ORDER);

            if (!empty($matches)) {
                $output .= '<ul class="page-sub-menu-item">';
                foreach ($matches as $match) {
                    $link = esc_url($match[1]);
                    $text = esc_html($match[2]);
                    $output .= "<li ><a href='{$link}'>{$text}</a></li>";
                }
                $output .= '</ul>';
            }
        }
    }

}

add_filter('wp_nav_menu_args', 'my_mega_menu_walker');
function my_mega_menu_walker($args) {
    
    if ($args['theme_location'] == 'aux_nav') {
        $args['walker'] = new My_Mega_Menu_Walker();
    }
    return $args;
}