<?php
if ( isset( $_POST['rebuild'] ) ) {

	$db = new FPLLeague_Database;
	if ( $db->build_tables() ) {

		wp_redirect( admin_url( 'admin.php?page=' . $_REQUEST['page'] . '&message=118' ) );
		exit;

	}

}


if ( isset( $_POST['publish'] ) ) {

	if ( get_option( 'fplleague_point_win' ) !== false ) {

		update_option( 'fplleague_point_win', $_POST['point_win'] );

	}
	else {

		add_option( 'fplleague_point_win', $_POST['point_win'] );

	}

	if ( get_option( 'fplleague_current_season' ) !== false ) {

		update_option( 'fplleague_current_season', $_POST['id_season'] );

	}
	else {

		add_option( 'fplleague_current_season', $_POST['id_season'] );

	}

	wp_redirect( admin_url( "admin.php?page=" . $_REQUEST['page'] . "&message=113" ) );
	exit;

}

$point_win = get_option( 'fplleague_point_win' );

?>

<div class="wrap">

	<h2>Flegg Pool League Settings</h2>
	
	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form name="post" id="post" action="admin.php?page=<?php echo $_REQUEST['page']; ?>&noheader=true" method="post">

		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">

						<label for="point_win">Points For A Win</label>

					</th>

					<td>

						<select name="point_win">

							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>

							<option value="<?php echo $i; ?>" <?php selected( $point_win, $i ); ?>><?php echo $i; ?></option>

							<?php endfor; ?>

						</select>

						<p class="description">How many points are allocated for a Win?</p>


					</td>

				</tr>

				<tr>

					<th scope="row">

						<label for="season_name">Current season</label>

					</th>

					<td>

						<select name="id_season">

<?php

	$current_season = get_option( 'fplleague_current_season' );

	$db = new FPLLeague_Database;
	$seasons = $db->get_every_season();
	foreach ( $seasons as $season ) :

?>

							<option value="<?php echo $season['id']; ?>" <?php selected( $current_season, $season['id'] ); ?>><?php echo $season['name']; ?></option>


	<?php endforeach; ?>

						</select>

						<p class="description">The current season affects all information shown to all visitors of the website, except for registered users who are logged in and using the Administration backend.</p>


					</td>

				</tr>

				<hr>

				<tr>

					<th scope="row">

						<label>Rebuild Tables</label>

					</th>

					<td>

						<input type="submit" name="rebuild" class="button button-large button-secondary" value="<?php _e('Rebuild Now'); ?>">

					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>