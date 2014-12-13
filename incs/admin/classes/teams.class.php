<?php

if ( ! class_exists( 'WP_List_Table' ) )
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class FPLTeam_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$id_division =  isset( $_GET['id_division'] ) ? $_GET['id_division'] : NULL;

		$data = $this->table_data( $id_division );
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
			'cb'			=> '<input type="checkbox">',
			'name'			=> 'Name',
			'division_name'	=> 'Division',
			'season_name'	=> 'Season',
			'id'			=> 'ID'
		);

		return $columns;

	}

	public function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="team[]" value="%s">', $item['id']
		);

	}

	public function get_hidden_columns() {

		return array();

	}

	public function get_sortable_columns() {

		return array( 'name' => array( 'name', false ), 'division_name' => array( 'division_name', false ) );

	}

	private function table_data( $id_division = NULL ) {

		$db = new FPLLeague_Database;

		$data = $db->get_every_team( $id_division, 0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'scheduled':
			case 'name':
			case 'division_name':
			case 'season_name':
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

	function column_name( $item ) {
	
		if ( isset( $_GET['id_division'] ) ) {
			$delete_page = 'fplleague-teams&id_division=' . $_GET['id_division'];
		}
		else {
			$delete_page = 'fplleague-teams';
		}

		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&id=%s">Edit</a>', 'fplleague-edit-team', $item['id']),
			'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>', $delete_page, 'delete', $item['id']),
			'fixtures' 	=> sprintf('<a href="?page=%s&id_team=%d">Fixtures</a>', 'fplleague-fixtures', $item['id']),
			'results' 	=> sprintf('<a href="?page=%s&id_team=%d">Results</a>', 'fplleague-results', $item['id'])
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

$fplteamlisttable = new FPLTeam_List_Table;
$fplteamlisttable->prepare_items();

