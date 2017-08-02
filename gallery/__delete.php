<?php
defined('ABSPATH') or die('Restricted access');
/******************************************************************
/* Deleting the Table Row
******************************************************************/
if($_GET['page'] == 'gallery' && $_GET['opt'] == 'delete') {
	$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id=%d",$_GET['id']));
	echo '<script>window.location="?page=gallery";</script>';
}

?>