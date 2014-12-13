<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$season_name = $_POST['season_name'];
	(int) $year = $_POST['year'];

	if ( $db->add_season( $season_name, $year ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-seasons&message=101' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

?>

<div class="wrap">

	<h2>Add New Season</h2>

	<form name="post" id="post" action="admin.php?page=<?php echo $_REQUEST['page']; ?>&noheader=true" method="post">

		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">

						<label for="season_name">Name</label>

					</th>

					<td>

						<input type="text" name="season_name" id="title" class="regular-text" placeholder="Enter season name here" required>

					</td>

				</tr>

				<tr>

					<th scope="row">

						<label for="year">Year</label>

					</th>

					<td>

						<select name="year">

							<?php for( $i=2014; $i<2024; $i++ ) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php endfor; ?>

						</select>

						<p class="description">A Season must be assigned a Year, e.g. 2014 would be considered 2014/15.</p>

					</td>

				</tr>

			</tbody>

		</table>

		<br class="clear">

		<div class="publishing-action">
			<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">
			<span class="spinner"></span>
		</div>

	</form>

</div>
