<?php
/**
* Plugin Name: Shivani Plugin
* Plugin URI: https://www.example.com/
* Description: Create Simple Plugin.
* Version: 0.1
* Author: Shivani Gupta
* Author URI: https://www.example.com/
**/
register_activation_hook( __FILE__,'Shivani_Plugin_activate');
register_deactivation_hook( __FILE__,'Shivani_Plugin_deactivate');
function Shivani_Plugin_activate(){
//  echo 'Now Plugin Activate';
//  die();
}
 function Shivani_Plugin_deactivate(){
 // echo 'Now Plugin Deactivate';
 // die();
 }
 require_once plugin_dir_path( __FILE__ ).'admin\class-plugin-name-admin.php';

 ?>