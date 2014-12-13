<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$id_division = $_POST['id_division'];
	$id_schedule = $_POST['id_schedule'];
	$id_fixture = $_POST['id_fixture'];
	$id_team_home = $_POST['id_team_home'];
	$id_team_away = $_POST['id_team_away'];
	$point_home = $_POST['point_home'];
	$point_away = $_POST['point_away'];
	$played = $_POST['played'];

	if ( $db->update_result( $id_division, $id_schedule, $id_fixture, $id_team_home, $id_team_away, $point_home, $point_away, $played ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-results&id_division=' . $_POST['id_division'] . '&id_schedule=' . $_POST['id_schedule'] . '&message=117' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

if ( isset( $_GET['id'] ) ) {

	$fixture = $db->get_fixture_result( $_GET['id'] );

}

?>

<div class="wrap">

	<h2>Edit Result</h2>

	<form id="edit-fixture" method="post" action="<?php echo admin_url('admin.php?page=' . $_REQUEST['page'] . '&noheader=true'); ?>">
		<input type="hidden" name="id_division" value="<?php echo $_GET['id_division']; ?>">
		<input type="hidden" name="id_schedule" value="<?php echo $_GET['id_schedule']; ?>">
		<input type="hidden" name="id_fixture" value="<?php echo $_GET['id']; ?>">
		<input type="hidden" name="id_team_home" value="<?php echo $fixture['id_team_home']; ?>">
		<input type="hidden" name="id_team_away" value="<?php echo $fixture['id_team_away']; ?>">

		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">
						<label for="point_home"><?php echo $fixture['team_home_name']; ?></label>
					</th>

					<td>

						<select name="point_home">

							<option value="">-- Home Points --</option>

							<?php for( $i = 0; $i <= 15; $i++ ) : ?>

							<option value="<?php echo $i; ?>" <?php selected( $fixture['point_home'], $i ); ?>><?php echo $i; ?></option>

							<?php endfor; ?>

						</select>

					</td>

				</tr>

				<tr>

					<th scope="row">
						<label for="point_away"><?php echo $fixture['team_away_name']; ?></label>
					</th>

					<td>

						<select name="point_away">

							<option value="">-- Away Points --</option>

							<?php for( $i = 0; $i <= 15; $i++ ) : ?>

							<option value="<?php echo $i; ?>" <?php selected( $fixture['point_away'], $i ); ?>><?php echo $i; ?></option>

							<?php endfor; ?>

						</select>

					</td>

				</tr>

				<tr>

					<th scope="row">

						<label for="played">Played</label>

					</th>

					<td>

						<?php

							$played = ( $fixture['played'] == NULL ) ? $fixture['scheduled'] : $fixture['played'];

						?>

						<input type="date" name="played" value="<?php echo $played; ?>">

					</td>

				</tr>

			</tbody>

		</table>

		<br class="clear">

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>