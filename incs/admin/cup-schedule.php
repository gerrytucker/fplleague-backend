<?php
require 'classes/cup-schedule.class.php';
$db = new FPLLeague_Database;
?>

<div class="wrap">

	<h2>Cup Competition Schedule <a href="<?php echo admin_url('admin.php?page=fplleague-add-cup-schedule&id_cup=' . $_GET['id_cup']); ?>" class="add-new-h2">Add New</a></h2>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<?php
	if ( isset( $_GET['id_cup'] ) && $cup = $db->get_cup( $_GET['id_cup'] ) ) {
		echo '<h4>' . $cup['name'] . '</h4>';
	}
	?>
	
	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
		<?php $fplcupschedulelisttable->display(); ?>
	</form>

</div>
