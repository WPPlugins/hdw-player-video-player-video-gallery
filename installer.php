<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/* Install the DB Table
******************************************************************/


function hdwplayer_db_install() {
	global $wpdb;
	global $installed_hdwplayer_version;
	global $hdwplayer_version;
	

	if ($installed_hdwplayer_version != $hdwplayer_version) {
    	$table_name = $wpdb->prefix . "hdwplayer";
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
  		`id` int(5) NOT NULL AUTO_INCREMENT,
		`videoid` int(5) NOT NULL,
		`playlistid` int(5) NOT NULL,
		`galleryid` int(5) NOT NULL,
  		`width` int(5) NOT NULL,
  		`height` int(5) NOT NULL,
		`skinmode` varchar(20) NOT NULL,
  		`stretchtype` varchar(20) NOT NULL,
  		`buffertime` int(3) NOT NULL,
  		`volumelevel` int(3) NOT NULL,
  		`autoplay` tinyint(4) NOT NULL,
		`playlistautoplay` tinyint(4) NOT NULL,
  		`playlistopen` tinyint(4) NOT NULL,
  		`playlistrandom` tinyint(4) NOT NULL,
		`controlbar` tinyint(4) NOT NULL,
  		`playpause` tinyint(4) NOT NULL,
  		`progressbar` tinyint(4) NOT NULL,
  		`timer` tinyint(4) NOT NULL,
  		`share` tinyint(4) NOT NULL,
  		`volume` tinyint(4) NOT NULL,
  		`fullscreen` tinyint(4) NOT NULL,
  		`playdock` tinyint(4) NOT NULL,
		`playlist` tinyint(4) NOT NULL,
		`token` varchar(20) NULL,
		UNIQUE KEY (`id`)
		);";
   		$wpdb->query($sql);
		
		$table_name = $wpdb->prefix . "hdwplayer_videos";
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
  		`id` int(5) NOT NULL AUTO_INCREMENT,
		`playlistid` int(5) NOT NULL,
		`title` varchar(255) NOT NULL,
  		`type` varchar(20) NOT NULL,
  		`streamer` varchar(255) NOT NULL,
  		`dvr` tinyint(4) NOT NULL,
  		`video` varchar(255) NOT NULL,
  		`hdvideo` varchar(255) NOT NULL,
  		`preview` varchar(255) NOT NULL,
		`thumb` varchar(255) NOT NULL,
  		`token` varchar(255) NOT NULL,
		`ordering` int(11) NOT NULL DEFAULT '0',
		UNIQUE KEY (`id`)
		);";
   		$wpdb->query($sql);
		
		$table_name = $wpdb->prefix . "hdwplayer_playlist";
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
  		`id` int(5) NOT NULL AUTO_INCREMENT,
  		`name` varchar(255) NOT NULL,
		UNIQUE KEY (`id`)
		);";
   		$wpdb->query($sql);
   		
   		$table_name = $wpdb->prefix . "hdwplayer_gallery";
   		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
  		`id` int(5) NOT NULL AUTO_INCREMENT,
  		`name` varchar(255) NOT NULL,
  		`rows` int(3) NOT NULL,
  		`columns` int(3) NOT NULL,
  		`limit` int(5) NOT NULL,
  		`width` int(5) NOT NULL,
  		`height` int(5) NOT NULL,
  		`space` int(5) NOT NULL,
		UNIQUE KEY (`id`)
		);";
   		$wpdb->query($sql);
		
		add_option( "hdwplayer_version", $hdwplayer_version );
	}
}

/******************************************************************
/* Add data to the installed DB Table
******************************************************************/
function hdwplayer_db_install_data() {
	global $wpdb;
	global $installed_hdwplayer_version;
	global $hdwplayer_version;

	if ($installed_hdwplayer_version != $hdwplayer_version) {
		$table_name = $wpdb->prefix . "hdwplayer";	
		$wpdb->insert($table_name, array( 
		'id'               => 1,
		'videoid'          => 1,
		'playlistid'       => 0,
		'width'            => 640, 
		'height'           => 360, 
		'skinmode'         => 'static',
  		'stretchtype'      => 'fill',
  		'buffertime'       => 3,
  		'volumelevel'      => 50,
  		'autoplay'         => 0,
		'playlistautoplay' => 0,
  		'playlistopen'     => 0,
  		'playlistrandom'   => 0,
		'controlbar'       => 1,
  		'playpause'        => 1,
  		'progressbar'      => 1,
  		'timer'            => 1,
  		'share'            => 1,
  		'volume'           => 1,
  		'fullscreen'       => 1,
  		'playdock'         => 1,
		'playlist'         => 1,
		'token' 		   => 'HDW Player Token'
		));
		
		
		$table_name = $wpdb->prefix . "hdwplayer_videos";	
		$wpdb->insert( $table_name, array( 
		'id'               => 1,
		'title'            => 'Sample Video',
		'type'             => 'video',
		'streamer'         => '',
		'dvr'              => 0,
		'video'            => 'http://hdwplayer.com/videos/300.mp4',
		'hdvideo'          => '',
		'preview'          => '',
		'thumb'            => '',
		'token'            => '',
		'playlistid'       => 1
		));
	}
}

/******************************************************************
/* Check for Update
******************************************************************/
function hdwplayer_update_db_check() {
	 global $hdwplayer_version;
	 global $wpdb;	 
	 $table_name = $wpdb->prefix ."hdwplayer_videos";
	 $sql = "show columns from ".$table_name." like 'ordering'";
	 if( !$wpdb->query($sql)){
	 	$sql = "ALTER TABLE ".$table_name." ADD COLUMN ordering int(11) DEFAULT '0'";
	 	$wpdb->query($sql);
	 	$result = $wpdb->get_results("SELECT * FROM $table_name");
	 	foreach($result as $res){
	 		if($res->playlistid != 0){
	 			$wpdb->update($table_name, array('ordering' => '1' ), array('id' => $res->id));
	 		}
	 	}
	 }
     if (get_site_option('hdwplayer_version') != $hdwplayer_version) {
        update_option( "hdwplayer_version", $hdwplayer_version );
        $table_name = $wpdb->prefix ."hdwplayer";
        $sql = "show columns from ".$table_name." like 'galleryid'";
        if( !$wpdb->query($sql)){
        	$sql = "alter table ".$table_name." add column galleryid int(5)";
        	$wpdb->query($sql);
        }
        
        $table_name = $wpdb->prefix . "hdwplayer_gallery";
        $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
  		`id` int(5) NOT NULL AUTO_INCREMENT,
  		`name` varchar(255) NOT NULL,
  		`rows` int(3) NOT NULL,
  		`columns` int(3) NOT NULL,
  		`limit` int(5) NOT NULL,
  		`width` int(5) NOT NULL,
  		`height` int(5) NOT NULL,
  		`space` int(5) NOT NULL,
		UNIQUE KEY (`id`)
		);";
        $wpdb->query($sql);
     }
}
    
?>