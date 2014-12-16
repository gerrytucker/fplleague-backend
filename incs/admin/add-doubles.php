<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$id_player_1 = $_POST['id_player_1'];
	$id_player_2 = $_POST['id_player_2'];
	$id_team = $_POST['id_team'];

	if ( $db->add_doubles( $id_player_1, $id_player_2, $id_team ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-doubles&message=150' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

?>

<div class="wrap">

	<h2>Add New Doubles</h2>

	<form name="post" id="post" action="<?php echo admin_url( 'admin.php?page=' . $_REQUEST['page'] . '&noheader=true' ); ?>" method="post">

		<table class="form-table">

			<tbody>

				<tr>
					<th scope="row"><h3>Step 1 - Select A Team</h3></th>
				</tr>
				
				<tr>

					<th scope="row">

						<label for="id_team">Team</label>

					</th>

					<td>

						<select id="doubles_select_team" name="id_team">

							<option value="">Select A Team...</option>

							<?php
							$team = $db->get_every_team( NULL, 0, 9999, 'ASC' );
							foreach ( $team as $team ) :
							?>

							<option value="<?php echo $team['id']; ?>"><?php echo $team['name']; ?></option>

							<?php endforeach; ?>

						</select>

						<div class="spinner wait-1"></div>
						<script type="text/javascript">jQuery('.spinner.wait-1').css('float', 'left');</script>
						
					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>" disabled>

	</form>

</div>
