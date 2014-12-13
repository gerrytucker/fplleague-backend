<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$id_team = $_POST['id_team'];
	$team_name = $_POST['team_name'];
	$id_division = $_POST['id_division'];

	if ( $db->update_team( $id_team, $team_name, $id_division ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-teams' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

if ( isset( $_GET['id'] ) ) {

	$team = $db->get_team( $_GET['id'] );
	$team_name = $team['name'];
	$id_division = $team['id_division'];

}

?>

<div class="wrap">

	<h2>Edit Team</h2>

	<form name="post" id="post" action="admin.php?page=<?php echo $_REQUEST['page']; ?>&noheader=true" method="post">
		<input type="hidden" name="id_team" id="id_team" value="<?php echo $_GET['id']; ?>">

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content">

						<div id="titlediv">

							<div id="titlewrap">

								<input type="text" name="team_name" id="title" class="large-text" value="<?php echo $team_name; ?>" placeholder="Enter team name here">

							</div>

						</div>

					</div>

				</div>

				<div id="post-body-content">

					<p>A Team must be assigned to a Division.  Pick a Division from the dropdown box below.</p>

					<label for="id_division">Division:</label>

					<select name="id_division">

						<?php
						$divs = $db->get_every_division( 0, 9999 );
						foreach ( $divs as $div ) :
						?>
						<option value="<?php echo $div['id']; ?>" <?php selected( $div['id'], $id_division ); ?>><?php echo $div['name'] . ' (' . $div['year'] . ')'; ?></option>
						<?php endforeach; ?>

					</select>

				</div>

				<br class="clear">

				<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save Changes' ); ?>">

			</div>

		</div>

	</form>

</div>
