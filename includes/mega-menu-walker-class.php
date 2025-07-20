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
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $classes[] = 'mega-parent';  
        }

        if (in_array('menu-item-has-children', $classes) && $depth === 1) {
            $classes[] = 'sub-menu-item';  
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "<li{$class_names}>";
        if ($depth === 1){
            $output .= '<a href="' . esc_attr($item->url) . '">';
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

            // $matches_label = [];
            // preg_match_all('', $page->post_content, $matches_label, PREG_SET_ORDER);

            if (!empty($matches)) {

                if ($depth === 0) {
                    $output .= "\n$indent<div class=\"mega-menu-wrapper\">\n";
                    $output .= "$indent\t<ul class=\"mega-menu\">\n";
                    $output .= "$indent\t\t<li class=\"menu-item\">LINKS FROM PAGE</li>\n";
                }
                else {
                    $output .= '<ul class="page-sub-menu-item">';
                }
                
                foreach ($matches as $match) {
                    $link = esc_url($match[1]);
                    $text = trim($match[2]);

                    if (
                        stripos($text, 'edit') !== false ||
                        stripos($link, 'wp-admin') !== false ||
                        stripos($match[0], 'tablepress-edit-link') !== false ||
                        stripos($text, 'http') !== false
                    ){
                        continue;
                    }

                    $text = esc_html($text);
                    $output .= "<li ><a href='{$link}'>{$text}</a></li>";
                }

                if ($depth === 0) {
                    $output .= "$indent\t\t</li>\n";
                    $output .= "$indent\t</ul>\n";
                    $output .= "$indent</div>\n";
                }else{
                    $output .= '</ul>';
                }

            }
        }
    }

    function end_lvl (&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "$indent\t</ul>\n";
            $output .= "$indent</div>\n";
        }
        else {
            $output .= "$indent</ul>\n";
        }
    }

}