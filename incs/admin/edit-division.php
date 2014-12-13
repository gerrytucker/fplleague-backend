<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$id_division = $_POST['id_division'];
	$id_season = $_POST['id_season'];
	$division_name = $_POST['division_name'];

	if ( $db->update_division( $id_division, $division_name, $id_season ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-divisions&message=202' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

if ( isset( $_GET['id'] ) ) {

	$division = $db->get_division( $_GET['id'] );
	$division_name = $division['name'];

}

?>

<div class="wrap">

	<h2>Edit Division</h2>

	<form name="post" id="post" action="admin.php?page=<?php echo $_REQUEST['page']; ?>&noheader=true" method="post">
		<input type="hidden" name="id_division" id="id_division" value="<?php echo $_GET['id']; ?>">

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content">

						<div id="titlediv">

							<div id="titlewrap">

								<input type="text" name="division_name" id="title" class="large-text" value="<?php echo $division_name; ?>" placeholder="Enter division name here" required>

								<br class="clear">

							</div>

						</div>

					</div>

				</div>

				<br class="clear">

				<div id="post-body-content">

					<p>A new Division must be allocated to a Season. Pick a Season from the dropdown box below.</p>

					<select name="id_season" id="id_season">

					<?php
					$db = new FPLLeague_Database;
					$seasons = $db->get_every_season();
					foreach ( $seasons as $season ) :
					?>

						<option value="<?php echo $season['id']; ?>"><?php echo $season['name']; ?></option>

					<?php endforeach; ?>

					</select>

				</div>

				<br class="clear">

				<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

			</div>

		</div>

	</form>

</div>
