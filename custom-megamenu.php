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

//ASYNCH JS FOR SAVING AND GETTING SUBMENU ITEMS WITHOUT RELOADING THE PAGE
require_once plugin_dir_path(__FILE__) . 'includes/ajax/save-submenu-order.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax/get-submenu-items.php';


//FOR ADMIN LAYOUTS AND FUNCTIONS
require_once plugin_dir_path(__FILE__) . 'includes/admin/enqueue-admin-assets.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/modals.php'; //MODALS FOR POP-UPS UI IN THE ADMIN

//FOR FRONT-END LAYOUTS AND FUNCTIONS
require_once plugin_dir_path(__FILE__) . 'includes/front-end/enqueue-assets.php';
require_once plugin_dir_path(__FILE__) . 'includes/front-end/inline-style.php';


require_once plugin_dir_path(__FILE__) . 'includes/mega-menu-walker-class.php'; 

add_filter('wp_nav_menu_args', 'my_mega_menu_walker');
function my_mega_menu_walker($args) {
    
    if ($args['theme_location'] == 'aux_nav') {
        $args['walker'] = new My_Mega_Menu_Walker();
    }
    return $args;
}