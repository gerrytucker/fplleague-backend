<?php
require "classes/fixtures.class.php";

$id_division = ( isset( $_GET['id_division'] ) ) ? $_GET['id_division'] : $_POST['id_division'];

?>

<div class="wrap">

	<?php
		$addnew_url = admin_url( $path = 'admin.php?page=fplleague-add-fixture&id_schedule=' . $_GET['id_schedule'] );
		$back_url = admin_url( $path = 'admin.php?page=fplleague-schedule&id_division=' . $_GET['id_division'] . '&id_schedule=' . $_GET['id_schedule'] );
	?>
	
	<h2>Fixtures <a href="<?php echo $addnew_url; ?>" class="add-new-h2">Add New</a></h2>
	<p><a href="<?php echo $back_url; ?>">&laquo; Back to Schedule</a></p>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] . '&id_division=' . $id_division; ?>">
		<?php $fplfixtureslisttable->display(); ?>
	</form>

	
</div>