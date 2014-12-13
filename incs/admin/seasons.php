<?php

if ( ! class_exists( 'WP_List_Table' ) ) {

	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

}

// Single Delete action
if ( isset ( $_GET['action'] ) && $_GET['action'] == 'delete' ) {

	$db = new FPLLeague_Database;
	if ( $db->delete_season( $_GET['id'] ) ) {
		wp_redirect( get_option( 'siteurl' ) . '/wp-admin/admin.php?page=fplleague-seasons&message=103' );
		exit;
	}

}

// Bulk Delete actions
if ( isset ( $_POST['action'] ) && ( $_POST['action'] == 'delete' || $_POST['action2'] == 'delete' ) ) {

	$db = new FPLLeague_Database;
	foreach( $_POST['season'] as $id_season ) {
		if ( $db->delete_season( $id_season ) ) {
		}
	}

}

class FPLSeason_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();
		usort( $data, array( &$this, 'sort_data' ) );

		$perPage = 10;
		$currentPage = $this->get_pagenum();
		$totalItems = count( $data );

		$this->set_pagination_args( array(
			'total_items'	=> $totalItems,
			'per_page'		=> $perPage
		) );

		$data = array_slice( $data, ( ( $currentPage - 1) * $perPage ), $perPage );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $data;

	}

	public function get_columns() {

		$columns = array(
			'cb'	=> '<input type="checkbox">',
			'name'		=> 'Name',
			'year'		=> 'Year',
			'id'		=> 'ID'
		);

		return $columns;

	}

	public function get_hidden_columns() {

		return array();

	}

	public function get_sortable_columns() {

		return array( 'name' => array( 'name', false ) );

	}

	private function table_data() {

		$db = new FPLLeague_Database;

		$data = $db->get_every_season( 0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'name':
			case 'year':
				return $item[ $column_name ];

			default:
				return print_r( $item, true );

		}

	}

	private function sort_data( $a, $b ) {

		$orderby = 'name';
		$order = 'asc';

		if ( ! empty( $_GET['orderby'] ) ) {

			$orderby = $_GET['orderby'];

		}

		if ( ! empty( $_GET['order'] ) ) {

			$order = $_GET['order'];

		}

		$result = strnatcmp( $a[$orderby], $b[$orderby] );

		if ( $order == 'asc' ) {

			return $result;

		}

		return -$result;

	}

	public function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="season[]" value="%s">', $item['id']
		);

	}

	function column_name( $item ) {
	
		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&id=%s">Edit</a>', 'fplleague-edit-season', $item['id']),
			'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>', 'fplleague-seasons', 'delete', $item['id']),
			'divisions' => sprintf('<a href="?page=%s&id_season=%s">Divisions</a>', 'fplleague-divisions', $item['id']),
		);

	  return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
	}

	public function get_bulk_actions() {

		$actions = array(
			'delete' => 'Delete'
		);

		return $actions;

	}

}

$fplseasonlisttable = new FPLSeason_List_Table;
$fplseasonlisttable->prepare_items();

?>

<div class="wrap">

	<h2>Seasons <a href="<?php echo admin_url( 'admin.php?page=fplleague-add-season' ); ?>" class="add-new-h2">Add New</a></h2>

	<?php
	if ( isset( $_GET['message'] ) )
		FPLLeague_Tools::show_message( $_GET['message'] );
	?>

	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
		<?php $fplseasonlisttable->display(); ?>
	</form>

</div>
