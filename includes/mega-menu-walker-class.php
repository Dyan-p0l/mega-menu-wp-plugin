<?php


if (!defined('ABSPATH')) exit;

class My_Mega_Menu_Walker extends Walker_Nav_Menu {
    
    function start_lvl ( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "\n$indent<div class=\"mega-menu-wrapper\">\n";
            $output .= "$indent\t<ul class=\"mega-menu\">\n";
        }
        else if ($depth === 1) {
            // $output .= "\n$indent<div class=\"mega-subitem-container\">\n";
            $output .= "$indent<ul class=\"sub-menu-item\">\n";
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
        $indent = str_repeat("\t", $depth);
        $text_content = get_post_meta($item->ID, '_custom_menu_text', true);

        if ($depth === 0) {
            $classes[] = 'mega-parent';  
        }

        if (in_array('menu-item-has-children', $classes) && $depth === 1) {
            $classes[] = 'sub-menu';  
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $unique_id = 'menu-item-' . $item->ID;

        $data_attr = ($depth >= 1) ? ' data-mega-id="' . esc_attr($unique_id) . '"' : '';

        $output .= "<li{$class_names}{$data_attr}>";

        if ($text_content && $depth === 1) {
            $output .= '<h4 class="item-title">'. esc_html($item->title) .'</h4>';
            $output .= '<p class="menu-item-text">' . esc_html($text_content) . '</p>';
        }
        
        if ($image_url && $depth === 1){
            $output .= '<img  src="' . esc_url($image_url). '" alt="" class="menu-item-img" />';
        }

        if ($depth === 1){
            $output .= '<a href="' . esc_attr($item->url) . '">';
        } else {
            $output .= '<a href="' . esc_attr($item->url) . '">';
        }
        
        if ($icon_class ) {
            $output .= '<i class="' . esc_attr($icon_class) . '" style="margin-right:5px;"></i>';
        }

        $output .= esc_html($item->title) . '</a>';

        if ($item->object === 'page' && $depth === 0 && $is_mega) {

            $page = get_post($item->object_id);


            if ($page && has_shortcode($page->post_content, 'skip-mega')) {
                return;
            }
            
            $rendered_content = apply_filters('the_content', $page->post_content);
            
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<div class=\"mega-menu-wrapper\">\n";
            $output .= "$indent\t<div class=\"mega-menu\">\n";
            $output .= "$indent\t\t<div class=\"mega-menu-content\">\n";
            $output .= $rendered_content; // full page layout with structure
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t</div>\n";
            $output .= "$indent</div>\n";

            return;
        }
        
    }
 
    function end_lvl (&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "$indent\t</ul>\n";
            $output .= "$indent\t<div class=\"mega-content-container\">\n";
            $output .= "$indent\t\t<div class=\"mega-content-left\">\n";
            $output .= "$indent\t\t\t<div class=\"mega-content-text\">\n";
            $output .= "$indent\t\t\t</div>\n";
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t\t<div class=\"mega-content-right\">\n";
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t</div>";
            $output .= "$indent</div>\n";
        }
        else if ($depth === 1) {
            $output .= "$indent\t</ul>\n";
            // $output .= "$indent</div>\n";
        }
    }
}