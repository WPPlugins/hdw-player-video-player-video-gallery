<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/* UnInstall the Webplayer Tables
******************************************************************/
function hdwplayer_db_uninstall() {
	global $wpdb;
	global $hdwplayer_version;

	$table_name = $wpdb->prefix . "hdwplayer";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	
	$table_name = $wpdb->prefix . "hdwplayer_videos";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	
	$table_name = $wpdb->prefix . "hdwplayer_playlist";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	
	$table_name = $wpdb->prefix . "hdwplayer_gallery";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	
	delete_option( "hdwplayer_version", $hdwplayer_version );
}
    
?>