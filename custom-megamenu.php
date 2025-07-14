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

add_action('admin_enqueue_scripts', 'custom_mm_admin_scripts');
function custom_mm_admin_scripts ($hook) {
    if ($hook !== 'nav-menus.php') return;
    wp_enqueue_script('custom_mm_admin', plugin_dir_url(__FILE__) . 'js/custom_mm_admin.js', array('jquery', 'jquery-ui-dialog'), '1.0', true);
    wp_enqueue_style('my_admin_style', plugin_dir_url(__FILE__) . 'css/custom-mm-admin-style.css');
    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_media();
}

add_action('admin_footer', 'custom_mm_modal_footer');
function custom_mm_modal_footer () {
    ?>
    <div id="mega-menu-popup" style="display:none;">
        <div class="draggable-wrapper">
            <ul id="sortable-submenus"></ul>
        </div>
        <p style="text-align: right;">
            <button class="button button-primary" id="save-mega-menu">Save</button>
            <button class="button" id="close-mega-menu">Cancel</button>
        </p>
    </div>
    <?php
}

add_action('wp_enqueue_scripts', 'custom_mega_menu_assets');    
function custom_mega_menu_assets() {
    wp_enqueue_style('my-mega-menu-style', plugin_dir_url(__FILE__) . 'css/custom-megamenu-style.css');
}

add_action('wp_nav_menu_item_custom_fields', 'custom_add_mega_menu_checkbox', 10, 4);

function custom_add_mega_menu_checkbox($item_id, $item, $depth, $args) {
    $value = get_post_meta($item_id, '_custom_mega_menu', true);
    ?>
    <p class="field-custom description description-wide">
        <label for="edit-menu-item-mega-menu-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" id="edit-menu-item-mega-menu-<?php echo esc_attr($item_id); ?>"
                   name="menu-item-mega-menu[<?php echo esc_attr($item_id); ?>]" 
                   value="1" <?php checked($value, '1'); ?> />
            Make this menu a Mega Menu
        </label>
    </p>
    <?php
}


add_action('wp_nav_menu_item_custom_fields', 'custom_add_config_button', 10, 3);

function custom_add_config_button ($item_id, $depth, $args) {
    ?>
    <button type="button" class="configure_mega_menu_btn" data-item-id="<?php echo esc_attr($item_id); ?>" style="color: #ffffff; background-color:rgb(5, 70, 94); font-weight: bold; border-color:rgb(1, 13, 44); cursor: pointer">
        CONFIGURE MEGA-MENU
    </button>
    <?php
}


add_action('wp_update_nav_menu_item', 'custom_save_mega_menu_checkbox', 10, 3);
function custom_save_mega_menu_checkbox($menu_id, $menu_item_db_id, $args) {
    if (isset($_POST['menu-item-mega-menu'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_custom_mega_menu', 1);
    } else {
        delete_post_meta($menu_item_db_id, '_custom_mega_menu');
    }
}

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

add_action('wp_nav_menu_item_custom_fields', function($item_id, $item, $depth, $args) {
    $image_url = get_post_meta($item_id, '_custom_menu_image', true);
    $icon_class = get_post_meta($item_id, '_custom_menu_icon', true);
    ?>
    <p class="description description-wide">
        <label for="menu-item-image-<?php echo $item_id; ?>">
            <input type="text" id="menu-item-image-<?php echo $item_id; ?>" name="menu-item-image[<?php echo $item_id; ?>]" value="<?php echo esc_attr($image_url); ?>" class="widefat custom-image-url" />
            <button type="button" class="button select-menu-image" data-input-id="menu-item-image-<?php echo $item_id; ?>">Select Image</button>
        </label>
    </p>
    <p class="description description-wide">
        <label for="menu-item-icon-<?php echo $item_id; ?>">
            Icon Class (e.g. `fa fa-star`):<br>
            <input type="text" id="menu-item-icon-<?php echo $item_id; ?>" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr($icon_class); ?>" class="widefat" />
        </label>
    </p>
    <?php
}, 20, 4);

add_action('wp_update_nav_menu_item', function($menu_id, $menu_item_db_id, $args) {
    if (isset($_POST['menu-item-image'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_custom_menu_image', sanitize_text_field($_POST['menu-item-image'][$menu_item_db_id]));
    }

    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_custom_menu_icon', sanitize_text_field($_POST['menu-item-icon'][$menu_item_db_id]));
    }
}, 20, 3);

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
        $output .= '<a href="' . esc_attr($item->url) . '">';

        if ($image_url) {
            $output .= '<img src="' . esc_url($image_url) . '" alt="" style="width:65px;height:65px;vertical-align:middle;margin-right:8px;" />';
        }

        if ($icon_class) {
            $output .= '<i class="' . esc_attr($icon_class) . '" style="margin-right:5px;"></i>';
        }

        $output .= esc_html($item->title) . '</a>';
    }

}

add_filter('wp_nav_menu_args', 'my_mega_menu_walker');
function my_mega_menu_walker($args) {
    if ($args['theme_location'] == 'aux_nav') {
        $args['walker'] = new My_Mega_Menu_Walker();
    }
    return $args;
}