<?php
/**
* Plugin Name: WordPress Slideshow
* Plugin URI: https://rtcamp.com/
* Description: Create WordPress-Slideshow Plugin.
* Version: 1.0.0
* Author: Shivani Gupta
* Author URI: https://rtcamp.com/
* Text Domain:  rtcamp
**/

register_activation_hook(__FILE__, 'rtcamp_plugin_activate');
register_deactivation_hook(__FILE__, 'rtcamp_plugin_deactivate');

function rtcamp_plugin_activate() {
}

function rtcamp_plugin_deactivate() {
}

require_once plugin_dir_path(__FILE__) . 'admin/class-rtcamp-admin.php';
require_once plugin_dir_path(__FILE__) . 'public/class-rtcamp-public.php';

