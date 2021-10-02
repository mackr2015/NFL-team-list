<?php 

/*
* Triggered on NFL Teams Plugin Uninstall
*
* @package NFL_Plugin
*/

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clear Database stored data
