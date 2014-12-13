<?php
require "classes/doubles.class.php";

// Single Delete action
if ( isset ( $_GET['action'] ) && $_GET['action'] == 'delete' ) {

	$db = new FPLLeague_Database;
	if ( $db->delete_doubles( $_GET['id'] ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-doubles&message=152' ) );
		exit;

	}

}

// Bulk Delete actions
if ( isset ( $_POST['action'] ) && ( $_POST['action'] == 'delete' || $_POST['action2'] == 'delete' ) ) {

	$db = new FPLLeague_Database;
	foreach( $_POST['id'] as $id_doubles ) {
		if ( $db->delete_doubles( $id_doubles ) ) {
		}
	}

}

?>

<div class="wrap">

	<h2>Doubles <a href="<?php echo admin_url( 'admin.php?page=fplleague-add-doubles' ); ?>" class="add-new-h2">Add New</a></h2>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form method="post" id="team_list_table">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
		<?php $fpldoubleslisttable->display(); ?>
	</form>

</div>
