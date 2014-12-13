<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$team_name = $_POST['team_name'];
	$id_division = $_POST['id_division'];

	if ( $db->add_team( $team_name, $id_division ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-teams&id_division=' . $id_division . '&message=107' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

?>

<div class="wrap">

	<h2>Add New Team</h2>

	<?php
	if ( isset( $_GET['id_division'] ) ) {
		$form_url = 'admin.php?page=' . $_REQUEST['page'] . '&id_division=' . $_GET['id_division'] . '&noheader=true';
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
						<label for="team_name">Name</label>
					</th>

					<td>
						<input type="text" name="team_name" id="title" class="regular-text" placeholder="Enter team name here" required>
					</td>

				</tr>

				<tr>

					<th scope="row">

						<label for="id_division">Division</label>

					</th>

					<td>

<?php
$disabled = isset( $_GET['id_division'] );
?>

						<select name="id_division" <?php disabled( $disabled, true) ?>>

							<?php
							$divisions = $db->get_every_division( $id_division, 0, 9999 );
							foreach ( $divisions as $division ) :
							?>

							<option value="<?php echo $division['id']; ?>" <?php selected($division['id'], $_GET['id_division']); ?>><?php echo $division['name']; ?></option>

							<?php endforeach; ?>

						</select>

						<p class="description">A Team must be assigned to a Division.</p>

					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>
