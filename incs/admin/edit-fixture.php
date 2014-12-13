<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$id_home_team = $_POST['id_home_team'];
	$id_away_team = $_POST['id_away_team'];
	$scheduled = $_POST['scheduled'];

	if ( $db->update_fixture( $_GET['id_fixture'], $id_home_team, $id_away_team, $scheduled ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-fixtures&id_schedule=' . $_GET['id_schedule'] . '&message=110' ) );
		exit;

	} else {

	}

}

if ( $fixture = $db->get_fixture( $_GET['id'] ) ) {

	$id_home_team = $fixture['id_home_team'];
	$id_away_team = $fixture['id_away_team'];
	$scheduled = $fixture['scheduled'];

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

?>

<div class="wrap">

	<?php
	if ( $id_division = $db->get_division_from_schedule( $_GET['id_schedule'] ) ) {

		$teams = $db->get_every_team( $id_division, 0, 9999, 'ASC' );

	}
	?>

	<h2>Add New Fixture</h2>

	<?php $form_url = admin_url( 'admin.php?page=' . $_REQUEST['page'] . '&id_schedule=' . $_GET['id_schedule'] . '&noheader=true' ); ?>

	<form id="add-new-fixture" method="post" action="<?php echo $form_url; ?>">
		<input type="hidden" name="id_fixture" id="id_fixture" value="<?php echo $_GET['id']; ?>">

		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">
						<label for="id_home_team">Home Team</label>
					</th>

					<td>

						<select name="id_home_team">

							<option value="">Select Home Team</option>

							<?php foreach( $teams as $team ) : ?>

							<option value="<?php echo $team['id']; ?>" <?php selected( $team['id'], $id_home_team ); ?>><?php echo $team['name']; ?></option>

							<?php endforeach; ?>

						</select>

					</td>

				</tr>

				<tr>

					<th scope="row">
						<label for="id_away_team">Away Team</label>
					</th>

					<td>

						<select name="id_away_team">

							<option value="">Select Away Team</option>

							<?php foreach( $teams as $team ) : ?>

							<option value="<?php echo $team['id']; ?>" <?php selected( $team['id'], $id_away_team ); ?>><?php echo $team['name']; ?></option>

							<?php endforeach; ?>

						</select>

					</td>
					
				</tr>

				<tr>

					<th scope="row">
						<label for="scheduled">Date To Be Played</label>
					</th>

					<td>
						<input type="date" name="scheduled" id="scheduled" value="<?php echo $scheduled; ?>" required>
					</td>

				</tr>

			</tbody>

		</table>

		<br class="clear">
		
		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>
