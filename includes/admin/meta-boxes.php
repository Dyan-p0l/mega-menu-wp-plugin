<?php

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

add_action('wp_nav_menu_item_custom_fields', 
function ($item_id, $depth, $item,$args) {

    // $is_mega = get_post_meta($item_id, '_custom_mega_menu', true);

    if ($depth === 1) return;
    
    ?>
    <p class="description description-wide">
        <label>
            <button type="button" class="configure_mega_menu_btn" data-item-id="<?php echo esc_attr($item_id); ?>" style="color: #ffffff; background-color:rgb(5, 70, 94); font-weight: bold; border-color:rgb(1, 13, 44); cursor: pointer">
                CONFIGURE MEGA-MENU
            </button>
        </label>
    </p>

    <?php
}, 20, 4);

add_action('wp_update_nav_menu_item', 'custom_save_mega_menu_checkbox', 10, 3);
function custom_save_mega_menu_checkbox($menu_id, $menu_item_db_id, $args) {
    if (isset($_POST['menu-item-mega-menu'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_custom_mega_menu', 1);
    } else {
        delete_post_meta($menu_item_db_id, '_custom_mega_menu');
    }
}

add_action('wp_nav_menu_item_custom_fields', function($item_id, $item, $depth, $args) {
    $image_url = get_post_meta($item_id, '_custom_menu_image', true);
    $icon_class = get_post_meta($item_id, '_custom_menu_icon', true);

    if ($depth === 0) return;

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


add_action('wp_nav_menu_item_custom_fields', function($item_id, $item, $depth, $args) {

    if ($depth === 0) return;

    $text_content = get_post_meta($item_id, '_custom_menu_text', true);
    ?>
    <p class="description description-wide">
        <label for="menu-item-text-<?php echo $item_id; ?>">        
            Enter a description text:<br>
            <textarea id="menu-item-text-<?php echo $item_id; ?>"
                name="menu-item-text[<?php echo $item_id; ?>]"
                class="widefat custom-text-content"
                style="min-height: 200px; min-width: 200px;"><?php echo esc_textarea($text_content); ?>
            </textarea>
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
    if (isset($_POST['menu-item-text'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_custom_menu_text', sanitize_textarea_field($_POST['menu-item-text'][$menu_item_db_id]));
    }

}, 20, 3);

