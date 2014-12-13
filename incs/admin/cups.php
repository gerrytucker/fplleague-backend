<?php
require "classes/cups.class.php";

if ( ! class_exists( 'WP_List_Table' ) ) {

	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

}

// Single Delete action
if ( isset ( $_GET['action'] ) && $_GET['action'] == 'delete' ) {

	$db = new FPLLeague_Database;
	if ( $db->delete_cup( $_GET['id'] ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-cups&message=132' ) );
		exit;
		
	}

}

// Bulk Delete actions
if ( isset ( $_POST['action'] ) && ( $_POST['action'] == 'delete' || $_POST['action2'] == 'delete' ) ) {

	$db = new FPLLeague_Database;
	foreach( $_POST['cup'] as $id_cup ) {
		if ( $db->delete_cup( $id_cup ) ) {
		}
	}

}

?>

<div class="wrap">

	<h2>Cup Competitions <a href="<?php echo admin_url('admin.php?page=fplleague-add-cup'); ?>" class="add-new-h2">Add New</a></h2>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
		<?php $fplcupslisttable->display(); ?>
	</form>

</div>
