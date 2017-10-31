<?php
/*
 Plugin Name: BEA Menu Anchors
 Version: 1.0.1
 Version Boilerplate: 2.1.2
 Plugin URI: https://beapi.fr
 Description: Display all ACF's flexible rows as anchors into menu items selector.
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: bea-menu-anchors

 ----

 Copyright 2017 BE API Technical team (human@beapi.fr)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'BEA_MENU_ANCHORS_VERSION', '1.0.1' );
define( 'BEA_MENU_ANCHORS_MIN_PHP_VERSION', '5.4' );

// Plugin URL and PATH
define( 'BEA_MENU_ANCHORS_URL', plugin_dir_url( __FILE__ ) );
define( 'BEA_MENU_ANCHORS_DIR', plugin_dir_path( __FILE__ ) );

// Check PHP min version
if ( version_compare( PHP_VERSION, BEA_MENU_ANCHORS_MIN_PHP_VERSION, '<' ) ) {
	require_once( BEA_MENU_ANCHORS_DIR . 'compat.php' );

	// possibly display a notice, trigger error
	add_action( 'admin_init', array( 'BEA\Menu_Anchors\Compatibility', 'admin_init' ) );

	// stop execution of this file
	return;
}

/**
 * Autoload all the things \o/
 */
require_once BEA_MENU_ANCHORS_DIR . 'autoload.php';

add_action( 'plugins_loaded', 'init_bea_menu_anchors_plugin' );
/**
 * Init the plugin
 */
function init_bea_menu_anchors_plugin() {
	// Admin
	if ( is_admin() ) {
		\BEA\Menu_Anchors\Admin\Main::get_instance();
	}
}
