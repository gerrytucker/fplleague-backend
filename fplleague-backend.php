<?php
/*
Plugin Name: Flegg Pool League Backend
Plugin URI: https://github.com/gerrytucker/fplleague-backend
Description: Backend administration for Flegg Pool League.
Version: 13.13.0
Author: Gerry Tucker
Author URI: http://gerrytucker.co.uk/
GitHub Plugin URI: https://github.com/gerrytucker/fplleague-backend
GitHub Branch: develop
Requires WP: 3.8
Requires PHP: 5.3
*/

if ( ! class_exists( 'FPLLeague_Backend' ) ) {

	class FPLLeague_Backend {

		// Variables
		public static $access = '';
		public static $pages = array(
			'fplleague-overview',
			'fplleague-seasons',
			'fplleague-divisions',
			'fplleague-teams',
			'fplleague-schedules',
			'fplleague-results',
			'fplleague-add-season',
			'fplleague-edit-season',
			'fplleague-add-division',
			'fplleague-edit-division',
			'fplleague-add-team',
			'fplleague-edit-team',
			'fplleague-schedule',
			'fplleague-add-schedule',
			'fplleague-edit-schedule',
			'fplleague-add-results',
			'fplleague-edit-results',
			'fplleague-settings',
			'fplleague-cups-overview',
			'fplleague-cups',
			'fplleague-add-cup',
			'fplleague-edit-cup',
			'fplleague-delete-cup',
			'fplleague-players',
			'fplleague-add-player',
			'fplleague-edit-player',
			'fplleague-delete-player',
			'fplleague-doubles',
			'fplleague-add-doubles',
			'fplleague-edit-doubles',
			'fplleague-delete-doubles',
			'fplleague-cup-schedule',
			'fplleague-add-cup-schedule',
			'fplleague-edit-cup-schedule',
			'fplleague-delete-cup-schedule',
		);

		public function __construct() {

			// Define constants
			$this->define_constants();

			require_once FPLLEAGUE_PATH . 'libs/fplleague-tools.php';
			require_once FPLLEAGUE_PATH . 'libs/fplleague-database.php';

			// Load tables
			$this->define_tables();

			if ( is_admin() ) {

				register_activation_hook( __FILE__, array( 'FPLLeague_Backend', 'activate' ) );
				register_uninstall_hook( __FILE__, array( 'FPLLeague_Backend', 'uninstall' ) );

				FPLLeague_Backend::$access = 'administrator';

				// Load the admin controller
				require_once FPLLEAGUE_PATH . 'libs/fplleague-admin.php';

				add_action( 'init', array( 'FPLLeague_Admin', 'add_editor_button' ) );
				add_action( 'admin_init', array( &$this, 'fplleague_admin_init' ) );
				add_action( 'admin_init', array( &$this, 'plugin_check_upgrade' ) );
				add_action( 'admin_print_styles', array( &$this, 'print_admin_styles' ) );
				add_action( 'admin_print_scripts', array( &$this, 'print_admin_scripts' ) );
				add_action( 'admin_menu', array( 'FPLLeague_Admin', 'admin_menu' ) );
				add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );

			}
			else {

				require_once FPLLEAGUE_PATH . 'libs/fplleague-front.php';

				add_action( 'wp_enqueue_styles', array( 'FPLLeague_Front', 'print_front_styles' ) );
				add_action( 'wp_enqueue_scripts', array( 'FPLLeague_Front', 'print_front_scripts' ) );

				add_shortcode( 'fplleague', array( &$this, 'shortcodes_controller' ) );

			}

		}


		/**
		 * Define constants
		 */
		public function define_constants() {

			define( 'FPLLEAGUE_VERSION', '13.9.0' );
			define( 'FPLLEAGUE_DB_VERSION', '4.4.0' );
			define( 'FPLLEAGUE_PATH', plugin_dir_path( __FILE__ ) );

		}

		/**
		 * Define tables
		 */
		public function define_tables() {

			global $wpdb;

			$wpdb->seasons = $wpdb->prefix . 'fplleague_seasons';
			$wpdb->divisions = $wpdb->prefix . 'fplleague_divisions';
			$wpdb->teams = $wpdb->prefix . 'fplleague_teams';
			$wpdb->schedules = $wpdb->prefix . 'fplleague_schedules';
			$wpdb->fixtures = $wpdb->prefix . 'fplleague_fixtures';
			$wpdb->results = $wpdb->prefix . 'fplleague_results';
			$wpdb->tables = $wpdb->prefix . 'fplleague_tables';
			$wpdb->messages = $wpdb->prefix . 'fplleague_messages';
			$wpdb->players = $wpdb->prefix . 'fplleague_players';
			$wpdb->doubles = $wpdb->prefix . 'fplleague_doubles';
			$wpdb->cups = $wpdb->prefix . 'fplleague_cups';
			$wpdb->cup_schedules = $wpdb->prefix . 'fplleague_cup_schedules';
		}

		/**
		 * Initialize Admin
		 */
		public function fplleague_admin_init() {

			// Set capabilities
			$role = get_role( FPLLeague_Backend::$access );
			$role->add_cap( 'manage_fplleague' );
			$role->add_cap( 'fplleague' );

			// Check permissions on every page
			if ( isset( $_GET['page'] ) && in_array( trim( $_GET['page'] ), FPLLeague_Backend::$pages ) ) { // input var okay
				if ( ! current_user_can( 'manage_fplleague' ) )
					wp_die(__('Insufficient permissions to access FPL Pool League!', 'fplleague'));
			}

		}

		/**
		 * Enqueue admin styles
		 */
		public static function print_admin_styles() {

			wp_register_style( 'fplleague_admin_style', plugins_url( 'assets/styles/fplleague-backend.css', __FILE__ ) );
			wp_enqueue_style( 'fplleague_admin_style' );

		}


		/**
		 * Enqueue admin scripts
		 */
		public static function print_admin_scripts() {

			if ( isset( $_GET['page'] ) ) {
				
				if ( ! in_array( trim( $_GET['page'] ), FPLLeague_Backend::$pages ) )
					return;

				wp_register_script( 'fplleague-backend', plugins_url( 'assets/scripts/fplleague-backend.min.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'fplleague-backend' );

			}

		}


		/**
		 * Plugin action links
		 */
		public function plugin_action_links( $links, $file ) {

			static $this_plugin;

			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}

			if ( $file == $this_plugin ) {

				$settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=fplleague-settings">Settings</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
			
		}


		/**
		 * Run on install or upgrade
		 */
		public static function run_install_or_upgrade( $table_name, $sql, $db_version ) {
			global $wpdb;
				
			// Table does not exist, we create it!
			// We use InnoDB and UTF-8 by default
			if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name . "'" ) != $table_name ) {

				$create = 'CREATE TABLE ' . $table_name . ' ( ' . $sql . ' ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;';

				// We use the dbDelta method given by WP!
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta( $create );
			}

		}
			

		public function plugin_check_upgrade() {

			global $wpdb;
				
			// Get the current versions from the database
			$version = get_option( 'fplleague_version' );
			$current_version = isset( $version ) ? $version : 0;
				
			$db_version = get_option( 'fplleague_db_version' );
			$current_db_version = isset( $db_version ) ? $db_version : 0;

			// We're already using the latest version
			// so we're leaving!
			if ($current_version == FPLLEAGUE_VERSION && $current_db_version == FPLLEAGUE_DB_VERSION)
				return;

			if (version_compare($current_db_version, '3.1.0', '<')) {

				$wpdb->query("ALTER TABLE $wpdb->fixtures ADD scheduled date NOT NULL DEFAULT '0000-00-00' AFTER id_schedule;");
				$wpdb->query(
					"UPDATE $wpdb->fixtures a
					INNER JOIN $wpdb->schedules b
					ON a.id_schedule = b.id
					SET a.scheduled = b.scheduled"
				);

			}

			if (version_compare($current_db_version, '3.2.0', '<')) {

				$wpdb->query("ALTER TABLE $wpdb->results ADD id_schedule smallint(5) NOT NULL DEFAULT 0 AFTER id_fixture;");
				$wpdb->query(
					"UPDATE $wpdb->results a
					INNER JOIN $wpdb->fixtures b
					ON a.id_fixture = b.id
					SET a.id_schedule = b.id_schedule"
				);

			}

			if (version_compare($current_db_version, '3.3.0', '<')) {

				$wpdb->query("ALTER TABLE $wpdb->fixtures ADD id_division smallint(5) NOT NULL DEFAULT 0 AFTER id_team_away");
				$wpdb->query(
					"UPDATE $wpdb->fixtures a
					INNER JOIN $wpdb->schedules b
					ON a.id_schedule = b.id
					SET a.scheduled = b.scheduled"
				);

				$wpdb->query("ALTER TABLE $wpdb->results ADD id_division smallint(5) NOT NULL DEFAULT 0 AFTER id_fixture;");
				$wpdb->query(
					"UPDATE $wpdb->results a
					INNER JOIN $wpdb->fixtures b
					ON a.id_fixture = b.id
					SET a.id_division = b.id_division"
				);

				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 120, 'err', 'Home Team and Away Team cannot be the same.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 121, 'err', 'Total Points must be equal to 15.');");

			}

			if (version_compare($current_db_version, '3.4.2', '<')) {

				// Player table
				$sql = "id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
							name varchar(100) NOT NULL DEFAULT '',
							id_player_team smallint(4) unsigned NOT NULL DEFAULT '0',
							PRIMARY KEY (id),
							KEY id_player_team (id_player_team)";
				$this->run_install_or_upgrade($wpdb->players, $sql, '3.4.2');

				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 122, 'ok', 'New player added.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 123, 'ok', 'Player updated.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 124, 'ok', 'Player deleted.');");

			}

			if (version_compare($current_db_version, '3.5.0', '<')) {
				
				$wpdb->query("ALTER TABLE $wpdb->players DROP name;");
				$wpdb->query("ALTER TABLE $wpdb->players ADD first_name VARCHAR(100) NOT NULL DEFAULT '' AFTER id;");
				$wpdb->query("ALTER TABLE $wpdb->players ADD last_name VARCHAR(100) NOT NULL DEFAULT '' AFTER first_name;");

			}

			if (version_compare($current_db_version, '3.5.1', '<')) {
                
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 125, 'ok', 'Player(s) deleted.');");

			}

			if (version_compare($current_db_version, '4.0.0', '<')) {
                
				// Cup Competitions table
				$sql = "id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
						name VARCHAR(255) NOT NULL DEFAULT '',
						id_season SMALLINT(5) NOT NULL DEFAULT '0',
						type TEXT(1) NOT NULL DEFAULT '',
						PRIMARY KEY (id),
						KEY id_season (id_season),
						UNIQUE KEY name (name)";
				FPLLeague_Backend::run_install_or_upgrade( $wpdb->cups, $sql, '4.0.0' );

			}

			if (version_compare($current_db_version, '4.0.1', '<')) {
                
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 130, 'ok', 'New cup competition added.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 131, 'ok', 'Cup competition updated.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 132, 'ok', 'Cup competition deleted.');");

			}

			if (version_compare($current_db_version, '4.1.0', '<')) {
                
				// Cup Schedule table
				$sql = "name varchar(255) NOT NULL DEFAULT '',
						id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
						id_cup smallint(5) unsigned NOT NULL DEFAULT '0',
						PRIMARY KEY (id),
						KEY id_cup (id_cup)";
				FPLLeague_Backend::run_install_or_upgrade( $wpdb->cup_schedules, $sql, $db_version );
				
			}

			if (version_compare($current_db_version, '4.2.0', '<')) {

				$wpdb->query("ALTER TABLE $wpdb->cup_schedules ADD scheduled DATE NOT NULL DEFAULT '0000-00-00' AFTER name");
				
			}
			
			
			if (version_compare($current_db_version, '4.2.1', '<')) {
                
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 140, 'ok', 'New cup schedule added.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 141, 'ok', 'Cup schedule updated.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 142, 'ok', 'Cup schedule deleted.');");

			}


			if (version_compare($current_db_version, '4.3.0', '<')) {

				// Doubles table
				$sql = "id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
							name_player_1 varchar(100) NOT NULL DEFAULT '',
							name_player_2 varchar(100) NOT NULL DEFAULT '',
							id_team smallint(4) unsigned NOT NULL DEFAULT '0',
							PRIMARY KEY (id),
							KEY id_team (id_team)";
				$this->run_install_or_upgrade($wpdb->doubles, $sql, '4.3.0');

				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 150, 'ok', 'New doubles team added.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 151, 'ok', 'Doubles team updated.');");
				$wpdb->query("INSERT INTO $wpdb->messages VALUES(0, 152, 'ok', 'Doubles team deleted.');");

			}

			if (version_compare($current_db_version, '4.4.0', '<')) {

				$wpdb->query("ALTER TABLE $wpdb->doubles DROP name_player_1;");
				$wpdb->query("ALTER TABLE $wpdb->doubles DROP name_player_2;");
				$wpdb->query("ALTER TABLE $wpdb->doubles ADD id_player_1 SMALLINT(6) NOT NULL DEFAULT 0 AFTER id;");
				$wpdb->query("ALTER TABLE $wpdb->doubles ADD id_player_2 SMALLINT(6) NOT NULL DEFAULT 0 AFTER id_player_1;");

			}
			
			
			// Basic actions to do everytime we upgrade FPLLeague...
			if ( $current_version < FPLLEAGUE_VERSION ) {
				update_option( 'fplleague_version', FPLLEAGUE_VERSION);
				update_option( 'fplleague_db_version', FPLLEAGUE_DB_VERSION);
			}

		}

		/**
		 * Activate plugin
		 */
		public static function activate() {

			global $wpdb;
			$db_version = FPLLEAGUE_DB_VERSION;

			// Seasons table
			$sql = "id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL DEFAULT '',
					year smallint(5) unsigned NOT NULL DEFAULT 0,
					PRIMARY KEY (id),
					UNIQUE KEY name (name)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->seasons, $sql, $db_version );

			// Divisions table
			$sql = "id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL DEFAULT '',
					id_season SMALLINT(5) NOT NULL DEFAULT '0',
					PRIMARY KEY (id),
					KEY id_season (id_season),
					UNIQUE KEY name (name)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->divisions, $sql, $db_version );

			// Teams table
			$sql = "id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL DEFAULT '',
					id_division SMALLINT(5) NOT NULL DEFAULT '0',
					PRIMARY KEY (id),
					UNIQUE KEY name (name)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->teams, $sql, $db_version );

			// Schedule table
			$sql = "week tinyint(3) unsigned NOT NULL DEFAULT '0',
					scheduled date NOT NULL DEFAULT '0000-00-00',
					id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
					id_division smallint(5) unsigned NOT NULL DEFAULT '0',
					PRIMARY KEY (id),
					KEY week (week),
					KEY id_division (id_division)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->schedules, $sql, $db_version );

			// Fixtures table
			$sql = "id mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
					id_team_home smallint(5) unsigned DEFAULT NULL,
					id_team_away smallint(5) unsigned DEFAULT NULL,
					id_schedule smallint(5) unsigned DEFAULT NULL,
					PRIMARY KEY (id),
					KEY id_team_away (id_team_away),
					KEY id_team_home (id_team_home)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->fixtures, $sql, $db_version );

			// Results table
			$sql = "id mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
					id_team_home smallint(5) unsigned DEFAULT NULL,
					id_team_away smallint(5) unsigned DEFAULT NULL,
					played datetime DEFAULT NULL,
					id_fixture smallint(5) unsigned DEFAULT NULL,
					point_home tinyint(1) unsigned DEFAULT NULL,
					point_away tinyint(1) unsigned DEFAULT NULL,
					PRIMARY KEY (id),
					KEY id_fixture (id_fixture),
					KEY id_team_away (id_team_away),
					KEY id_team_home (id_team_home)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->results, $sql, $db_version );

			// Messages
			$sql = "id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
					code smallint(5) unsigned NOT NULL DEFAULT 0,
					type text(1) NOT NULL,
					message varchar(255) NOT NULL DEFAULT '',
					PRIMARY KEY (id),
					UNIQUE KEY code (code)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->messages, $sql, $db_version );

			$messages = array (
				array( 'code' => 101, 'type' => 'ok', 'message' => 'New season added.' ),
				array( 'code' => 102, 'type' => 'ok', 'message' => 'Season updated.' ),
				array( 'code' => 103, 'type' => 'ok', 'message' => 'Season deleted.' ),
				array( 'code' => 104, 'type' => 'ok', 'message' => 'New division added.' ),
				array( 'code' => 105, 'type' => 'ok', 'message' => 'Division updated.' ),
				array( 'code' => 106, 'type' => 'ok', 'message' => 'Division deleted.' ),
				array( 'code' => 107, 'type' => 'ok', 'message' => 'New team added.' ),
				array( 'code' => 108, 'type' => 'ok', 'message' => 'Team updated.' ),
				array( 'code' => 109, 'type' => 'ok', 'message' => 'Team deleted.' ),
				array( 'code' => 110, 'type' => 'ok', 'message' => 'New fixture added.' ),
				array( 'code' => 111, 'type' => 'ok', 'message' => 'Fixture updated.' ),
				array( 'code' => 112, 'type' => 'ok', 'message' => 'Fixture deleted.' ),
				array( 'code' => 113, 'type' => 'ok', 'message' => 'Settings updated.' ),
				array( 'code' => 114, 'type' => 'ok', 'message' => 'New schedule added.' ),
				array( 'code' => 115, 'type' => 'ok', 'message' => 'Schedule updated.' ),
				array( 'code' => 116, 'type' => 'ok', 'message' => 'Schedule deleted.' ),
				array( 'code' => 117, 'type' => 'ok', 'message' => 'Result updated.' ),
				array( 'code' => 118, 'type' => 'ok', 'message' => 'Tables rebuilt.' ),
			);

			foreach ( $messages as $message ) {
				$wpdb->insert(
					$wpdb->messages,
					array(
						'code' 		=> $message['code'],
						'type' 		=> $message['type'],
						'message' 	=> $message['message']
					),
					array('%d', '%s', '%s')
				);
			}

			// League table
			$sql = "id smallint(5) NOT NULL AUTO_INCREMENT,
					id_team smallint(5) DEFAULT NULL,
					match_played smallint(5) DEFAULT NULL,
					match_won smallint(5) DEFAULT NULL,
					match_lost smallint(5) DEFAULT NULL,
					point_for smallint(5) DEFAULT NULL,
					point_against smallint(5) DEFAULT NULL,
					point_total smallint(5) DEFAULT NULL,
					point_diff smallint(5) DEFAULT NULL,
					id_division smallint(5) DEFAULT NULL,
					id_season smallint(5) DEFAULT NULL,
					PRIMARY KEY (id),
					KEY id_team (id_team),
					KEY id_division (id_division),
					KEY id_season (id_season),
					KEY point_total (point_total)";
			FPLLeague_Backend::run_install_or_upgrade( $wpdb->tables, $sql, $db_version );


			// Save versions
			add_option( 'fplleague_version', FPLLEAGUE_VERSION );
			add_option( 'fplleague_db_version', $db_version );
			add_option( 'fplleague_do_activation_redirect', TRUE );
		}


		/**
		 * Uninstall plugin
		 */
		public static function uninstall() {

			global $wpdb;

			$tables = array(
				$wpdb->seasons,
				$wpdb->divisions,
				$wpdb->teams,
				$wpdb->schedules,
				$wpdb->fixtures,
				$wpdb->results,
				$wpdb->messages
			);

			foreach ( $tables as $table ) {

				$wpdb->query( 'DROP TABLE IF EXISTS ' . $table . ';' );

			}

			// Delete versions in the options table
			delete_option( 'fplleague_version' );
			delete_option( 'fplleague_db_version' );

		}


		/**
		 * Shortcodes controller
		 */
		public function shortcodes_controller( $attributes ) {

			extract( shortcode_atts(
				array(
					'id'	=> 1,
					'type'	=> 'table',
				),
				$attributes
			) );

			$id = (int) $id;

			$front = new FPLLeague_Front;

			// Display division table
			if ( $type == 'table' ) {

				return $front->get_division_table( $id );

			}
			else if ( $type == 'results' ) {

				return $front->get_latest_results( $id );

			}
			else if ( $type == 'teams' ) {

				if ( $id = 999999 ) {

					return $front->get_all_teams_by_division();

				}
				else {

					return $front->get_teams_by_division( $id );

				}
			}
			else if ( $type == 'team' ) {

				return $front->get_team( $id );

			}
			else if ( $type == 'division' ) {

				return $front->get_division( $id );

			}


		}

	}

	$fplleaguebackend = new FPLLeague_Backend;

}