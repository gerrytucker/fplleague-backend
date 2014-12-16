<?php

if ( ! class_exists( 'FPLLeague_Database' ) ) {

	class FPLLeague_Database extends FPLLeague_Tools {

		/**
		 * Constructor
		 */
		public function __construct() {

			parent::__construct();

		}

		/**
		 * Count the teams
		 */
		public function count_teams( $id_division ) {

			global $wpdb;

			return $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->teams
				WHERE id_division = %d",
				$id_division ) );

		}

		/**
		 * Get all the teams
		 */
		public function get_every_team( $id_division = NULL, $offset = 0, $limit = 10, $order = 'DESC' ) {

			global $wpdb;

			if ( $id_division === NULL ) {

				return $wpdb->get_results( $wpdb->prepare(
					"SELECT a.id, a.name, a.id_division, b.name as division_name, c.name as season_name
					FROM $wpdb->teams AS a
					LEFT JOIN $wpdb->divisions AS b
					ON a.id_division = b.id
					LEFT JOIN $wpdb->seasons AS c
					ON b.id_season = c.id
					ORDER BY a.name $order
					LIMIT %d, %d",
					$offset, $limit ), ARRAY_A
				);

			}
			else {

				return $wpdb->get_results( $wpdb->prepare(
					"SELECT a.id, a.name, a.id_division, b.name as division_name, c.name as season_name
					FROM $wpdb->teams AS a
					LEFT JOIN $wpdb->divisions AS b
					ON a.id_division = b.id
					LEFT JOIN $wpdb->seasons AS c
					ON b.id_season = c.id
					WHERE a.id_division = $id_division
					ORDER BY a.name $order
					LIMIT %d, %d",
					$offset, $limit ), ARRAY_A
				);

			}

		}

		/**
		 * Is team unique
		 */
		public function is_team_unique( $var, $control = 'name' ) {

			global $wpdb;

			if ( $control == 'name' ) {
				$exist = $wpdb->get_var( $wpdb->prepare(
					"SELECT COUNT(*) FROM $wpdb->teams WHERE name = %s",
					$var
				));
			}
			elseif ( $control == 'id' ) {
				$exist = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->teams WHERE id = %d", $var ) );
			}
			else {
				return;
			}

			if ( $exist == 0 )
				return true;
			else
				return false;

		}

        /**
         * Get team page link
         */
        public function get_team_page_link( $id_team ) {
            
            if ( $team = $this->get_team( $id_team ) ) {
                
                return '<a href="' . trailingslashit( get_bloginfo( 'wpurl') . '/team/' . $id_team ) . '">' . $team['name'] . '</a>';
                
            }
            else {
                
                return NULL;
                
            }
            
        }
        
        
		/**
		 * Add a team
		 */
		public function add_team( $name, $id_division ) {

			global $wpdb;

			return $wpdb->insert( $wpdb->teams, array( 'name' => $name, 'id_division' => $id_division ), array( '%s', '%d' ) );

		}

		/**
		 * Delete a team
		 */
		public function delete_team( $id_team ) {

			global $wpdb;

			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->teams WHERE id = %d", $id_team ) );

			// Delete from all other tables
			$wpdb->query( $wpdb->prepare(
				"DELETE FROM $wpdb->fixtures
				WHERE id_team_home = %d
				OR id_team_away = %d",
				$id_team, $id_team ) );

			$wpdb->query( $wpdb->prepare(
				"DELETE FROM $wpdb->results
				WHERE id_team_home = %d
				OR id_team_away = %d",
				$id_team, $id_team ) );

			$wpdb->query( $wpdb->prepare(
				"DELETE FROM $wpdb->tables
				WHERE id_team = %d",
				$id_team ) );

		}

		/**
		 * Update a team
		 */
		public function update_team( $id_team, $name, $id_division ) {

			global $wpdb;

			return $wpdb->update(
				$wpdb->teams,
				array(
					'name'	=> $name,
					'id_division' => $id_division
				),
				array( 'id' => $id_team ),
				array( '%s', '%d' ),
				array( '%d')
			);

		}

		/**
		 * Get team information
		 */
		public function get_team( $id_team ) {

			global $wpdb;
			return $wpdb->get_row( $wpdb->prepare( "SELECT name, id_division FROM $wpdb->teams WHERE id = %d", $id_team ), ARRAY_A );

		}
		

		/**
		 * Get team information
		 */
		public function get_team_record( $id_team ) {

			global $wpdb;
			return $wpdb->get_row( $wpdb->prepare(
				"SELECT match_won, match_lost FROM $wpdb->tables WHERE id_team = %d",
				$id_team ), ARRAY_A
			);

		}
		

		/**
		 * Does Division exist?
		 */
		public function is_division_exists( $id_division ) {

			global $wpdb;

            $exist = $wpdb->get_var( $wpdb->prepare(
            	"SELECT COUNT(*) FROM $wpdb->divisions WHERE id = %d",
            $id_division) );
            
            // We didn't find a row
            if ( $exist == 0 )
                return FALSE;    
            else
                return TRUE;

		}
		/**
		 * Count the divisions
		 */
		public function count_divisions() {

			global $wpdb;

			return $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->divisions" );

		}

		/**
		 * Get all the divisions
		 */
		public function get_every_division( $id_season = NULL, $offset = 0, $limit = 10, $order = 'ASC' ) {

			global $wpdb;

			if ( NULL === $id_season ) {

				return $wpdb->get_results( $wpdb->prepare(
					"SELECT a.id, a.name, b.name AS season_name 
					FROM $wpdb->divisions AS a
					LEFT JOIN $wpdb->seasons AS b
					ON a.id_season = b.id
					ORDER BY name $order
					LIMIT %d, %d",
					$offset, $limit ), ARRAY_A
				);

			}
			else {

				return $wpdb->get_results( $wpdb->prepare(
					"SELECT a.id, a.name, b.name AS season_name 
					FROM $wpdb->divisions AS a
					LEFT JOIN $wpdb->seasons AS b
					ON a.id_season = b.id
					WHERE id_season = %d
					ORDER BY name $order
					LIMIT %d, %d",
					$id_season, $offset, $limit ), ARRAY_A
				);

			}

		}


		/**
		 * Add a team
		 */
		public function add_division( $name, $id_season ) {

			global $wpdb;

			return $wpdb->insert( $wpdb->divisions, array( 'name' => $name, 'id_season' => $id_season ), array( '%s', '%d' ) );
		}

		/**
		 * Delete a division
		 */
		public function delete_division( $id_division ) {

			global $wpdb;

			if ( $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->divisions WHERE id = %d", $id_division ) ) ) {

				// Delete from all other tables
				$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->teams WHERE id_division = %d", $id_division ) );

			}
			
		}

		/**
		 * Update a division
		 */
		public function update_division( $id_division, $name, $id_season ) {

			global $wpdb;

			return $wpdb->update(
				$wpdb->divisions,
				array(
					'name'		=> $name,
					'id_season'	=> $id_season
				),
				array( 'id' => $id_division ),
				array( '%s', '%d' ),
				array( '%d')
			);

		}

		/**
		 * Get division information
		 */
		public function get_division( $id_division ) {

			global $wpdb;
			return $wpdb->get_row( $wpdb->prepare(
				"SELECT a.name, a.id_season, b.name as season_name
				FROM $wpdb->divisions AS a
				LEFT JOIN $wpdb->seasons AS b
				ON a.id_season = b.id
				WHERE a.id = %d", $id_division ), ARRAY_A );

		}
		


		/**
		 * Count the seasons
		 */
		public function count_seasons() {

			global $wpdb;

			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->seasons", NULL ) );

		}

		/**
		 * Get all the seasons
		 */
		public function get_every_season( $offset = 0, $limit = 10, $order = 'DESC' ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare( "SELECT id, name, year
				FROM $wpdb->seasons
				ORDER BY year $order, name ASC
				LIMIT %d, %d",
				$offset, $limit ), ARRAY_A
			);

		}

		/**
		 * Get current season
		 */
		public function get_current_season() {

			return (int) get_option( 'fplleague_current_season' );

		}


		/**
		 * Add a season
		 */
		public function add_season( $name, $year ) {

			global $wpdb;

			return $wpdb->insert( $wpdb->seasons, array( 'name' => $name, 'year' => $year ), array( '%s', '%d' ) );

		}

		/**
		 * Delete a season
		 */
		public function delete_season( $id_season ) {

			global $wpdb;

			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->seasons WHERE id = %d", $id_season ) );

			// Delete from all other tables
			$wpdb->query( $wpdb->prepare(
				"DELETE $wpdb->teams, $wpdb->divisions
				FROM $wpdb->teams, $wpdb->divisions
				WHERE $wpdb->teams.id_division = $wpdb->divisions.id AND
				$wpdb->divisions.id_season = %d", $id_season
 			) );

		}

		/**
		 * Update a season
		 */
		public function update_season( $id_season, $name, $year ) {

			global $wpdb;

			return $wpdb->update(
				$wpdb->seasons,
				array(
					'name'	=> $name,
					'year'	=> $year,
				),
				array( 'id' => $id_season ),
				array( '%s', '%d' ),
				array( '%d')
			);

		}

		/**
		 * Get season information
		 */
		public function get_season( $id_season ) {

			global $wpdb;
			return $wpdb->get_row( $wpdb->prepare( "SELECT name FROM $wpdb->seasons WHERE id = %d", $id_season ), ARRAY_A );

		}


		/**
		 * Get message from code
		 */
		public function get_message( $message_code ) {

			global $wpdb;

			if ( $message_code == NULL )
				return false;

			if ( $message = $wpdb->get_row( 
				"SELECT code, type, message FROM $wpdb->messages 
				WHERE code = " . $message_code,
				ARRAY_A
			) ) {

				if ( $message['type'] == 'ok' ) {

					return '<div class="updated"><p>' . $message['message'] . '</p></div>';

				}
				else {

					return '<div class="error"><p>' . $message['message'] . '</p></div>';

				}
			}
			else {

				return false;

			}

		}

		
		/**
		 * Get cup information
		 */
		public function get_cup( $id_cup ) {

			global $wpdb;
			
			return $wpdb->get_row( $wpdb->prepare(
				"SELECT a.name, a.type, a.id_season, b.name as season_name
				FROM $wpdb->cups AS a
				LEFT JOIN $wpdb->seasons AS b
				ON a.id_season = b.id
				WHERE a.id = %d", $id_cup ), ARRAY_A );

		}
		


		/**
		 * Get all the cups
		 */
		public function get_every_cup( $offset = 0, $limit = 10, $order = 'ASC' ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, a.name, a.type, b.name AS season_name 
				FROM $wpdb->cups a
				LEFT JOIN $wpdb->seasons b
				ON a.id_season = b.id
				ORDER BY name $order
				LIMIT %d, %d",
				$offset, $limit ), ARRAY_A
			);

		}

		/**
		 * Add a cup
		 */
		public function add_cup( $name, $id_season, $type ) {

			global $wpdb;

			return $wpdb->insert(
				$wpdb->cups,
				array(
					'name' => $name,
					'id_season' => $id_season,
					'type' => $type
				),
				array( '%s', '%d', '%s' )
			);

		}

		/**
		 * Delete a cup
		 */
		public function delete_cup( $id_cup ) {

			global $wpdb;

			if ( $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->cups WHERE id = %d", $id_cup ) ) ) {

			}
			
		}

		/**
		 * Update a cup
		 */
		public function update_cup( $id_cup, $name, $type, $id_season ) {

			global $wpdb;

			return $wpdb->update(
				$wpdb->cups,
				array(
					'name'		=> $name,
					'type'		=> $type,
					'id_season'	=> $id_season
				),
				array( 'id' => $id_cup ),
				array( '%s', '%s', '%d' ),
				array( '%d')
			);

		}

		/**
		 * Get every cup schedule
		 */
		public function get_every_cup_schedule( $id_cup, $offset, $limit, $order ) {
			
			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, a.name, a.scheduled, b.name AS cup_name, c.name AS season_name
				FROM $wpdb->cup_schedules a
				LEFT JOIN $wpdb->cups b
				ON a.id_cup = b.id
				LEFT JOIN $wpdb->seasons c
				ON b.id_season = c.id
				WHERE id_cup = %d
				ORDER BY a.name $order
				LIMIT %d, %d",
				$id_cup, $offset, $limit ), ARRAY_A
			);

		}

		/**
		 * Add cup schedule
		 */
		public function add_cup_schedule( $id_cup, $name, $scheduled ) {

			global $wpdb;

			return $wpdb->insert(
				$wpdb->cup_schedules,
				array(
					'id_cup' => $id_cup,
					'name' => $name,
					'scheduled' => $scheduled
				),
				array( '%d', '%s', '%s' )
			);

		}


		/**
		 * Count the Schedules
		 */
		public function count_schedules( $id_division ) {

			global $wpdb;

			return (int) $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->schedules
				WHERE id_division = %d",
				$id_division
			) );

		}

		/**
		 * Add schedule
		 */
		public function add_schedule( $id_division, $week, $date ) {

			global $wpdb;

			return $wpdb->insert(
				$wpdb->schedules,
				array(
					'id_division' => $id_division,
					'week' => $week,
					'scheduled' => $date
				),
				array( '%d', '%d', '%s' )
			);

		}

		/**
		 * Get every schedule
		 */
		public function get_every_schedule( $id_division, $offset = 0, $limit = 10, $order = 'ASC' ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, a.week, a.scheduled, b.name AS division_name, c.name AS season_name
				FROM $wpdb->schedules a
				LEFT JOIN $wpdb->divisions b
				ON a.id_division = b.id
				LEFT JOIN $wpdb->seasons c
				ON b.id_season = c.id
				WHERE id_division = %d
				ORDER BY a.week $order
				LIMIT %d, %d",
				$id_division, $offset, $limit ), ARRAY_A
			);

		}

		/**
		 * Delete schedule
		 */
		public function delete_schedule( $id_division ) {

			global $wpdb;

			return $wpdb->query( $wpdb->prepare(
				"DELETE FROM $wpdb->schedules
				WHERE id_division = %d", $id_division
			) );

		}


		/**
		 * Get Division from Schedule
		 */
		public function get_division_from_schedule( $id_schedule ) {

			global $wpdb;

			return (int) $wpdb->get_var( $wpdb->prepare(
				"SELECT id_division FROM $wpdb->schedules
				WHERE id = %d",
				$id_schedule
			) );

		}


		/**
		 * Get every schedule
		 */
		public function update_schedule( $id_division, $schedule = array() ) {

			global $wpdb;

			if ( $this->delete_schedule( $id_division ) ) {

				foreach( $schedule as $week => $date ) {

					$wpdb->insert(
						$wpdb->schedules,
						array(
							'id_division' => $id_division,
							'week' => $week + 1,
							'scheduled' => $date
						),
						array( '%d', '%d', '%s' )
					);

				}

			}

		}

		/**
		 * Get every fixture
		 */
		public function get_every_fixture( $id_schedule, $offset = 0, $limit = 10, $order = 'ASC' ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, b.scheduled, c.name AS team_home_name, d.name AS team_away_name
				FROM $wpdb->fixtures a
				LEFT JOIN $wpdb->schedules b
				ON a.id_schedule = b.id
				LEFT OUTER JOIN $wpdb->teams c
				ON a.id_team_home = c.id
				LEFT OUTER JOIN $wpdb->teams d
				ON a.id_team_away = d.id
				WHERE id_schedule = %d
				ORDER BY c.name $order, a.id
				LIMIT 0, 9999",
				$id_schedule, $offset, $limit )
			);

		}

		/**
		 * Get every fixture
		 */
		public function get_fixture( $id_fixture ) {

			global $wpdb;

			return $wpdb->get_row( $wpdb->prepare(
				"SELECT a.id_team_home, b.name AS team_home_name, a.id_team_away, c.name AS team_away_name, a.scheduled
				FROM $wpdb->fixtures a
				LEFT JOIN $wpdb->teams b
				ON a.id_team_home = b.id
				LEFT JOIN $wpdb->teams c
				ON a.id_team_away = c.id
				WHERE a.id = %d",
				$id_fixture ), ARRAY_A
			);

		}


		/**
		 * Add Fixture
		 */
		public function add_fixture( $id_schedule, $id_team_home, $id_team_away ) {

			global $wpdb;

			return $wpdb->insert(
				$wpdb->fixtures,
				array(
					'id_schedule' => $id_schedule,
					'id_team_home' => $id_team_home,
					'id_team_away' => $id_team_away
				),
				array( '%d', '%d', '%d' )
			);

		}


		/**
		 * Update Fixture
		 */
		public function update_fixture( $id_fixture, $id_team_home, $id_team_away, $scheduled ) {

			global $wpdb;

			return $wpdb->update(
				$wpdb->fixtures,
				array(
					'id_team_home'	=> $id_team_home,
					'id_team_away'	=> $id_team_away,
					'scheduled'		=> $scheduled
				),
				array( 'id' => $id_fixture ),
				array( '%d', '%d', '%s' ),
				array( '%d' )
			);

		}


		/**
		 * Get every team fixture
		 */
		public function get_every_team_fixture( $id_team ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, a.id_team_home, b.name AS team_home_name, a.id_team_away, c.name AS team_away_name,
				d.point_home, d.point_away, a.scheduled, d.played
				FROM $wpdb->fixtures a
				LEFT OUTER JOIN $wpdb->results d
				ON d.id_fixture = a.id
				LEFT JOIN $wpdb->teams b
				ON b.id = a.id_team_home
				LEFT JOIN $wpdb->teams c
				ON c.id = a.id_team_away
				WHERE ( a.id_team_home = %d OR a.id_team_away = %d )
				ORDER BY a.id",
				$id_team, $id_team ), ARRAY_A
			);

		}

		/**
		 * Get every result
		 */
		public function get_every_result( $id_schedule, $offset = 0, $limit = 10, $order = 'ASC' ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, a.id_team_home, b.name AS team_home_name, a.id_team_away, c.name AS team_away_name, d.point_home, d.point_away
				FROM $wpdb->fixtures a
				LEFT OUTER JOIN $wpdb->results d
				ON d.id_fixture = a.id
				LEFT JOIN $wpdb->teams b
				ON b.id = a.id_team_home
				LEFT JOIN $wpdb->teams c
				ON c.id = a.id_team_away
				WHERE a.id_schedule = %d
				ORDER BY b.name $order, a.id
				LIMIT %d, %d",
				$id_schedule, $offset, $limit ), ARRAY_A
			);

		}


		/**
		 * Get every team result
		 */
		public function get_every_team_result( $id_team ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT a.id, a.id_team_home, b.name AS team_home_name, a.id_team_away, c.name AS team_away_name,
				d.point_home, d.point_away, a.scheduled, d.played
				FROM $wpdb->fixtures a
				LEFT OUTER JOIN $wpdb->results d
				ON d.id_fixture = a.id
				LEFT JOIN $wpdb->teams b
				ON b.id = a.id_team_home
				LEFT JOIN $wpdb->teams c
				ON c.id = a.id_team_away
				WHERE d.played IS NOT NULL
				AND ( a.id_team_home = %d
				OR a.id_team_away = %d )
				ORDER BY a.id",
				$id_team, $id_team ), ARRAY_A
			);

		}

		/**
		 * Get every result
		 */
		public function get_fixture_result( $id_fixture ) {

			global $wpdb;

			return $wpdb->get_row( $wpdb->prepare(
				"SELECT a.id, a.id_team_home, b.name AS team_home_name, d.point_home, a.id_team_away, c.name AS team_away_name, d.point_away, e.scheduled, d.played
				FROM $wpdb->fixtures a
				LEFT OUTER JOIN $wpdb->results d
				ON d.id_fixture = a.id
				LEFT JOIN $wpdb->schedules e
				ON a.id_schedule = e.id
				LEFT JOIN $wpdb->teams b
				ON b.id = a.id_team_home
				LEFT JOIN $wpdb->teams c
				ON c.id = a.id_team_away
				WHERE a.id = %d",
				$id_fixture ), ARRAY_A
			);

		}


		/**
		 * Delete result
		 */
		public function delete_fixture_result( $id_fixture ) {

			global $wpdb;

			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->results WHERE id_fixture = %d", $id_fixture ) );

		}


		/**
		 * Update result
		 */
		public function update_result( $id_division, $id_schedule, $id_fixture, $id_team_home, $id_team_away, $point_home, $point_away, $played ) {

			global $wpdb;

			$this->delete_fixture_result( $id_fixture );

			return $wpdb->insert(
				$wpdb->results,
				array(
					'id_division'	=> $id_division,
					'id_schedule'	=> $id_schedule,
					'id_fixture'	=> $id_fixture,
					'id_team_home'	=> $id_team_home,
					'id_team_away'	=> $id_team_away,
					'played'		=> $played,
					'point_home'	=> $point_home,
					'point_away'	=> $point_away
				),
				array( '%d', '%d', '%d', '%d', '%d', '%s', '%d', '%d' )
			);

		}


		/**
		 * Get Tables
		 */
		public function get_table( $id_division ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT b.id, b.name as team_name, a.match_played, a.match_won, a.match_lost,
				a.point_for, a.point_against, a.point_total, a.point_diff
				FROM $wpdb->tables a
				LEFT JOIN $wpdb->teams b
				ON a.id_team = b.id
				WHERE b.id_division = %d
				ORDER BY a.point_total DESC, a.point_diff DESC, a.point_for DESC",
				$id_division), ARRAY_A
			);

		}


		/**
		 * Update Tables
		 */
		public function build_tables() {

			global $wpdb;

			$id_season = get_option( 'fplleague_current_season' );
			$point_win = get_option( 'fplleague_point_win' );
			$point_win = (int) $point_win;

			$divisions = $this->get_every_division( $id_season, 0, 9999 );

			foreach( $divisions as $division ) {

				$wpdb->query( $wpdb->prepare(
					"DELETE FROM $wpdb->tables
					WHERE id_division = %d", $division['id']
				) );

				$id_division = $division['id'];

				$teams = $this->get_every_team( $id_division, 0, 9999, 'ASC' );

				foreach( $teams as $team ) {

					$id_team = $team['id'];
					$match_played = 0;
					$match_won = 0;
					$match_lost = 0;
					$point_for = 0;
					$point_against = 0;
					$point_total = 0;
					$point_diff = 0;

					$results = $this->get_every_team_result( $id_team );

					foreach( $results as $result ) {

						$match_played++;

						if ( $id_team == $result['id_team_home'] ) {

							$point_for += $result['point_home'];
							$point_against += $result['point_away'];

							if ( $result['point_home'] > $result['point_away'] ) {

								$match_won++;

								}
							else {

								$match_lost++;

							}

						}
						else {

							$point_for += $result['point_away'];
							$point_against += $result['point_home'];

							if ( $result['point_away'] > $result['point_home'] ) {

								$match_won++;

							}
							else {

								$match_lost++;

							}
								
						}

					}

					$point_total = $match_won * $point_win;
					$point_diff = $point_for - $point_against;

					$wpdb->insert(
						$wpdb->tables,
						array(
							'id_team'		=> $id_team,
							'match_played'	=> $match_played,
							'match_won'		=> $match_won,
							'match_lost'	=> $match_lost,
							'point_for'		=> $point_for,
							'point_against'	=> $point_against,
							'point_total'	=> $point_total,
							'point_diff'	=> $point_diff,
							'id_division'	=> $id_division,
							'id_season'		=> $id_season
						),
						array( '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d' )
					);

				}

			}

			return true;

		}


		/**
		 * Build PDF
		 */
		public function create_pdf( $id_division ) {

			global $wpdb;

			$division = $this->get_division( $id_division );

			$output =
				'<style type="text/css">' .
				'h1 { text-align: center; font-size: 18px; font-weight: 400; }' .
				'h2 { font-size: 14px; font-weight: 400; }' .
				'th, td { font-size: 12px; }' .
				'th { font-weight: bold; }' .
				'</style>';

			$output .=
				'<page>' .
					'<h1>Louis Brooks (Flegg) Pool League - ' . $division['name'] . '</h1>' .
					'<h2>Latest Results</h2>' .
				'</page>';

			return $output;
		}

		/**
		 * Get all the players
		 */
		public function get_every_player( $offset = 0, $limit = 10 ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT p.id, p.first_name, p.last_name, p.id_player_team, t.name as team_name, d.name as division_name
				FROM $wpdb->players AS p
				LEFT JOIN $wpdb->teams AS t
				ON p.id_player_team = t.id
				LEFT JOIN $wpdb->divisions AS d
				ON t.id_division = d.id
				ORDER BY t.name ASC, p.last_name, p.first_name
				LIMIT %d, %d",
				$offset, $limit ), ARRAY_A
			);

		}

		/**
		 * Add player
		 */
		public function add_player( $first_name, $last_name, $id_player_team ) {

			global $wpdb;

			return $wpdb->insert(
				$wpdb->players,
				array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'id_player_team' => $id_player_team
				),
				array( '%s', '%s', '%d' )
			);

		}

		/**
		 * Delete a player
		 */
		public function delete_player( $id_player ) {

			global $wpdb;

			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->players WHERE id = %d", $id_player ) );

		}

		/**
		 * Update a player
		 */
		public function update_player( $id_player, $first_name, $last_name, $id_player_team ) {

			global $wpdb;

			return $wpdb->update(
				$wpdb->players,
				array(
					'first_name' => $first_name,
					'last_name'	=> $last_name,
					'id_player_team' => $id_player_team
				),
				array( 'id' => $id_player ),
				array( '%s', '%s', '%d' ),
				array( '%d')
			);

		}

		/**
		 * Get player
		 */
		public function get_player( $id_player ) {

			global $wpdb;
			return $wpdb->get_row(
				$wpdb->prepare(
					"SELECT first_name, last_name, id_player_team
					FROM $wpdb->players
					WHERE id = %d",
					$id_player_team
				), ARRAY_A
			);

		}

		/**
		 * Get all the players from a team
		 */
		public function get_players_from_team( $id_team, $offset = 0, $limit = 10 ) {

			global $wpdb;

			$players = $wpdb->get_results( $wpdb->prepare(
				"SELECT p.id, p.first_name, p.last_name, p.id_player_team, t.name as team_name, d.name as division_name
				FROM $wpdb->players AS p
				LEFT JOIN $wpdb->teams AS t
				ON p.id_player_team = t.id
				LEFT JOIN $wpdb->divisions AS d
				ON t.id_division = d.id
				WHERE p.id_player_team = %d
				ORDER BY t.name ASC, p.last_name, p.first_name
				LIMIT %d, %d",
				$id_team, $offset, $limit ), ARRAY_A
			);
			var_dump($players); die();

		}


		/**
		 * Get all the doubles
		 */
		public function get_every_doubles( $offset = 0, $limit = 10 ) {

			global $wpdb;

			return $wpdb->get_results( $wpdb->prepare(
				"SELECT d.id,
				d.id_player_1,
				CONCAT(p1.first_name, ' ', p1.last_name) AS name_player_1,
				d.id_player_2,
				CONCAT(p2.first_name, ' ', p2.last_name) AS name_player_2,
				d.id_team,
				t.name as team_name
				FROM $wpdb->doubles AS d
				LEFT JOIN $wpdb->players p1
				ON p1.id = d.id_player_1
				LEFT JOIN $wpdb->players p2
				ON p2.id = d.id_player_2
				LEFT JOIN $wpdb->teams AS t
				ON d.id_team = t.id
				ORDER BY t.name ASC, name_player_1
				LIMIT %d, %d", $offset, $limit ), ARRAY_A
			);

		}

	}

}