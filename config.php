<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/*Bootstrap file for getting the ABSPATH constant to wp-load.php
/*This is requried when a plugin requires access not via the admin screen.
******************************************************************/
require_once (dirname ( __FILE__ ) . '/isMobile.php');

add_filter('query_vars','plugin_add_trigger');
function plugin_add_trigger($vars) {
	$vars[] = 'wid';
	$vars[] = 'view';
    $vars[] = 'vid';
    $vars[] = 'pid';
    $vars[] = 'lic';
    return $vars;
}
 
add_action('template_redirect', 'plugin_trigger_check');
	function plugin_trigger_check() {		
		if(get_query_var('wid') && get_query_var('view') == "config"){
			configXml(get_query_var('wid'));
		}else if(get_query_var('vid') && checkL(get_query_var('lic'))){
			videoPlaylist(get_query_var('vid'));
		}else if(get_query_var('pid') && checkL(get_query_var('lic'))){
			playlist(get_query_var('pid'));
		}		  
	}
		
	function configXML($id){
		global $wpdb;
		$id = encrypt_decrypt('decrypt', $id);
		$table_name = $wpdb->prefix."hdwplayer";
		$config  = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$table_name." WHERE id = %d",trim($id)));
		$siteurl = get_option('siteurl');
		$br      = "\n";
		if(!$config->id){
			die('<b><h1>Restricted access</h1></b>');
		}
		srand ((double) microtime( )*1000000);
		$dyn      = rand( );
		$value['token'] = $dyn;
		$video_id = '';
		if(isset($_GET['vid'])){
			$video_id  = '&amp;id='.intval($_GET['vid']);
		}
		$wpdb->update($table_name, $value, array('id' => $config->id));
		
		header("content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?>'.$br;
		echo '<config>'.$br;
		echo '<skinMode>'.$config->skinmode.'</skinMode>'.$br;
		echo '<autoStart>'.castAsBoolean($config->autoplay).'</autoStart>'.$br;
		echo '<stretch>'.$config->stretchtype.'</stretch>'.$br;
		echo '<buffer>'.$config->buffertime.'</buffer>'.$br;
		echo '<volumeLevel>'.$config->volumelevel.'</volumeLevel>'.$br;		
		if($config->videoid){
			echo '<playListXml>'.$siteurl.'/?vid='.$config->videoid.'</playListXml>'.$br;
		} else {
			echo '<playListXml>'.$siteurl.'/?pid='.$config->playlistid.$video_id.'</playListXml>'.$br;
		}

		echo skinXml($config);
		echo '<playListAutoStart>'.castAsBoolean($config->playlistautoplay).'</playListAutoStart>'.$br;
		echo '<playListOpen>'.castAsBoolean($config->playlistopen).'</playListOpen>'.$br;
		echo '<playListRandom>'.castAsBoolean($config->playlistrandom).'</playListRandom>'.$br;
		echo '<token>'.$dyn.'</token>'.$br;
		echo '</config>'.$br;
		exit();		
	}
	
	function videoPlaylist($id){
		global $wpdb;		
		$siteurl = get_option('siteurl');
		$br      = "\n";
		$config  = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."hdwplayer_videos WHERE id = %d",intval($id)));
		$item = $config[0];
		
		header("content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?>'.$br;
		echo '<playlist>'.$br;		
		echo '<media>'.$br;
		echo '<id>'.$item->id.'</id>'.$br;
		echo '<type>'.$item->type.'</type>'.$br;
		echo '<video>'.$item->video.'</video>'.$br;
		if($item->hdvideo) {
			echo '<hd>'.$item->hdvideo.'</hd>'.$br;
		}
		echo '<streamer>'.$item->streamer.'</streamer>'.$br;
		if($item->dvr) {
			echo '<dvr>'.$item->dvr.'</dvr>'.$br;
		}
		echo '<thumb>'.$item->thumb.'</thumb>'.$br;
		if($item->token) {
			echo '<token>'.$item->token.'</token>'.$br;
		}
		echo '<preview>'.$item->preview.'</preview>'.$br;
		echo '<title>'.$item->title.'</title>'.$br;
		echo '</media>'.$br.$br;			
		echo '</playlist>'.$br;
		exit();
	}
	
	function playlist($id){
		global $wpdb;
		$val = 0;
		$siteurl = get_option('siteurl');
		$br      = "\n";
		$vid = (isset($_GET['id']) && $_GET['id']       != '') ? $_GET['id'] : '';
		
		$query = "SELECT * FROM ".$wpdb->prefix."hdwplayer_videos WHERE";
		
		if($vid == ''){
			$query .= " playlistid = %d";
			$val = intval($id);
		}else{
			$query .= " id = %d";
			$val = intval($vid);
		}
		
		$query .= ' ORDER BY ordering';
		
		$config = json_decode(json_encode($wpdb->get_results($wpdb->prepare($query,$val))),true);
		
		if($vid != ''){
			$query = "SELECT * FROM ".$wpdb->prefix."hdwplayer_videos WHERE";
			$query .= " id != %d";
			$query .= " AND playlistid = %d";
			$query .= " ORDER BY ordering";
			$config = array_merge($config,json_decode(json_encode($wpdb->get_results($wpdb->prepare($query,intval($vid),intval($id)))),true));			
		}
		
		if(!$config[0]['id']){
			die('<b><h1>Restricted access</h1></b>');
		}
		header("content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?>'.$br;
		echo '<playlist>'.$br;
		foreach ($config as $item){
			$br;
			echo '<media>'.$br;
			echo '<id>'.$item['id'].'</id>'.$br;
			echo '<type>'.$item['type'].'</type>'.$br;
			echo '<video>'.$item['video'].'</video>'.$br;
			if($item['hdvideo']) {
				echo '<hd>'.$item['hdvideo'].'</hd>'.$br;
			}
			echo '<streamer>'.$item['streamer'].'</streamer>'.$br;
			if($item['dvr']) {
				echo '<dvr>'.$item['dvr'].'</dvr>'.$br;
			}
			echo '<thumb>'.$item['thumb'].'</thumb>'.$br;
			if($item['token']) {
				echo '<token>'.$item['token'].'</token>'.$br;
			}
			echo '<preview>'.$item['preview'].'</preview>'.$br;
			echo '<title>'.$item['title'].'</title>'.$br;
			echo '</media>'.$br.$br;
		}		
		echo '</playlist>'.$br;
		exit();
	}
	
	function skinXml($config){
		$br      = "\n";	
		$node  = '<controlBar>'.castAsBoolean($config->controlbar).'</controlBar>'.$br;
		$node .= '<playPauseDock>'.castAsBoolean($config->playpause).'</playPauseDock>'.$br;
		$node .= '<progressBar>'.castAsBoolean($config->progressbar).'</progressBar>'.$br;
		$node .= '<timerDock>'.castAsBoolean($config->timer).'</timerDock>'.$br;
		$node .= '<shareDock>'.castAsBoolean($config->share).'</shareDock>'.$br;
		$node .= '<volumeDock>'.castAsBoolean($config->volume).'</volumeDock>'.$br;
		$node .= '<fullScreenDock>'.castAsBoolean($config->fullscreen).'</fullScreenDock>'.$br;
		$node .= '<playDock>'.castAsBoolean($config->playdock).'</playDock>'.$br;
		$node .= '<playList>'.castAsBoolean($config->playlist).'</playList>'.$br;
		return $node;
	}
	
	function castAsBoolean($val){
		if($val == 1) {
			return 'true';
		} else {
			return 'false';
		}
	}
	
	function encrypt_decrypt($action, $string) {
	   $output = false;  
	
	   if( $action == 'encrypt' ) {
	       $output = (double)$string*525325.24;
	       $output = base64_encode($output);
	   }
	   else if( $action == 'decrypt' ){
	       $output = base64_decode(substr($string,0,-3));
	       $output = (double)$output/525325.24;
	   }
	   return $output;
	}
	
	function  checkL($lic){
		global $wpdb;
		$token = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hdwplayer");
		$license = array();
		foreach($token as $tok){
			$license[] = trim($tok->token);	
		}		
		if(in_array(trim($lic),$license)){
			return true;
		}
		return false;		
	}
	
function hdwplayer_gallery_ajax(){
	if(!isset($_POST['action']))
	{
		return;
	}
	$action = $_POST['action'];
	if('flashvars' == $action)
	{
		global $wpdb;
		$player = $wpdb->get_row ( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "hdwplayer WHERE id = %d",intval($_POST ['id'])));
		$siteurl = get_option ( 'siteurl' );		
		$results = $wpdb->get_row ( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "hdwplayer_videos WHERE id = %d",intval($_POST ['vid'])));
		$detect = new Mobile_Detect();
		if ($detect->isMobile()) {
			switch ($results->type) {
				case 'youtube' :
					$url_string = parse_url ( $results->video, PHP_URL_QUERY );
					parse_str ( $url_string, $args );
					$html5 = '<iframe title="YouTube video player" width="100%" height="100%" src="http://www.youtube.com/embed/' . $args ['v'] . '" frameborder="0" allowfullscreen></iframe>';
					break;
				case 'dailymotion' :
					$html5 = '<iframe frameborder="0" width="100%" height="100%" src="' . $results->video . '"></iframe>';
					break;
				case 'rtmp' :
					$url_string = str_replace ( 'rtmp', 'http', $results->streamer ) . '/' . $results->video . '/playlist.m3u8';
					$html5 = '<video poster="' . $results->preview . '"  onclick="this.play();" width="100%" height="100%" controls>';
					$html5 .= '<source src="' . $url_string . '" />';
					$html5 .= '</video>';
					break;
				default :
					$html5 = '<video poster="' . $results->preview . '"  onclick="this.play();" width="100%" height="100%" controls>';
					$html5 .= '<source src="' . $results->video . '" />';
					$html5 .= '</video>';
			}
		}else{
			$flashvars = 'baseW=' . $siteurl . '&id=' . encrypt_decrypt ( 'encrypt', $player->id ).'&vid='.intval($_POST ['vid']);
			$pluginurl = plugins_url();
			$src = $pluginurl .'/'. basename ( dirname ( __FILE__ ) ) . '/player.swf';
			$html5 .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $player->width . '" height="' . $player->height . '">';
			$html5 .= '<param name="movie" value="' . $src . '" />';
			$html5 .= '<param name="allowfullscreen" value="true" />';
			$html5 .= '<param name="allowscriptaccess" value="always" />';
			$html5 .= '<param name="flashvars" value="' . $flashvars . '" />';
			$html5 .= '<object type="application/x-shockwave-flash" data="' . $src . '" width="' . $player->width . '" height="' . $player->height . '">';
			$html5 .= '<param name="movie" value="' . $src . '" />';
			$html5 .= '<param name="allowfullscreen" value="true" />';
			$html5 .= '<param name="allowscriptaccess" value="always" />';
			$html5 .= '<param name="flashvars" value="' . $flashvars . '" />';
			$html5 .= '</object>';
			$html5 .= '</object>';
		}

		$response = array(
			'html5' 	=>$html5
		);
		die(json_encode($response));
	}
	
	if('email' == $action && checkL($_POST["lic"]))
	{
		$to       = $_POST["to"];
		$from     = $_POST["from"];
		$url      = $_POST["url"];
		$subject  = "You have received a video!";
		
		$headers  = "From: "."<" . $_POST["from"] .">\r\n";
		$headers .= "Reply-To: " . $_POST["from"] . "\r\n";
		$headers .= "Return-path: " . $_POST["from"];
		
		$message  = $_POST["message"] . "\n\n";
		$message .= "Video URL: " . $url;
		
		if(mail($to, $subject, $message, $headers)) {
			echo "sent";
			exit;
		} else {
			echo "error";
			exit;
		}
	}
}

?>