<?php

class FPLCups_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$id_season =  isset( $_GET['id_season'] ) ? $_GET['id_season'] : NULL;

		$data = $this->table_data( $id_season );
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
			'type'			=> 'Type',
			'season_name'	=> 'Season',
			'id'			=> 'ID'
		);

		return $columns;

	}

	public function get_hidden_columns() {

		return array();

	}

	public function get_sortable_columns() {

		return array( 'name' => array( 'name', false ), 'year' => array( 'year', false ) );

	}

	private function table_data() {

		$db = new FPLLeague_Database;

		$data = $db->get_every_cup(0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'name':
			case 'season_name':
				return $item[ $column_name ];

			case 'type':
				if ( $item[ $column_name ] === "T" ) {
					return "Team";
				}
				elseif ( $item[ $column_name ] === "D" ) {
					return "Doubles";
				}
				elseif ( $item[ $column_name ] === "S" ) {
					return "Singles";
				};

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
			'<input type="checkbox" name="cup[]" value="%s">', $item['id']
		);

	}

	function column_name( $item ) {
	
		$actions = array(
			'edit' => sprintf('<a href="?page=%s&id_cup=%s">Edit</a>', 'fplleague-edit-cup', $item['id']),
			'delete' => sprintf('<a href="?page=%s&action=%s&id_cup=%s">Delete</a>', 'fplleague-delete-cup', 'delete', $item['id']),
			'schedule' => sprintf('<a href="?page=%s&action=%s&id_cup=%s">Schedule</a>', 'fplleague-cup-schedule', 'schedule', $item['id']),
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

$fplcupslisttable = new FPLCups_List_Table;
$fplcupslisttable->prepare_items();
