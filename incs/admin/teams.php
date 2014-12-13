<?php
require "classes/teams.class.php";

// Single Delete action
if ( isset ( $_GET['action'] ) && $_GET['action'] == 'delete' ) {

	$db = new FPLLeague_Database;
	if ( $db->delete_team( $_GET['id'] ) ) {

		if ( isset( $_GET['id_division'] ) ) {
			$redirect_url =
				admin_url( 'admin.php?page=fplleague-teams&id_division=' . $_GET['id_division'] . '&message=109' );
		}
		else {
			$redirect_url = admin_url( 'admin.php?page=fplleague-teams&message=109' );
		}
		wp_redirect( $redirect_url );
		exit;

	}

}

// Bulk Delete actions
if ( isset ( $_POST['action'] ) && ( $_POST['action'] == 'delete' || $_POST['action2'] == 'delete' ) ) {

	$db = new FPLLeague_Database;
	foreach( $_POST['team'] as $id_team ) {
		if ( $db->delete_team( $id_team ) ) {
		}
	}

}

?>

<div class="wrap">

	<?php
	$addnew_url = 'admin.php?page=fplleague-add-team';
	if ( isset( $_GET['id_division'] ) )
		$addnew_url .= '&id_division=' . $_GET['id_division'];
	?>

	<h2>Teams <a href="<?php echo $addnew_url; ?>" class="add-new-h2">Add New</a></h2>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form method="post" id="team_list_table">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
		<?php $fplteamlisttable->display(); ?>
	</form>

</div>
