<?php

class FPLLeague_Cup_Schedule_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$id_cup =  isset( $_GET['id_cup'] ) ? $_GET['id_cup'] : NULL;

		$data = $this->table_data( $id_cup );
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
			'cb' => '<input type="checkbox">',
			'name'			=> 'Week',
			'scheduled'		=> 'Date',
			'cup_name'	=> 'Competition',
			'id'			=> 'ID'
		);

		return $columns;

	}

	public function get_hidden_columns() {

		return array();

	}

	public function get_sortable_columns() {

		return array( 'scheduled' => array( 'scheduled', false ), 'name' => array( 'name', false ) );

	}

	private function table_data( $id_cup = NULL ) {

		$db = new FPLLeague_Database;

		$data = $db->get_every_cup_schedule( $id_cup, 0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'name':
			case 'cup_name':
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

		$orderby = 'scheduled';
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
	
		$actions = array(
			'fixtures' 	=> sprintf('<a href="?page=%s&id_cup=%d&id_schedule=%d">Fixtures</a>', 'fplleague-cup-fixtures', $_GET['id_cup'], $item['id']),
			'results' 	=> sprintf('<a href="?page=%s&id_cup=%d&id_schedule=%d">Results</a>', 'fplleague-cup-results', $_GET['id_cup'], $item['id'])
		);

	  return sprintf( '%1$s %2$s', $item['name'], $this->row_actions($actions) );
	}

}

$fplcupschedulelisttable = new FPLLeague_Cup_Schedule_List_Table;
$fplcupschedulelisttable->prepare_items();

