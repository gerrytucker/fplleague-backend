<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$id_season = $_POST['id_season'];
	$season_name = $_POST['season_name'];
	$year = $_POST['year'];

	if ( false === $db->update_season( $id_season, $season_name, $year ) ) {

	}
	else {

		FPLLeague::$messages[] = array(
			'type'		=> 'updated',
			'message'	=> 'Season updated.'
		);
		wp_redirect( admin_url( 'admin.php?page=fplleague-seasons' ) );
		exit;

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

if ( isset( $_GET['id'] ) ) {

	$season = $db->get_season( $_GET['id'] );
	$season_name = $season['name'];
	$year = $season['year'];

}

?>

<div class="wrap">

	<h2>Edit Season</h2>

	<form name="post" id="post" action="admin.php?page=<?php echo $_REQUEST['page']; ?>&noheader=true" method="post">
		<input type="hidden" name="id_season" id="id_season" value="<?php echo $_GET['id']; ?>">

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content">

						<div id="titlediv">

							<div id="titlewrap">

								<input type="text" name="season_name" id="title" class="large-text" value="<?php echo $season_name; ?>" placeholder="Enter season name here" required>

								<br class="clear">

							</div>

						</div>

					</div>

				</div>

				<br class="clear">

				<div id="post-body-content">

					<p>Pick a Year from the dropdown box below.</p>

					<label for="year">Season:</label>

					<select name="year">

						<?php for( $i=2014; $i<2025; $i++ ) : ?>
						<option value="<?php echo $i; ?>" <?php selected( $i, $year ); ?>><?php echo $i; ?></option>
						<?php endfor; ?>

					</select>

				</div>

				<br class="clear">

				<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

			</div>

		</div>

	</form>

</div>
