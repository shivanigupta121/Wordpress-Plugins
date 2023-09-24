<?php
/**
* Plugin Name: WordPress Contributors
* Plugin URI: https://rtcamp.com/
* Description: Create WordPress-Contributors Plugin.
* Version: 1.0.0
* Author: Shivani Gupta
* Author URI: https://rtcamp.com/
* Text Domain:  rtcamp
**/

register_activation_hook(__FILE__, 'rtcamp_wp_contributors_activate');
register_deactivation_hook(__FILE__, 'rtcamp_wp_contributors_deactivate');

function rtcamp_wp_contributors_activate() {
}

function rtcamp_wp_contributors_deactivate() {
}
require_once plugin_dir_path(__FILE__) . 'admin/functions.php';


