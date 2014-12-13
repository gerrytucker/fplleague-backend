<?php

if ( ! class_exists( 'WP_List_Table' ) )
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class FPLLeague_Fixtures_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$id_schedule = isset( $_GET['id_schedule'] ) ? $_GET['id_schedule'] : NULL;

		$data = $this->table_data( $id_schedule );
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
			'cb'				=> '<input type="checkbox">',
			'team_home_name'	=> 'Home Team',
			'team_away_name'	=> 'Away Team',
			'scheduled'			=> 'Scheduled',
			'id'				=> 'ID'
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

		return array( 'team_home_name' => array( 'team_home_name', false ), 'team_away_name' => array( 'team_away_name', false ) );

	}

	private function table_data( $id_schedule = NULL ) {

		$db = new FPLLeague_Database;

		$data = $db->get_every_fixture( $id_schedule, 0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'team_home_name':
			case 'team_away_name':
				return $item[ $column_name ];

			case 'scheduled':
				list($year, $month, $day) = explode('-', $item[ $column_name ]);
				$time = mktime(0, 0, 0, $month, $day, $year);
				return date( 'd/m/Y', $time );

			default:
				return print_r( $item, true );

		}

	}

	private function sort_data( $a, $b ) {

		$orderby = 'team_home_name';
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

	function column_team_home_name( $item ) {
	
		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&action=edit&id_division=%d&id=%d">Edit</a>', 'fplleague-edit-fixture', $_GET['id_division'], $item['id']),
			'delete'    => sprintf('<a href="?page=%s&action=delete&id=%s">Delete</a>', 'fplleague-delete-fixture', $item['id']),
		);

	  return sprintf('%1$s %2$s', $item['team_home_name'], $this->row_actions($actions) );
	}

	public function get_bulk_actions() {

		$actions = array(
			'delete' => 'Delete'
		);

		return $actions;

	}

}

$fplfixtureslisttable = new FPLLeague_Fixtures_List_Table;
$fplfixtureslisttable->prepare_items();

