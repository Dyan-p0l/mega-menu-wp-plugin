<?php

if (!defined('ABSPATH')) exit;

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

add_action('admin_footer', function(){
    $screen = get_current_screen();
    if ($screen && $screen->base !== 'nav-menus') return;
    ?>
    <div id="save-popup">
        <h3>Order Saved Succesfully!</h3>
    </div>
    <?php
});

add_action('admin_footer', function(){
    $screen = get_current_screen();
    if ($screen && $screen->base !== 'nav-menus') return;
    ?>
    <div id="not-mega-menu">    
        <h3>Can only configure a Mega-menu</h3>
    </div>
    <?php
});