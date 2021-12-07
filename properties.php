<?php
/**
* Plugin Name: Property Plugin
* Plugin URI: https://www.yourwebsiteurl.com/
* Description: This is a custom plugin for property features
* Version: 1.0
* Author: pankajwb
* Author URI: https://github.com/pankajwb
**/


// Include main class file
include( plugin_dir_path( __FILE__ ) .'properties_class.php');
new PropertiesClass();
// Include filters class
include( plugin_dir_path( __FILE__ ) .'filters_class.php');

?>
