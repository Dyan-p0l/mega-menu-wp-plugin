<?php

if (!defined('ABSPATH')) exit;


add_action('wp_ajax_get_submenu_items', function() {
    if (!current_user_can('edit_theme_options')) wp_send_json_error('Unauthorized');

    $locations = get_nav_menu_locations();
    $menu_id = $locations['aux_nav'] ?? 0;

    $parent_id = intval($_POST['parent_id'] ?? 0);
    if (!$parent_id) wp_send_json_error('Missing parent_id');

    $menu_items = wp_get_nav_menu_items($menu_id, array('orderby' => 'menu_order')); 

    $filtered = array_filter($menu_items, function($item) use ($parent_id) {
        return intval($item->menu_item_parent) === $parent_id;
    });

    usort($filtered, function($a, $b) {
        return $a->menu_order - $b->menu_order;
    });

    $result = array_map(function($item) {
        return [
            'ID' => $item->ID,
            'title' => $item->title,
            'image' => get_post_meta($item->ID, '_custom_menu_image', true),
            'icon' => get_post_meta($item->ID, '_custom_menu_icon', true)
        ];
    }, $filtered);

    wp_send_json_success($result);
});