<?php
require 'classes/schedule.class.php';

$db = new FPLLeague_Database;

if ( isset( $_POST['publish'] ) ) {

	if ( $_POST['action'] == 'create' ) {

		$number_of_teams = $db->count_teams( $_GET['id_division'] );

		if ( $number_of_teams % 2 == 0 ) {

			$number_of_weeks = ( $number_of_teams * 2 ) - 2;

		}
		else {

			$number_of_weeks = $number_of_teams * 2;

		}

		// Insert in schedule table

		$date = $_POST['start_date'];
		$week = 0;

		for ( $i = 0; $i < $number_of_weeks; $i++ ) {

			$week++;

			if ( $db->add_schedule( $_GET['id_division'], $week, $date ) ) {

				list($year, $month, $day) = explode('-', $date);
				$time = mktime(0, 0, 0, $month, $day, $year);
				$date = date( 'Y-m-d', strtotime( '+1 week', $time ) );


			}

		}

		wp_redirect( admin_url( 'admin.php?page=fplleague-schedule&id_division=' . $_GET['id_division'] ) . '&message=114' );
		exit;

	}
	else {

		$db->update_schedule( $_GET['id_division'], $_POST['week'] );
		
		wp_redirect( admin_url( 'admin.php?page=fplleague-schedule&id_division=' . $_GET['id_division'] ) . '&message=115' );
		exit;

	}

}

// If Division ID is passed...
if ( $_GET['id_division'] ) {

	// If no schedule we need to create one...
	if ( $db->count_schedules( $_GET['id_division'] ) === 0 ) {

		$action = 'create';

	}
	else {

		$action = 'update';

	}

}


switch ( $action ) {

	case 'create':

?>

	<div class="wrap">

		<h2>Create New Schedule</h2>
		<?php
			
			if ( isset( $_GET['message'] ) )
				FPLLeague_Tools::show_message( $_GET['message'] );
			
		?>

	<?php

		if ( isset( $_GET['id_division'] ) ) {
			$form_url = 'admin.php?page=' . $_REQUEST['page'] . '&id_division=' . $_GET['id_division'] . '&noheader=true';
		}

	?>

		<form name="post" id="post" action="<?php echo $form_url; ?>" method="post">

		<?php $number_of_teams = $db->count_teams( $_GET['id_division'] ); ?>

			<input name="action" type="hidden" value="<?php echo $action; ?>">
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

			<input class="button button-primary button-large" type="submit" name="publish" id="publish" value="<?php _e( 'Save' ); ?>">

		</form>

	</div>

<?php
		break;

	case 'update':

?>

	<div class="wrap">

		<h2>Edit Schedule</h2>

		<?php
		if ( isset( $_GET['message'] ) )
			FPLLeague_Tools::show_message( $_GET['message'] );
		?>

		<form method="post">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
			<?php $fplschedulelisttable->display(); ?>
		</form>

	</div>

<?php
		break;
		
}
