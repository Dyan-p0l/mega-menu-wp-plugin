<?php

if (!defined('ABSPATH')) exit;

add_action('wp_ajax_save_submenu_order', function() {
    if (!current_user_can('edit_theme_options')) wp_send_json_error('No permission');

    $order = $_POST['order'] ?? [];
    foreach ($order as $index => $item_id) {
        wp_update_post([
            'ID' => intval($item_id),
            'menu_order' => $index
        ]);
    }

    wp_send_json_success('Order saved.');
});