<?php

class FPLLeague_Schedule_List_Table extends WP_List_Table {

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
			'week'			=> 'Week',
			'scheduled'		=> 'Date',
			'division_name'	=> 'Division',
			'season_name'	=> 'Season',
			'id'			=> 'ID'
		);

		return $columns;

	}

	public function get_hidden_columns() {

		return array();

	}

	public function get_sortable_columns() {

		return array( 'week' => array( 'week', false ) );

	}

	private function table_data( $id_division = NULL ) {

		$db = new FPLLeague_Database;

		$data = $db->get_every_schedule( $id_division, 0, 9999, 'ASC' );

		return $data;

	}

	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
			case 'week':
			case 'division_name':
			case 'season_name':
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

		$orderby = 'week';
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

	function column_week( $item ) {
	
		$actions = array(
			'fixtures' 	=> sprintf('<a href="?page=%s&id_division=%d&id_schedule=%d">Fixtures</a>', 'fplleague-fixtures', $_GET['id_division'], $item['id']),
			'results' 	=> sprintf('<a href="?page=%s&id_division=%d&id_schedule=%d">Results</a>', 'fplleague-results', $_GET['id_division'], $item['id'])
		);

	  return sprintf( '%1$s %2$s', $item['week'], $this->row_actions($actions) );
	}

}

$fplschedulelisttable = new FPLLeague_Schedule_List_Table;
$fplschedulelisttable->prepare_items();

