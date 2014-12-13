<?php
$db = new FPLLeague_Database;

if ( isset( $_POST['publish'] ) ) {

	$name = $_POST['name'];
	$scheduled = $_POST['scheduled'];
	
	if ( $db->add_cup_schedule( $_POST['id_cup'], $name, $scheduled ) ) {

		wp_redirect( admin_url( 'admin.php?page=fplleague-cup-schedule&id_cup=' . $_GET['id_cup'] ) . '&message=140' );
		exit;

	}
	
}

?>

<div class="wrap">

	<h2>Add New Schedule</h2>

<?php

	if ( isset( $_GET['id_cup'] ) ) {
		$form_url = 'admin.php?page=' . $_REQUEST['page'] . '&id_cup=' . $_GET['id_cup'] . '&noheader=true';
	}

?>

	<form name="post" id="post" action="<?php echo $form_url; ?>" method="post">
		<input type="hidden" name="id_cup" value="<?php echo $_GET['id_cup']; ?>">
		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">
						<label for="name">Name</label>
					</th>

					<td>
						<input class="regular-text" type="text" name="name" id="name" required autocapitalize="on">
					</td>

				</tr>

				<tr>

					<th scope="row">
						<label for="scheduled">Scheduled Date</label>
					</th>

					<td>
						<input type="date" name="scheduled" id="date" required>
					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

	</form>

</div>
