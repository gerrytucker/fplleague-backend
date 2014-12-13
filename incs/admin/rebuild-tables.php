<?php
$db = new FPLLeague_Database;

if ( $db->build_tables() ) {

	wp_redirect( admin_url( 'admin.php?page=fplleague-settings' ) );
	exit;

}
?>