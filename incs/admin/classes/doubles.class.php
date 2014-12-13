<?php

if ( ! class_exists( 'WP_List_Table' ) )
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class FPLDoubles_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();
		usort( $data, array( &$this, 'sort_data' ) );

		$perPage = 20;
		$currentPage = $this->get_pagenum();
		$totalItems = count( $data );

		$this->set_pagination_args( array(
			'total_items'	=> $totalItems,
			'per_page'		=> $perPage
		) );

		$data = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $data;

	}

	public function get_columns() {

		$columns = array(
			'cb' => '<input type="checkbox">',
			'player_1_name' => 'Player 1',
			'player_2_name' => 'Player 2',
			'team_name'      => 'Team',
			'id'             => 'ID'
		);

		return $columns;

	}

	public function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="player_1_name[]" value="%s">', $item['id']
		);

	}

	public function get_hidden_columns() {

		return array();

	}

	public function get_sortable_columns() {

		return array(
            'player_1_name' => array( 'player_1_name', false ),
            'player_2_name' => array( 'player_2_name', false ),
            'team_name' => array( 'team_name', false ),
            'id' => array( 'id', false )
        );

	}

	private function table_data() {

		$db = new FPLLeague_Database;

		$data = $db->get_every_doubles( 0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'player_1_name':
			case 'player_1_name':
			case 'team_name':
				return $item[ $column_name ];

			default:
				return print_r( $item, true );

		}

	}

	private function sort_data( $a, $b ) {

		$orderby = 'player_1_name';
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

	function column_last_name( $item ) {
	
		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&id=%s">Edit</a>', 'fplleague-edit-doubles', $item['id']),
			'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>', 'fplleague-delete-doubles', 'delete', $item['id']),
		);

	  return sprintf('%1$s %2$s', $item['last_name'], $this->row_actions($actions) );
	}

	public function get_bulk_actions() {

		$actions = array(
			'delete' => 'Delete'
		);

		return $actions;

	}

}

$fpldoubleslisttable = new FPLDoubles_List_Table;
$fpldoubleslisttable->prepare_items();

