<?php

if ( ! class_exists( 'FPLLeague_Front' ) ) {

	class FPLLeague_Front {

		/**
		 * Constructor
		 */
		public function __construct() {}


		/**
		 * Front-End Styles
		 */
		public static function print_front_styles() {

			wp_register_style( 'fplleague-front', plugins_url( 'assets/css/fplleague-front.css', dirname( __FILE__ ) ) );
			wp_enqueue_style( 'fplleague-front' );

		}

		/**
		 * Front-End Scripts
		 */
		public static function print_front_scripts() {

			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', false, false, true );
			wp_enqueue_script( 'jquery' );

		}


		/**
		 * Get division table
		 */
		public function get_division_table( $id_division ) {

			global $wpdb;
			$db = new FPLLeague_Database;

			$division = $db->get_division( $id_division );

			$output =
				'<h3>Table</h3>' .
				'<table id="fplleague_division">' .
					'<thead>' .
						'<tr>' .
							'<th>' . __('Team', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pld', 'fplleague') . '</th>' .
							'<th class="centered">' . __('W', 'fplleague') . '</th>' .
							'<th class="centered">' . __('L', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('F', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('A', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('Diff', 'fplleague') . '</th>' .
						'</tr>' .
					'</thead>' .
					'<tbody>';

			foreach( $db->get_table( $id_division) as $row ) :

				$id_team = $row['id'];
				$team_name = $row['team_name'];
				$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $id_team );
				$match_played = (int) $row['match_played'];
				$match_won = (int) $row['match_won'];
				$match_lost = (int) $row['match_lost'];
				$point_for = (int) $row['point_for'];
				$point_against = (int) $row['point_against'];
				$point_total = (int) $row['point_total'];
				$point_diff = (int) $row['point_diff'];

			$output .=
				'<tr>' .
					'<td><a href="' . $team_url . '">' . $team_name . '</a></td>' .
					'<td class="centered">' . $match_played . '</td>' .
					'<td class="centered">' . $match_won . '</td>' .
					'<td class="centered">' . $match_lost . '</td>' .
					'<td class="centered hide-for-small">' . $point_for . '</td>' .
					'<td class="centered hide-for-small">' . $point_against . '</td>' .
					'<td class="centered">' . $point_total . '</td>' .
					'<td class="centered hide-for-small">' . $point_diff . '</td>' .
				'</tr>';
			
			endforeach;

			$output .=
					'</tbody>' .
					'<tfoot>' .
						'<tr>' .
							'<th>' . __('Team', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pld', 'fplleague') . '</th>' .
							'<th class="centered">' . __('W', 'fplleague') . '</th>' .
							'<th class="centered">' . __('L', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('F', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('A', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('Diff', 'fplleague') . '</th>' .
						'</tr>' .
					'</tfoot>' .
				'</table>';

			return $output;

		}

		/**
		 * Get latest division results
		 */
		public function get_latest_results( $id_division ) {

			global $wpdb;
			$db = new FPLLeague_Database;

			if ( $db->is_division_exists( $id_division ) === false )
				return;

			$output =
				'<table id="fplleague">' .
					'<thead>' .
						'<tr>' .
							'<th class="centered">' . __('Home Team', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered">' . __('-', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Away Team', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Date', 'fplleague') . '</th>' .
						'</tr>' .
					'</thead>' .
					'<tbody>';

/*
			foreach( $db->get_table( $id_division) as $row ) :

				$team_name = $row['team_name'];
				$match_played = (int) $row['match_played'];
				$match_won = (int) $row['match_won'];
				$match_lost = (int) $row['match_lost'];
				$point_for = (int) $row['point_for'];
				$point_against = (int) $row['point_against'];
				$point_total = (int) $row['point_total'];
				$point_diff = (int) $row['point_diff'];

			$output .=
				'<tr>' .
					'<td>' . $team_name. '</td>' .
					'<td class="centered">' . $match_played . '</td>' .
					'<td class="centered">' . $match_won . '</td>' .
					'<td class="centered">' . $match_lost . '</td>' .
					'<td class="centered">' . $point_for . '</td>' .
					'<td class="centered">' . $point_against . '</td>' .
					'<td class="centered">' . $point_total . '</td>' .
					'<td class="centered">' . $point_diff . '</td>' .
				'</tr>';
			
			endforeach;

*/

			$output .=
					'</tbody>' .
				'</table>';

			return $output;

		}


		public function get_all_teams_by_division() {

            global $wpdb;
            $db = new FPLLeague_Database;

            $number_divisions = $db->count_divisions();
            $column_class = 'large-' . (12 / $number_divisions) . ' small-' . (12 / $number_divisions);

            $current_season = $db->get_current_season();

            $divisions = $db->get_every_division( $current_season );

            $output =
                "<h2>Teams</h2>" .
                '<div class="row data-equalizer">';

            foreach ( $divisions as $division ) {

                $output .=
                    '<div class="' . $column_class . ' columns">' .
                        '<div class="panel radius">' .
                            '<h3>' . $division['name'] . '</h3>';

                $teams = $db->get_every_team( $division['id'], 0, 9999, 'ASC' );

                $output .= '<ul>';

                foreach ( $teams as $team ) {

                    $output .= '<li>' . $db->get_team_page_link( $team['id'] ) . '</li>';

                }

                $output .=
                            '</ul>' .
                        '</div>' .
                    '</div>';

            }

            $output .=
                '</div>';
            
            return $output;

		}

		public function get_teams_by_division( $id_division ) {

			return;

		}


		public function get_division( $id_division ) {

			global $wpdb;
			$db = new FPLLeague_Database;

			$division= $db->get_division( $id_division );

			$output =
				'<h2>' . $division['name'] . '</h2>';

			$output .= $this->get_division_table( $id_division );

			return $output;

		}



		public function get_team( $id_team ) {

			global $wpdb;
			$db = new FPLLeague_Database;

			$team = $db->get_team( $id_team );
			$division = $db->get_division( $team['id_division'] );
			$team_record = $db->get_team_record( $id_team );
			if ( $team_record )
				$record = ' (W' . $team_record['match_won'] . ' L' . $team_record['match_lost'] . ')';

			$output =
				'<h2>' . $team['name'] . '</h2>' .
				'<p>Play in <strong>' . $division['name'] . $record . '</strong></p>';

			$output .=
				'<h3>Results</h3>' .
				'<table id="fplleague_results">' .
					'<thead>' .
						'<tr>' .
							'<th class="centered show-for-medium-up">' . __('Date', 'fplleague') . '</th>' .
							'<th class="centered hidden-for-medium-up">' . __('Date', 'fplleague') . '</th>' .
							'<th class="centered show-for-medium-up">' . __('Home Team', 'fplleague') . '</th>' .
							'<th class="centered show-for-medium-up">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered show-for-medium-up">' . __('-', 'fplleague') . '</th>' .
							'<th class="centered show-for-medium-up">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered show-for-medium-up">' . __('Away Team', 'fplleague') . '</th>' .
							'<th class="hidden-for-medium-up">' . __('Opponent', 'fplleague') . '</th>' .
							'<th class="centered hidden-for-medium-up">' . __('V', 'fplleague') . '</th>' .
							'<th class="centered hidden-for-medium-up">' . __('R', 'fplleague') . '</th>' .
							'<th class="centered hidden-for-medium-up">' . __('F', 'fplleague') . '</th>' .
							'<th class="centered hidden-for-medium-up">' . __('A', 'fplleague') . '</th>' .
						'</tr>' .
					'</thead>' .
					'<tbody>';

			$fixtures = $db->get_every_team_result( $id_team );

			foreach ( $fixtures as $fixture ) {

				$output .= '<tr>';
				
				$output .=
							'<td class="centered show-for-medium-up">';

				if ( $fixture['played'] !== NULL ) {
					list($year, $month, $day) = explode('-', $fixture['played']);
					$time = mktime(0, 0, 0, $month, $day, $year);
					$played = date( 'd/m/Y', $time );
					$output .= $played;
				}
				else {
					list($year, $month, $day) = explode('-', $fixture['scheduled']);
					$time = mktime(0, 0, 0, $month, $day, $year);
					$scheduled = date( 'd/m/Y', $time );
					$output .= $scheduled;
				}

				$output .=
							'</td>';

				$output .=
							'<td class="centered hidden-for-medium-up">';

				if ( $fixture['played'] !== NULL ) {
					list($year, $month, $day) = explode('-', $fixture['played']);
					$time = mktime(0, 0, 0, $month, $day, $year);
					$played = date( 'd/m', $time );
					$output .= $played;
				}
				else {
					list($year, $month, $day) = explode('-', $fixture['scheduled']);
					$time = mktime(0, 0, 0, $month, $day, $year);
					$scheduled = date( 'd/m', $time );
					$output .= $scheduled;
				}

				$output .=
							'</td>';

				if ( $id_team == $fixture['id_team_home'] ) {

					$output .=
							'<td class="show-for-medium-up"><strong>' . $fixture['team_home_name'] . '</strong></td>';

				}
				else {

					$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $fixture['id_team_home'] );

					$output .=
							'<td class="show-for-medium-up"><a href="' . $team_url . '">' . $fixture['team_home_name'] . '</a></td>';

				}

				$output .=
							'<td class="centered show-for-medium-up">' . $fixture['point_home'] . '</td>' .
							'<td class="centered show-for-medium-up">-</td>' .
							'<td class="centered show-for-medium-up">' . $fixture['point_away'] . '</td>';

				if ( $id_team == $fixture['id_team_away'] ) {

					$output .=
							'<td class="show-for-medium-up"><strong>' . $fixture['team_away_name'] . '</strong></td>';

				}
				else {

					$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $fixture['id_team_away'] );

					$output .=
							'<td class="show-for-medium-up"><a href="' . $team_url . '">' . $fixture['team_away_name'] . '</a></td>';

				}

				if ( $id_team == $fixture['id_team_home'] ) {

					$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $fixture['id_team_home'] );

					$output .=
							'<td class="hidden-for-medium-up"><a href="' . $team_url . '">' . $fixture['team_away_name'] . '</a></td>';

				}
				else if ( $id_team == $fixture['id_team_away'] ) {

					$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $fixture['id_team_away'] );

					$output .=
							'<td class="hidden-for-medium-up"><a href="' . $team_url . '">' . $fixture['team_home_name'] . '</a></td>';

				}

				// Result
				if ( $id_team == $fixture['id_team_home'] ) {
					$output .= '<td class="centered hidden-for-medium-up">H</td>';
				}
				else {
					$output .= '<td class="centered hidden-for-medium-up">A</td>';
				}
				
				// Venue
				if ( $id_team == $fixture['id_team_home'] ) {
					if ( $fixture['point_home'] > $fixture['point_away'] ) {
						$output .= '<td class="centered hidden-for-medium-up">W</td>';
					}
					else {
						$output .= '<td class="centered hidden-for-medium-up">L</td>';
					}
				}
				else {
					if ( $fixture['point_away'] > $fixture['point_home'] ) {
						$output .= '<td class="centered hidden-for-medium-up">W</td>';
					}
					else {
						$output .= '<td class="centered hidden-for-medium-up">L</td>';
					}
				}
							
				if ( $id_team == $fixture['id_team_home'] ) {
					$output .=
						'<td class="centered hidden-for-medium-up">' . $fixture['point_home'] . '</td>' .
						'<td class="centered hidden-for-medium-up">' . $fixture['point_away'] . '</td>';
				} else if ( $id_team == $fixture['id_team_away'] ) {
					$output .=
						'<td class="centered hidden-for-medium-up">' . $fixture['point_away'] . '</td>' .
						'<td class="centered hidden-for-medium-up">' . $fixture['point_home'] . '</td>';
				}


				$output .=
						'</tr>';

			}

			$output .=
					'<tbody>' .
				'</table>';

			$output .=
				'<h3>Fixtures</h3>' .
				'<table id="fplleague_fixtures">' .
					'<thead>' .
						'<tr>' .
							'<th class="centered">' . __('Date', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Home Team', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered">' . __('-', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Away Team', 'fplleague') . '</th>' .
						'</tr>' .
					'</thead>' .
					'<tbody>';

			$fixtures = $db->get_every_team_fixture( $id_team );

			foreach ( $fixtures as $fixture ) {

				$output .=
						'<tr>' .
							'<td class="centered">';

				if ( $fixture['played'] !== NULL ) {
					list($year, $month, $day) = explode('-', $fixture['played']);
					$time = mktime(0, 0, 0, $month, $day, $year);
					$played = date( 'd/m/Y', $time );
					$output .= $played;
				}
				else {
					list($year, $month, $day) = explode('-', $fixture['scheduled']);
					$time = mktime(0, 0, 0, $month, $day, $year);
					$scheduled = date( 'd/m/Y', $time );
					$output .= $scheduled;
				}

				$output .=
							'</td>';

				if ( $id_team == $fixture['id_team_home'] ) {

					$output .=
							'<td><strong>' . $fixture['team_home_name'] . '</strong></td>';

				}
				else {

					$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $fixture['id_team_home'] );

					$output .=
							'<td><a href="' . $team_url . '">' . $fixture['team_home_name'] . '</a></td>';

				}

				$output .=
							'<td class="centered">' . $fixture['point_home'] . '</td>' .
							'<td class="centered">-</td>' .
							'<td class="centered">' . $fixture['point_away'] . '</td>';

				if ( $id_team == $fixture['id_team_away'] ) {

					$output .=
							'<td><strong>' . $fixture['team_away_name'] . '</strong></td>';

				}
				else {

					$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $fixture['id_team_away'] );

					$output .=
							'<td><a href="' . $team_url . '">' . $fixture['team_away_name'] . '</a></td>';

				}

				$output .=
						'</tr>';

			}

			$output .=
					'<tbody>' .
				'</table>';

			$output .= $this->get_team_table( $id_team );

			return $output;

		}

		/**
		 * Get division table
		 */
		public function get_team_table( $id_team ) {

			global $wpdb;
			$db = new FPLLeague_Database;

			$team = $db->get_team( $id_team );
			$division = $db->get_division( $team['id_division'] );

			$output =
				'<h3>' . $division['name'] . ' Table</h3>' .
				'<table id="fplleague">' .
					'<thead>' .
						'<tr>' .
							'<th>' . __('Team', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pld', 'fplleague') . '</th>' .
							'<th class="centered">' . __('W', 'fplleague') . '</th>' .
							'<th class="centered">' . __('L', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('F', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('A', 'fplleague') . '</th>' .
							'<th class="centered">' . __('Pts', 'fplleague') . '</th>' .
							'<th class="centered hide-for-small">' . __('Diff', 'fplleague') . '</th>' .
						'</tr>' .
					'</thead>' .
					'<tbody>';

			foreach( $db->get_table( $team['id_division'] ) as $row ) :

				$team_name = $row['team_name'];
				$team_url = trailingslashit( get_bloginfo( 'wpurl' ) . '/team/' . $row['id'] );
				$match_played = (int) $row['match_played'];
				$match_won = (int) $row['match_won'];
				$match_lost = (int) $row['match_lost'];
				$point_for = (int) $row['point_for'];
				$point_against = (int) $row['point_against'];
				$point_total = (int) $row['point_total'];
				$point_diff = (int) $row['point_diff'];

				if ( $id_team == $row['id'] ) {

					$output .=
						'<tr>' .
							'<td><strong>' . $team_name . '</strong></td>' .
							'<td class="centered"><strong>' . $match_played . '</strong></td>' .
							'<td class="centered"><strong>' . $match_won . '</strong></td>' .
							'<td class="centered"><strong>' . $match_lost . '</strong></td>' .
							'<td class="centered hide-for-small"><strong>' . $point_for . '</strong></td>' .
							'<td class="centered hide-for-small"><strong>' . $point_against . '</strong></td>' .
							'<td class="centered"><strong>' . $point_total . '</strong></td>' .
							'<td class="centered hide-for-small"><strong>' . $point_diff . '</strong></td>' .
						'</tr>';

				}
				else {

					$output .=
						'<tr>' .
							'<td>' . $team_name . '</td>' .
							'<td class="centered">' . $match_played . '</td>' .
							'<td class="centered">' . $match_won . '</td>' .
							'<td class="centered">' . $match_lost . '</td>' .
							'<td class="centered hide-for-small">' . $point_for . '</td>' .
							'<td class="centered hide-for-small">' . $point_against . '</td>' .
							'<td class="centered">' . $point_total . '</td>' .
							'<td class="centered hide-for-small">' . $point_diff . '</td>' .
						'</tr>';

				}
					
			endforeach;

			$output .=
					'</tbody>' .
				'</table>';

			return $output;

		}

	}

}