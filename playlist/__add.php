<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/* Inserting (or) Updating the DB Table when edited
******************************************************************/
if(isset($_POST['edited']) && $_POST['edited'] == 'true' && check_admin_referer( 'hdwplayer-nonce')) {
	unset($_POST['edited'], $_POST['save'], $_POST['_wpnonce'], $_POST['_wp_http_referer']);
	$format = array('%s');
	$wpdb->insert($table_name, $_POST, $format);
	echo '<script>window.location="?page=playlist";</script>';
}
	
?>
<div class="wrap">
  <br />
  <?php _e( "HDW Player is the Fastest Growing Online Video Platform for your Websites. For More visit <a href='http://hdwplayer.com'>HDW Player</a>." ); ?>
  <br />
  <br />
  <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" onsubmit="return hdwplayer_validate();">
  	<?php wp_nonce_field('hdwplayer-nonce'); ?>
    <?php  echo "<h3>" . __( 'Playlist Settings' ) . "</h3>"; ?>
    <table cellpadding="0" cellspacing="10">
      <tr>
        <td name="30%"><?php _e("Name" ); ?></td>
        <td><input type="text" id="name" name="name" size="50" /></td>
      </tr>
    </table>
    <br />
    <input type="hidden" name="edited" value="true" />
    <input type="submit" class="button-primary" name="save" value="<?php _e("Save Options" ); ?>" />
    &nbsp; <a href="?page=playlist" class="button-secondary" title="cancel">
    <?php _e("Cancel" ); ?>
    </a>
  </form>
</div>
<script type="text/javascript">
function hdwplayer_validate() {
	if(document.getElementById('name').value == '') {
		alert("Warning! You have not added any Name to the Playlist");
		return false;
	}
	
	return true;
}
</script>