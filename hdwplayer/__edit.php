<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/* Inserting (or) Updating the DB Table when edited
******************************************************************/
if(isset($_POST['edited']) && $_POST['edited'] == 'true' && check_admin_referer( 'hdwplayer-nonce')) {
	unset($_POST['group'], $_POST['edited'], $_POST['video'], $_POST['save'], $_POST['_wpnonce'], $_POST['_wp_http_referer']);
	if(!$_POST['playlistid']){
		$_POST['galleryid'] = 0;
	}
	$format = array('%d','%d','%s','%s','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d');
	$wpdb->update($table_name, $_POST, array('id' => intval($_GET['id'])),$format);
	echo '<script>window.location="?page=hdwplayer";</script>';
}

/******************************************************************
/* Getting Input from the DB Table
******************************************************************/
$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",intval($_GET['id'])));
$vname = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."hdwplayer_videos WHERE id=%d",$data->videoid));
	
?>
<style>
.autocomplete-suggestions { border: 1px solid #999; width: 429px !important; background: #fff; cursor: default; overflow: auto; }
.autocomplete-suggestion { padding: 5px 5px; font-size: 13px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #f0f0f0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399ff; }
</style>
<div class="wrap">
  <br />
  <?php _e( "HDW Player is the Fastest Growing Online Video Platform for your Websites. For More visit <a href='http://hdwplayer.com'>HDW Player</a>." ); ?>
  <br />
  <br />
  <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" onsubmit="return hdwplayer_validate();">
  	<?php wp_nonce_field('hdwplayer-nonce'); ?>
    <?php  echo "<h3>" . __( 'Player Settings' ) . "</h3>"; ?>
    <table cellpadding="0" cellspacing="10">
      <tr>
        <td width="30%"><?php _e("Player Size" ); ?></td>
        <td><?php _e("Width" ); ?>
          &nbsp;&nbsp;
          <input type="text" id="width" name="width" value="<?php echo $data->width; ?>" size="5" />
          &nbsp;&nbsp;
          <?php _e("Height" ); ?>
          &nbsp;&nbsp;
          <input type="text" id="height" name="height" value="<?php echo $data->height; ?>" size="5"/></td>
      </tr>
      <tr>
        <td><?php _e("Skin Mode" ); ?></td>
        <td><select id="skinmode" name="skinmode">
            <option value="float" id="float">Float</option>
            <option value="static" id="static">Static</option>
          </select>
          <?php echo '<script>document.getElementById("'.$data->skinmode.'").selected="selected"</script>'; ?> </td>
      </tr>
      <tr>
        <td><?php _e("Stretch Type" ); ?></td>
        <td><select id="stretchtype" name="stretchtype">
            <option value="fill" id="fill">Fill</option>
            <option value="uniform" id="uniform">Uniform</option>
            <option value="none" id="none">Original</option>
            <option value="exactfit" id="exactfit">Exact Fit</option>
          </select>
          <?php echo '<script>document.getElementById("'.$data->stretchtype.'").selected="selected"</script>'; ?> </td>
      </tr>
      <tr>
        <td><?php _e("Buffer Time" ); ?></td>
        <td><input type="text" id="buffertime" name="buffertime" value="<?php echo $data->buffertime; ?>" size="50"></td>
      </tr>
      <tr>
        <td><?php _e("Volume Level" ); ?></td>
        <td><input type="text" id="volumelevel" name="volumelevel" value="<?php echo $data->volumelevel; ?>" size="50"></td>
      </tr>
      <tr>
        <td><?php _e("AutoPlay" ); ?></td>
        <td><input type="hidden" name="autoplay" value="0"><input type="checkbox" id="autoplay" name="autoplay" value="1" <?php if($data->autoplay==1){echo 'checked="checked" ';}?>></td>
      </tr>
    </table>
    <?php  echo "<h3>" . __( 'Skin Settings' ) . "</h3>"; ?>
    <table cellpadding="0" cellspacing="15">
      <tr>
        <td><input type="hidden" name="controlbar" value="0"><input type="checkbox" id="controlbar" name="controlbar" value="1" <?php if($data->controlbar==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Control Bar" ); ?></td>
        <td><input type="hidden" name="playpause" value="0"><input type="checkbox" id="playpause" name="playpause" value="1" <?php if($data->playpause==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("PlayPause Dock" ); ?></td>
        <td><input type="hidden" name="progressbar" value="0"><input type="checkbox" id="progressbar" name="progressbar" value="1" <?php if($data->progressbar==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Progress Bar" ); ?></td>
        <td><input type="hidden" name="timer" value="0"><input type="checkbox" id="timer" name="timer" value="1" <?php if($data->timer==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Timer Dock" ); ?></td>
      </tr>
      <tr>
        <td><input type="hidden" name="share" value="0"><input type="checkbox" id="share" name="share" value="1" <?php if($data->share==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Share Dock" ); ?></td>
        <td><input type="hidden" name="volume" value="0"><input type="checkbox" id="volume" name="volume" value="1" <?php if($data->volume==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Volume Dock" ); ?></td>
        <td><input type="hidden" name="fullscreen" value="0"><input type="checkbox" id="fullscreen" name="fullscreen" value="1" <?php if($data->fullscreen==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Fullscreen Dock" ); ?></td>
        <td><input type="hidden" name="playdock" value="0"><input type="checkbox" id="playdock" name="playdock" value="1" <?php if($data->playdock==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("Play Dock" ); ?></td>
      </tr>
      <tr>
        <td><input type="hidden" name="playlist" value="0"><input type="checkbox" id="playlist" name="playlist" value="1" <?php if($data->playlist==1){echo 'checked="checked" ';}?>></td>
        <td><?php _e("PlayList" ); ?></td>
      </tr>
    </table>
    <?php  echo "<h3>" . __( 'Video Settings' ) . "</h3>"; ?>
    <table cellpadding="0" cellspacing="15">
      <tr>      	
        <td><input type="radio" name="group" onchange="changeType('videoid');" <?php if($data->videoid){echo 'checked="checked" ';}?>>
        <label>&nbsp;&nbsp;<?php _e("Single Video" ); ?></label></td>
        <td><input type="radio" name="group" onchange="changeType('playlistid');" <?php if($data->playlistid){echo 'checked="checked" ';}?>>
        <label>&nbsp;&nbsp;<?php _e("Playlist" ); ?></label></td>
	  </tr>
      <tr id="_videoid">
      	<td><?php _e("Video Name" ); ?></td>
        <td><input type="hidden" id="videoid" name="videoid" value="<?php echo $data->videoid; ?>"><input type="text" id="video" name="video" value="<?php echo $vname->title; ?>" size="50"></td>
      </tr>
      <tr id="_playlistid">
        <td class="key"><?php _e("Choose your Playlist" ); ?></td>
        <td><select id="playlistid" name="playlistid" >
            <option value="0" id="0" >None</option>
            <?php
            $k=count( $playlist);
            for ($i=0; $i < $k; $i++)
            {
               $row = $playlist[$i];
            ?>
            <option value="<?php echo $row->id; ?>" id="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
            <?php } ?>
           </select><?php echo '<script>document.getElementById("'.$data->playlistid.'").selected="selected"</script>'; ?>
        </td>
      </tr>
      <tr id="_playlistautoplay">
      	<td><?php _e("Playlist Autoplay" ); ?></td>
        <td><input type="hidden" name="playlistautoplay" value="0"><input type="checkbox" id="playlistautoplay" name="playlistautoplay" value="1" <?php if($data->playlistautoplay==1){echo 'checked="checked" ';}?>></td>
      </tr>
      <tr id="_playlistopen">
      	<td><?php _e("Playlist Open" ); ?></td>
        <td><input type="hidden" name="playlistopen" value="0"><input type="checkbox" id="playlistopen" name="playlistopen" value="1" <?php if($data->playlistopen==1){echo 'checked="checked" ';}?>></td>
      </tr>
      <tr id="_playlistrandom">
      	<td><?php _e("Playlist Random" ); ?></td>
        <td><input type="hidden" name="playlistrandom" value="0"><input type="checkbox" id="playlistrandom" name="playlistrandom" value="1" <?php if($data->playlistrandom==1){echo 'checked="checked" ';}?>></td>
      </tr>
      <tr id="_galleryid">
      	<td><?php _e("Choose Your Gallery" ); ?></td>
        <td><select id="galleryid" name="galleryid">
        <option value="0" id="g0">None</option>
            <?php
            $k=count( $gallery);
            for ($i=0; $i < $k; $i++)
            {
               $row = $gallery[$i];
            ?>
            <option value="<?php echo $row->id; ?>" id="g<?php echo $row->id; ?>"><?php echo $row->name; ?></option><?php echo '<script>document.getElementById("g'.$data->galleryid.'").selected="selected"</script>'; ?>
            <?php } ?>
        </select>
        </td>
      </tr>
     </table>
    <br />
    <input type="hidden" name="edited" value="true" />
    <input type="submit" class="button-primary" name="save" value="<?php _e("Save Options" ); ?>" />
    &nbsp; <a href="?page=hdwplayer" class="button-secondary" title="cancel">
    <?php _e("Cancel" ); ?>
    </a>
  </form>
</div>
<?php if($data->videoid) { $type = "videoid"; } else { $type = "playlistid"; } ?>
<script type="text/javascript" src="<?php echo $jsac; ?>"></script>
<script type="text/javascript">
changeType(<?php echo "'".$type."'"; ?>);

function changeType(type) {
	document.getElementById('_videoid').style.display="none";
	document.getElementById('_playlistid').style.display="none";
	document.getElementById('_playlistautoplay').style.display="none";
	document.getElementById('_playlistopen').style.display="none";
	document.getElementById('_playlistrandom').style.display="none";
	document.getElementById('_galleryid').style.display="none";
	switch(type) {
		case 'playlistid':
			document.getElementById('_playlistid').style.display="";
			document.getElementById('_playlistautoplay').style.display="";
			document.getElementById('_playlistopen').style.display="";
			document.getElementById('_playlistrandom').style.display="";
			document.getElementById('_galleryid').style.display="";
			break;
		default:
			document.getElementById('_videoid').style.display="";
	}
}

function hdwplayer_validate() {
	if(document.getElementById('_videoid').style.display == 'none') {
		document.getElementById('videoid').value = 0;
	} else {
		document.getElementById('playlistid').value = 0;
	}
	
	if(document.getElementById('width').value < 180 || document.getElementById('height').value < 180) {
		alert("Warning! The Player size should be atleast 180 * 180");
		return false;
	}
	
	if(document.getElementById('videoid').value == '' && document.getElementById('playlistid').value == '') {
		alert("Warning! You have not added any Video (or) Playlist to the Player.");
		return false;
	}
	
	return true;
}
</script>
<script>
jQuery(function(){
	  var video = [
		<?php foreach ($video as $data) { ?>
          	{ value: '<?php echo $data->title; ?>', data: '<?php echo $data->id; ?>' },
        <?php } ?>
	  ];	  
	  
	  jQuery('#video').autocomplete({
	    lookup: video,
	    onSelect: function (suggestion) {
	      document.getElementById("videoid").value = suggestion.data;
	    }
	  });  

	});
</script>