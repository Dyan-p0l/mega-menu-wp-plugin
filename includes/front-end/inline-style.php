<?php

if (!defined('ABSPATH')) exit;


add_action( 'admin_print_footer_scripts', function () {   // runs after every stylesheet
    ?>
    <style>
        .iris-picker {
            display: block !important;
            position: relative !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .wp-picker-container {
            display: inline-block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        .wp-picker-default {
            display: none !important;
        }

        .wp-color-result, .wp-color-picker {
            display: inline-block !important;
        }
        .wp-picker-input-wrap{ display:block !important; margin-top:4px }
    </style>
    <?php
});