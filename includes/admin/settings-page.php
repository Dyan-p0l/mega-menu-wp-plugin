<?php

if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'custom_mega_menu_settings_page');
function custom_mega_menu_settings_page() {
    add_menu_page(
        'Mega Menu Settings',
        'Mega Menu Settings',
        'manage_options',
        'custom-mega-menu-settings',
        'custom_mega_menu_render_settings_page',
        'dashicons-admin-customizer',
        100
    );
}

add_action('admin_init', 'custom_mega_menu_register_settings');
function custom_mega_menu_register_settings() {
    register_setting('custom_megamenu_options', 'custom_megamenu_bg_color');
    register_setting('custom_megamenu_options', 'custom_megamenu_text_color');
    register_setting('custom_megamenu_options', 'custom_megamenu_bg_color:hover');
    register_setting('custom_megamenu_options', 'custom_megamenu_text_color:hover');
}

function custom_mega_menu_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Mega Menu Style Settings</h1>
        <br/>
        <form method="post" action="options.php">
            <?php settings_fields('custom_megamenu_options'); ?>
            <?php do_settings_sections('custom_megamenu_options'); ?>
            <div class="form-grid">
                <div class="form-row">
                    <label for="bg-color">Background Color</label>
                    <input type="text" id="bg-color" name="custom_megamenu_bg_color"
                        value="<?php echo esc_attr(get_option('custom_megamenu_bg_color', '#ffffff')); ?>"
                        class="color-picker" data-default-color="#ffffff" />
                </div>
                <div class="form-row">
                    <label for="text-color">Text Color</label>
                    <input type="text" id="text-color" name="custom_megamenu_text_color"
                        value="<?php echo esc_attr(get_option('custom_megamenu_text_color', '#000000')); ?>"
                        class="color-picker" data-default-color="#000000" />
                </div>
                <div class="form-row">
                    <label for="bg-color-hover">Background Color: Hovered</label>
                    <input type="text" id="bg-color-hover" name="custom_megamenu_bg_color:hover"
                        value="<?php echo esc_attr(get_option('custom_megamenu_bg_color:hover', '#000000')); ?>"
                        class="color-picker" data-default-color="#000000" />
                </div>
                <div class="form-row">
                    <label for="text-color-hover">Text Color: Hovered</label>
                    <input type="text" id="text-color-hover" name="custom_megamenu_text_color:hover"
                        value="<?php echo esc_attr(get_option('custom_megamenu_text_color:hover', '#000000')); ?>"
                        class="color-picker" data-default-color="#000000" />
                </div>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}