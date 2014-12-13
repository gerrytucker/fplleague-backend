<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$id_team = $_POST['id_team'];

	if ( $db->add_player( $first_name, $last_name, $id_team ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-players&message=122' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

?>

<div class="wrap">

	<h2>Add New Player</h2>

	<form name="post" id="post" action="<?php echo admin_url( 'admin.php?page=' . $_REQUEST['page'] . '&noheader=true' ); ?>" method="post">

		<table class="form-table">

			<tbody>

				<tr>
						
					<th scope="row">
						<label for="first_name">First name</label>
					</th>

					<td>
						<input type="text" name="first_name" class="regular-text" placeholder="Enter first name here" autocapitalize="on" required>
					</td>

				</tr>

				<tr>
						
					<th scope="row">
						<label for="last_name">Last name</label>
					</th>

					<td>
						<input type="text" name="last_name" class="regular-text" placeholder="Enter last name here" autocapitalize="on" required>
					</td>

				</tr>

				<tr>

					<th scope="row">

						<label for="id_team">Team</label>

					</th>

					<td>

						<select name="id_team">

							<option value="">Select A Team...</option>

                            <?php
							$team = $db->get_every_team( NULL, 0, 9999, 'ASC' );
							foreach ( $team as $team ) :
							?>

							<option value="<?php echo $team['id']; ?>"><?php echo $team['name']; ?></option>

							<?php endforeach; ?>

						</select>

						<p class="description">A Player must be assigned to a Team.</p>

					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>
