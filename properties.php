<?php
/**
* Plugin Name: Hestabit Property Plugin
* Plugin URI: https://www.yourwebsiteurl.com/
* Description: This is a custom plugin for property features
* Version: 1.0
* Author: Hestabit
* Author URI: http://hestabit.in/
**/


// Include main class file
include( plugin_dir_path( __FILE__ ) .'properties_class.php');
new PropertiesClass();
// Include filters class
include( plugin_dir_path( __FILE__ ) .'filters_class.php');

?>