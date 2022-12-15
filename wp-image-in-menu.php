<?php
/*
Plugin Name: Add Image to Menu
Plugin URI: https://rezahedi.dev/projects/wp-image-in-menu
description: Add image from media library to WordPress menu items. My plugin do not import any js, css or other assets to your frontend site exept the menu image tag. You need to add your own css and js to style/use image in menu (for optimal performance matter).
Version: 1.0
Author: Reza Zahedi
Author URI: https://rezahedi.dev
*/
?>
<?php
define( 'RZ_MENU_IMG', __FILE__ );
define( 'RZ_MENU_IMG_DIR', plugin_dir_path( __FILE__ ) );
define( 'RZ_MENU_IMG_URL', plugin_dir_url( RZ_MENU_IMG_DIR ) . basename( dirname( __FILE__ ) ) . '/' );
define( 'RZ_MENU_IMG_BASENAME', plugin_basename( RZ_MENU_IMG ) );
require_once(RZ_MENU_IMG_DIR.'init/rz-functions.php');