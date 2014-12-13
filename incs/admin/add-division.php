<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$division_name = $_POST['division_name'];
	$id_season = $_POST['id_season'];

	if ( $db->add_division( $division_name, $id_season ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-divisions&id_season=' . $id_season . '&message=104' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}


// Season passed via parameter
$disabled = isset( $_GET['id_season'] );

?>

<div class="wrap">

	<h2>Add New Division</h2>

	<?php
	if ( isset( $_GET['id_season'] ) ) {
		$form_url = 'admin.php?page=' . $_REQUEST['page'] . '&id_season=' . $_GET['id_season'] . '&noheader=true';
	}
	else {
		$form_url = 'admin.php?page=' . $_REQUEST['page'] . '&noheader=true';
	}
	?>
	<form name="post" id="post" action="<?php echo $form_url; ?>" method="post">

		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">
						<label for="division_name">Name</label>
					</th>

					<td>
						<input type="text" name="division_name" id="title" class="regular-text" placeholder="Enter division name here" required>
					</td>

				</tr>

				<tr>

					<th scope="row">
						<label for="id_season">Season</label>
					</th>

					<td>

						<?php
						// Season passed via parameter
						$disabled = isset( $_GET['id_division'] );
						?>

						<select name="id_season" <?php disabled( $disabled, true) ?>>

						<?php
						$db = new FPLLeague_Database;
						$seasons = $db->get_every_season();
						foreach ( $seasons as $season ) :
						?>

							<option value="<?php echo $season['id']; ?>"><?php echo $season['name']; ?></option>

						<?php endforeach; ?>

						</select>

						<p class="description">A new Division must be allocated to a Season.</p>

					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>
