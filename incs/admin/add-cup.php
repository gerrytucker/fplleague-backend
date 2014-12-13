<?php
$db = new FPLLeague_Database;

if( isset( $_POST['publish'] ) ) {

	$cup_name = $_POST['cup_name'];
	$cup_type = $_POST['cup_type'];
	$id_season = $_POST['id_season'];

	if ( $db->add_cup( $cup_name, $id_season, $cup_type ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-cups&message=130' ) );
		exit;

	} else {

	}

}

if ( isset( $_POST['noheader'] ) ) {
	require_once ABSPATH . 'wp-admin/admin-header.php';
}

?>

<div class="wrap">

	<h2>Add New Cup Competition</h2>

	<?php $form_url = 'admin.php?page=' . $_REQUEST['page'] . '&noheader=true'; ?>
	
	<form name="post" id="post" action="<?php echo $form_url; ?>" method="post">

		<table class="form-table">

			<tbody>

				<tr>
						
					<th scope="row">
						<label for="cup_name">Competition Name</label>
					</th>

					<td>
						<input type="text" name="cup_name" id="title" class="regular-text" placeholder="Enter cup competition name here" required autocapitalize="on">
					</td>

				</tr>

				<tr>
						
					<th scope="row">
						<label for="cup_type">Competition Type</label>
					</th>

					<td>
						<select name="cup_type">
							<option value="T">Team</option>
							<option value="D">Doubles</option>
							<option value="S">Singles</option>
						</select>

						<p class="description">A Competition can be for Teams, Doubles or Singles.</p>

					</td>

				</tr>

				<tr>

					<th scope="row">

						<label for="id_season">Season</label>

					</th>

					<td>

						<select name="id_season">

							<?php
							$seasons = $db->get_every_season( 0, 9999 );
							foreach ( $seasons as $season ) :
							?>

							<option value="<?php echo $season['id']; ?>"><?php echo $season['name']; ?></option>

							<?php endforeach; ?>

						</select>

						<p class="description">A Competition must be assigned to a Season.</p>

					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>
