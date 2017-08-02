<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/* Inserting (or) Updating the DB Table when edited
******************************************************************/
if(isset($_POST['edited']) && $_POST['edited'] == 'true' && check_admin_referer( 'hdwplayer-nonce')) {
	unset($_POST['edited'], $_POST['save'], $_POST['_wpnonce'], $_POST['_wp_http_referer']);	
	$format = array('%s','%d','%d','%d','%d','%d','%d');
	$wpdb->insert($table_name, $_POST,$format);
	echo '<script>window.location="?page=gallery";</script>';
}
	
?>
<div class="wrap">
  <br />
  <?php _e( "HDW Player is the Fastest Growing Online Video Platform for your Websites. For More visit <a href='http://hdwplayer.com'>HDW Player</a>." ); ?>
  <br />
  <br />
  <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" onsubmit="return hdwplayer_validate();">
  	<?php wp_nonce_field('hdwplayer-nonce'); ?>
    <?php  echo "<h3>" . __( 'Gallery Settings' ) . "</h3>"; ?>
    <table cellpadding="0" cellspacing="10">
      <tr>
        <td width="30%"><?php _e("Name" ); ?></td>
        <td><input type="text" id="name" name="name" size="50" /></td>
      </tr>
      <tr>
        <td width="30%"><?php _e("No. of Rows" ); ?></td>
        <td><input type="text" id="rows" name="rows" size="50" value="1"/></td>
      </tr>
      <tr>
        <td width="30%"><?php _e("No. of Columns" ); ?></td>
        <td><input type="text" id="columns" name="columns" size="50" value="5"/></td>
      </tr>
      <tr>
        <td width="30%"><?php _e("Display Limit" ); ?></td>
        <td><input type="text" id="limit" name="limit" size="50" value="6"/></td>
      </tr>
      <tr>
        <td width="30%"><?php _e("Width of Thumbnail" ); ?></td>
        <td><input type="text" id="width" name="width" size="50" value="105"/></td>
      </tr>
      <tr>
        <td width="30%"><?php _e("Height of Thumbnail" ); ?></td>
        <td><input type="text" id="height" name="height" size="50" value="60"/></td>
      </tr>
      <tr>
        <td width="30%"><?php _e("Space Between Each Thumb" ); ?></td>
        <td><input type="text" id="space" name="space" size="50" value="2"/></td>
      </tr>
    </table>
    <br />
    <input type="hidden" name="edited" value="true" />
    <input type="submit" class="button-primary" name="save" value="<?php _e("Save Options" ); ?>" />
    &nbsp; <a href="?page=gallery" class="button-secondary" title="cancel">
    <?php _e("Cancel" ); ?>
    </a>
  </form>
</div>
<script type="text/javascript">
function hdwplayer_validate() {
	if(document.getElementById('name').value == '' || document.getElementById('rows').value == '' || document.getElementById('columns').value == '' || document.getElementById('limit').value == '' || document.getElementById('width').value == '' || document.getElementById('height').value == '' || document.getElementById('space').value == '') {
		alert("Warning! You must enter all the fields");
		return false;
	}
	
	
	return true;
}
</script>