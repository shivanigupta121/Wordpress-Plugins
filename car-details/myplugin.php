<?php
/**
* Plugin Name: Shivani Plugin
* Plugin URI: https://www.example.com/
* Description: A plugin to add car details via a form.
* Version: 0.1
* Author: Shivani Gupta
* Author URI: https://www.example.com/
**/
register_activation_hook( __FILE__,'custom_plugin_activate');
register_deactivation_hook( __FILE__,'custom_plugin_deactivate');
function custom_plugin_activate(){
//  echo 'Now Plugin Activate';
//  die();
}
 function custom_plugin_deactivate(){
 // echo 'Now Plugin Deactivate';
 // die();
 }
 require_once plugin_dir_path( __FILE__ ).'admin\class-admin.php';
 require_once plugin_dir_path( __FILE__ ).'public\class-public.php';

 ?>