<?php
$db = new FPLLeague_Database;

if ( $_POST['publish'] ) {

	$number_of_teams = $db->count_teams( $_GET['id_division'] );

	if ( $number_of_teams % 2 == 0 ) {

		$number_of_weeks = ( $number_of_teams * 2 ) - 2;

	}
	else {

		$number_of_weeks = $number_of_teams * 2;

	}

	// Insert in schedule table

	$date = $_POST['start_date'];
	$number = 1;

	foreach ( $i = 0; $i < $number_of_teams; $i++ ) {

		if ( $db->add_schedule( $_GET['id_division'], $number, $date ) ) {

			$date = date( 'Y-m-d', strtotime( '+1 week', $date ) );

		}

	}

	wp_redirect( admin_url( 'admin.php?page=fplleague-edit-schedule&id_division=' . $_GET['id_division'] ) . '&message=114' );
	exit;

}

?>

<div class="wrap">

	<h2>Create New Schedule</h2>

<?php

	if ( isset( $_GET['id_division'] ) ) {
		$form_url = 'admin.php?page=' . $_REQUEST['page'] . '&id_division=' . $_GET['id_division'] . '&noheader=true';
	}

?>

	<form name="post" id="post" action="<?php echo $form_url; ?>" method="post">

<?php $number_of_teams = $db->count_teams( $_GET['id_division'] ); ?>

		<input name="number_of_teams" type="hidden" value="<?php echo $number_of_teams; ?>">

		<table class="form-table">

			<tbody>

				<tr>

					<th scope="row">
						<label for="start_date">Start Date</label>
					</th>

					<td>
						<input type="date" name="start_date" id="date" required>
					</td>

				</tr>

			</tbody>

		</table>

		<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Create Schedule' ); ?>">

	</form>

</div>
