<?php
require "classes/results.class.php";

$id_division = ( isset( $_GET['id_division'] ) ) ? $_GET['id_division'] : $_POST['id_division'];
$id_schedule = ( isset( $_GET['id_schedule'] ) ) ? $_GET['id_schedule'] : $_POST['id_schedule'];

?>

<div class="wrap">

	<h2>Results</h2>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] . '&id_division=' . $id_division . '&id_schedule=' . $id_schedule; ?>">
		<?php $fplresultslisttable->display(); ?>
	</form>

</div>