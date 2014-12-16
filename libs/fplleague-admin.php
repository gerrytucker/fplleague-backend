<?php

if (! class_exists('FPLLeague_Admin')) {

	/**
	 * FPL League Admin System
	 */
	class FPLLeague_Admin {

		private $overview_about_key = 'about';
		private $overview_seasons_key = 'seasons';
		private $overview_divisions_key = 'divisions';
		private $overview_teams_key = 'teams';
		private $overview_settings_key = 'settings';
		private $overview_tabs_settings = array(
			'about' => 'About',
			'seasons' => 'Seasons',
			'divisions' => 'Divisions',
			'teams' => 'Teams',
			'players' => 'Players',
			'settings' => 'Settings'
		);
		private $overview_tabs_pages = array(
			'about' => 'incs/admin/overview-about.php',
			'seasons' => 'incs/admin/overview-seasons.php',
			'divisions' => 'incs/admin/overview-divisions.php',
			'teams' => 'incs/admin/overview-teams.php',
			'players' => 'incs/admin/overview-players.php',
			'settings' => 'incs/admin/overview-settings.php'
		);

		/**
		 * Constructor
		 */
		public function __construct() {}

		/**
		 * Admin pages handler
		 */
		public function admin_page() {

			$db = new FPLLeague_Database;
			$ctl = new FPLLeague_Admin;

			$_POST = stripslashes_deep($_POST);
			$_GET = stripslashes_deep($_GET);

			switch (trim($_GET['page'])) {

				case 'fplleague-seasons':
					require_once FPLLEAGUE_PATH . 'incs/admin/seasons.php';
					break;  
				case 'fplleague-divisions':
					require_once FPLLEAGUE_PATH . 'incs/admin/divisions.php';
					break;  
				case 'fplleague-teams':
					require_once FPLLEAGUE_PATH . 'incs/admin/teams.php';
					break;  
				case 'fplleague-settings' :
					require_once FPLLEAGUE_PATH . 'incs/admin/settings.php';
					break;  
				case 'fplleague-add-season' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-season.php';
					break;  
				case 'fplleague-edit-season' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-season.php';
					break;  
				case 'fplleague-delete-season' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-season.php';
					break;  
				case 'fplleague-add-division' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-division.php';
					break;  
				case 'fplleague-edit-division' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-division.php';
					break;  
				case 'fplleague-delete-division' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-division.php';
					break;  
				case 'fplleague-add-team' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-team.php';
					break;  
				case 'fplleague-edit-team' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-team.php';
					break;  
				case 'fplleague-delete-team' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-team.php';
					break;  
				case 'fplleague-players' :
					require_once FPLLEAGUE_PATH . 'incs/admin/players.php';
					break;  
				case 'fplleague-add-player' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-player.php';
					break;  
				case 'fplleague-edit-player' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-player.php';
					break;  
				case 'fplleague-delete-player' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-player.php';
					break;  
				case 'fplleague-doubles' :
					require_once FPLLEAGUE_PATH . 'incs/admin/doubles.php';
					break;  
				case 'fplleague-add-doubles' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-doubles.php';
					break;  
				case 'fplleague-edit-doubles' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-doubles.php';
					break;  
				case 'fplleague-delete-doubles' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-doubles.php';
					break;  
				case 'fplleague-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/schedule.php';
					break;  
				case 'fplleague-add-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-schedule.php';
					break;  
				case 'fplleague-edit-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-schedule.php';
					break;  
				case 'fplleague-delete-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-schedule.php';
					break;  
				case 'fplleague-fixtures' :
					require_once FPLLEAGUE_PATH . 'incs/admin/fixtures.php';
					break;  
				case 'fplleague-add-fixture' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-fixture.php';
					break;  
				case 'fplleague-edit-fixture' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-fixture.php';
					break;  
				case 'fplleague-delete-fixture' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-fixture.php';
					break;  
				case 'fplleague-results' :
					require_once FPLLEAGUE_PATH . 'incs/admin/results.php';
					break;  
				case 'fplleague-add-result' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-result.php';
					break;  
				case 'fplleague-edit-result' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-result.php';
					break;  
				case 'fplleague-delete-result' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-result.php';
					break;  
				case 'fplleague-rebuild-tables' :
					require_once FPLLEAGUE_PATH . 'incs/admin/rebuild-tables.php';
					break;  
				case 'fplleague-overview-about' :
					require_once FPLLEAGUE_PATH . 'incs/admin/overview-about.php';
					break;  
				case 'fplleague-overview-seasons' :
					require_once FPLLEAGUE_PATH . 'incs/admin/overview-seasons.php';
					break;  
				case 'fplleague-cups':
					require_once FPLLEAGUE_PATH . 'incs/admin/cups.php';
					break;  
				case 'fplleague-add-cup':
					require_once FPLLEAGUE_PATH . 'incs/admin/add-cup.php';
					break;  
				case 'fplleague-edit-cup':
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-cup.php';
					break;  
				case 'fplleague-delete-cup':
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-cup.php';
					break;  
				case 'fplleague-players':
					require_once FPLLEAGUE_PATH . 'incs/admin/players.php';
					break;  
				case 'fplleague-add-player':
					require_once FPLLEAGUE_PATH . 'incs/admin/add-player.php';
					break;  
				case 'fplleague-edit-player':
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-player.php';
					break;  
				case 'fplleague-delete-player':
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-player.php';
					break;  
				case 'fplleague-cup-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/cup-schedule.php';
					break;  
				case 'fplleague-add-cup-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/add-cup-schedule.php';
					break;  
				case 'fplleague-edit-cup-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/edit-cup-schedule.php';
					break;  
				case 'fplleague-delete-cup-schedule' :
					require_once FPLLEAGUE_PATH . 'incs/admin/delete-cup-schedule.php';
					break;  
				case 'fplleague-overview' :
				default :
					require_once FPLLEAGUE_PATH . 'incs/admin/overview.php';
					break;
			}

		}

		public function overview_tabs_page() {

			$tab = isset($_GET['tab']) ? $_GET['tab'] : $this->overview_about_key;
	?>
			<div class="wrap">
				<h2>Flegg Pool League - An Overview</h2>
				<?php
					$this->overview_tabs();
					include FPLLEAGUE_PATH . $this->overview_tabs_pages[$tab];
				?>
			</div>
	<?php
		}

		public function overview_tabs() {

			$current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->overview_about_key;

			echo '<h2 class="nav-tab-wrapper">';
			foreach ($this->overview_tabs_settings as $tab_key => $tab_caption) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=fplleague-overview&tab=' . $tab_key . '">' . $tab_caption . '</a>';  
			}
			echo '</h2>';

		}

		/**
		 * Create admin menus
		 */
		public static function admin_menu() {

			$instance = new FPLLeague_Admin;
			$parent = 'fplleague-overview';

			if (function_exists('add_menu_page')) {

				add_menu_page(
					'Overview',
					'Pool League',
					FPLLeague_Backend::$access,
					$parent,
					array($instance, 'overview_tabs_page'),
					'none',
					8
			 );

			}

			if (function_exists('add_submenu_page')) {

				// Overview About
				add_submenu_page(
					NULL,
					'About',
					'About',
					FPLLeague_Backend::$access,
					'fplleague-overview-about',
					array($instance, 'overview_tabs_page')
				);

				// Overview Seasons
				add_submenu_page(
					NULL,
					'Seasons',
					'Seasons',
					FPLLeague_Backend::$access,
					'fplleague-overview-seasons',
					array($instance, 'overview_tabs_page')
				);

				// Seasons
				add_submenu_page(
					$parent,
					'Seasons',
					'Seasons',
					FPLLeague_Backend::$access,
					'fplleague-seasons',
					array($instance, 'admin_page')
				);

				// Divisions
				add_submenu_page(
					$parent,
					'Divisions',
					'Divisions',
					FPLLeague_Backend::$access,
					'fplleague-divisions',
					array($instance, 'admin_page')
				);

				// Teams
				add_submenu_page(
					$parent,
					'Teams',
					'Teams',
					FPLLeague_Backend::$access,
					'fplleague-teams',
					array($instance, 'admin_page')
				);

				// Players
				add_submenu_page(
					$parent,
					'Players',
					'Players',
					FPLLeague_Backend::$access,
					'fplleague-players',
					array($instance, 'admin_page')
				);

				// Add Player
				add_submenu_page(
					NULL,
					'Add Player',
					'Add Player',
					FPLLeague_Backend::$access,
					'fplleague-add-player',
					array($instance, 'admin_page')
				);

				// Edit Player
				add_submenu_page(
					NULL,
					'Edit Player',
					'Edit Player',
					FPLLeague_Backend::$access,
					'fplleague-edit-player',
					array($instance, 'admin_page')
				);

				// Delete Player
				add_submenu_page(
					NULL,
					'Delete Player',
					'Delete Player',
					FPLLeague_Backend::$access,
					'fplleague-delete-player',
					array($instance, 'admin_page')
				);

				/**
				 * Players
				 */
				add_submenu_page(
					$parent,
					'Doubles',
					'Doubles',
					FPLLeague_Backend::$access,
					'fplleague-doubles',
					array($instance, 'admin_page')
				);

				// Add Doubles
				add_submenu_page(
					NULL,
					'Add Doubles',
					'Add Doubles',
					FPLLeague_Backend::$access,
					'fplleague-add-doubles',
					array($instance, 'admin_page')
				);

				// Edit Doubles
				add_submenu_page(
					NULL,
					'Edit Doubles',
					'Edit Doubles',
					FPLLeague_Backend::$access,
					'fplleague-edit-doubles',
					array($instance, 'admin_page')
				);

				// Delete Doubles
				add_submenu_page(
					NULL,
					'Delete Doubles',
					'Delete Doubles',
					FPLLeague_Backend::$access,
					'fplleague-delete-doubles',
					array($instance, 'admin_page')
				);

				/**
				 * Cup Competitions
				 */
				add_submenu_page(
					$parent,
					'Cup Competitions',
					'Cup Competitions',
					FPLLeague_Backend::$access,
					'fplleague-cups',
					array($instance, 'admin_page')
				);

				// Add Cup Competition
				add_submenu_page(
					NULL,
					'Add Cup Competition',
					'Add Cup Competition',
					FPLLeague_Backend::$access,
					'fplleague-add-cup',
					array($instance, 'admin_page')
				);

				// Edit Cup Competition
				add_submenu_page(
					NULL,
					'Edit Cup Competition',
					'Edit Cup Competition',
					FPLLeague_Backend::$access,
					'fplleague-edit-cup',
					array($instance, 'admin_page')
				);

				// Delete Cup Competition
				add_submenu_page(
					NULL,
					'Delete Cup Competition',
					'Delete Cup Competition',
					FPLLeague_Backend::$access,
					'fplleague-delete-cup',
					array($instance, 'admin_page')
				);

				// Schedule
				add_submenu_page(
					NULL,
					'Schedule',
					'Schedule',
					FPLLeague_Backend::$access,
					'fplleague-schedule',
					array($instance, 'admin_page')
				);

				// Results
				add_submenu_page(
					NULL,
					'Results',
					'Results',
					FPLLeague_Backend::$access,
					'fplleague-results',
					array($instance, 'admin_page')
				);

				// Settings
				add_submenu_page(
					$parent,
					'Settings',
					'Settings',
					FPLLeague_Backend::$access,
					'fplleague-settings',
					array($instance, 'admin_page')
				);

				// Settings
				add_options_page(
					'Pool League',
					'Pool League',
					FPLLeague_Backend::$access,
					'fplleague-settings',
					array($instance, 'admin_page')
				);

				// Add Season
				add_submenu_page(
					NULL,
					'Add New Season',
					'Add New Season',
					FPLLeague_Backend::$access,
					'fplleague-add-season',
					array($instance, 'admin_page')
				);

				// Edit Division
				add_submenu_page(
					NULL,
					'Edit Season',
					'Edit Season',
					FPLLeague_Backend::$access,
					'fplleague-edit-season',
					array($instance, 'admin_page')
				);

				// Delete Division
				add_submenu_page(
					NULL,
					'Delete Season',
					'Delete Season',
					FPLLeague_Backend::$access,
					'fplleague-delete-season',
					array($instance, 'admin_page')
				);

				// Add Division
				add_submenu_page(
					NULL,
					'Add New Division',
					'Add New Division',
					FPLLeague_Backend::$access,
					'fplleague-add-division',
					array($instance, 'admin_page')
				);

				// Edit Division
				add_submenu_page(
					NULL,
					'Edit Division',
					'Edit Division',
					FPLLeague_Backend::$access,
					'fplleague-edit-division',
					array($instance, 'admin_page')
				);

				// Delete Division
				add_submenu_page(
					NULL,
					'Delete Division',
					'Delete Division',
					FPLLeague_Backend::$access,
					'fplleague-delete-division',
					array($instance, 'admin_page')
				);

				// Add Team
				add_submenu_page(
					NULL,
					'Add New Team',
					'Add New Team',
					FPLLeague_Backend::$access,
					'fplleague-add-team',
					array($instance, 'admin_page')
				);

				// Edit Team
				add_submenu_page(
					NULL,
					'Edit Team',
					'Edit Team',
					FPLLeague_Backend::$access,
					'fplleague-edit-team',
					array($instance, 'admin_page')
				);

				// Delete Team
				add_submenu_page(
					NULL,
					'Delete Team',
					'Delete Team',
					FPLLeague_Backend::$access,
					'fplleague-delete-team',
					array($instance, 'admin_page')
				);

				// Add Player
				add_submenu_page(
					NULL,
					'Add New Player',
					'Add New Player',
					FPLLeague_Backend::$access,
					'fplleague-add-player',
					array($instance, 'admin_page')
				);

				// Edit Player
				add_submenu_page(
					NULL,
					'Edit Player',
					'Edit Player',
					FPLLeague_Backend::$access,
					'fplleague-edit-player',
					array($instance, 'admin_page')
				);

				// Delete Player
				add_submenu_page(
					NULL,
					'Delete Player',
					'Delete Player',
					FPLLeague_Backend::$access,
					'fplleague-delete-player',
					array($instance, 'admin_page')
				);

				// Schedule
				add_submenu_page(
					NULL,
					'Schedule',
					'Schedule',
					FPLLeague_Backend::$access,
					'fplleague-schedule',
					array($instance, 'admin_page')
				);

				// Add Schedule
				add_submenu_page(
					NULL,
					'Add New Schedule',
					'Add New Schedule',
					FPLLeague_Backend::$access,
					'fplleague-add-schedule',
					array($instance, 'admin_page')
				);

				// Edit Schedule
				add_submenu_page(
					NULL,
					'Edit Schedule',
					'Edit Schedule',
					FPLLeague_Backend::$access,
					'fplleague-edit-schedule',
					array($instance, 'admin_page')
				);

				// Delete Schedule
				add_submenu_page(
					NULL,
					'Delete Schedule',
					'Delete Schedule',
					FPLLeague_Backend::$access,
					'fplleague-delete-schedule',
					array($instance, 'admin_page')
				);

				// Fixtures
				add_submenu_page(
					NULL,
					'Fixtures',
					'Fixtures',
					FPLLeague_Backend::$access,
					'fplleague-fixtures',
					array($instance, 'admin_page')
				);

				// Add Fixture
				add_submenu_page(
					NULL,
					'Add New Fixture',
					'Add New Fixture',
					FPLLeague_Backend::$access,
					'fplleague-add-fixture',
					array($instance, 'admin_page')
				);

				// Edit Fixture
				add_submenu_page(
					NULL,
					'Edit Fixture',
					'Edit Fixture',
					FPLLeague_Backend::$access,
					'fplleague-edit-fixture',
					array($instance, 'admin_page')
				);

				// Delete Fixture
				add_submenu_page(
					NULL,
					'Delete Fixture',
					'Delete Fixture',
					FPLLeague_Backend::$access,
					'fplleague-delete-fixture',
					array($instance, 'admin_page')
				);

				// Results
				add_submenu_page(
					NULL,
					'Results',
					'Results',
					FPLLeague_Backend::$access,
					'fplleague-results',
					array($instance, 'admin_page')
				);

				// Add Result
				add_submenu_page(
					NULL,
					'Add New Result',
					'Add New Result',
					FPLLeague_Backend::$access,
					'fplleague-add-result',
					array($instance, 'admin_page')
				);

				// Edit Result
				add_submenu_page(
					NULL,
					'Edit Result',
					'Edit Result',
					FPLLeague_Backend::$access,
					'fplleague-edit-result',
					array($instance, 'admin_page')
				);

				// Delete Result
				add_submenu_page(
					NULL,
					'Delete Result',
					'Delete Result',
					FPLLeague_Backend::$access,
					'fplleague-delete-result',
					array($instance, 'admin_page')
				);

				// Rebuild Tables
				add_submenu_page(
					NULL,
					'Rebuid Tables',
					'Rebuild Tables',
					FPLLeague_Backend::$access,
					'fplleague-rebuild-tables',
					array($instance, 'admin_page')
				);

				// Cup Schedule
				add_submenu_page(
					NULL,
					'Cup Schedule',
					'Cup Schedule',
					FPLLeague_Backend::$access,
					'fplleague-cup-schedule',
					array($instance, 'admin_page')
				);

				// Add Cup Schedule
				add_submenu_page(
					NULL,
					'Add Cup Schedule',
					'Add Cup Schedule',
					FPLLeague_Backend::$access,
					'fplleague-add-cup-schedule',
					array($instance, 'admin_page')
				);

				// Edit Cup Schedule
				add_submenu_page(
					NULL,
					'Edit Cup Schedule',
					'Edit Cup Schedule',
					FPLLeague_Backend::$access,
					'fplleague-edit-cup-schedule',
					array($instance, 'admin_page')
				);

				// Delete Cup Schedule
				add_submenu_page(
					NULL,
					'Delete Cup Schedule',
					'Delete Cup Schedule',
					FPLLeague_Backend::$access,
					'fplleague-delete-cup-schedule',
					array($instance, 'admin_page')
				);

			}

		}

		/**
		 * Add TinyMCE Button
		 */
		public static function add_editor_button() {

			// Don't bother doing this stuff if the current user lacks permissions
			if (! current_user_can('edit_posts') && ! current_user_can('edit_pages')) return;

			// Check for FPLLeague capability
			if (! current_user_can('manage_fplleague')) return;

			// Add only in Rich Editor mode
			if (get_user_option('rich_editing') == 'true') {

				add_filter('mce_external_plugins', array('FPLLeague_Admin', 'add_editor_plugin'));
				add_filter('mce_buttons', array('FPLLeague_Admin', 'register_editor_button'));

			}

		}

		/**
		 * Add TinyMCE plugin
		 */
		public static function add_editor_plugin($plugin_array) {

			$plugin_array['FPLLeague'] = plugins_url('assets/js/tinymce/editor_plugin.js', dirname(__FILE__));
			return $plugin_array;

		}

		/**
		 * Register TinyMCE button
		 */
		public static function register_editor_button($buttons) {

					array_push($buttons, 'separator', 'FPLLeague');
					return $buttons;

		}

		public static function fplleague_admin_get_players_from_team() {
			$id_team = $_POST['id_team'];
			$db = new FPLLeague_Database;
			$players = $db->get_players_from_team( $id_team );
			header('Content-Type: application/json');
			echo json_encode( $players );
			die();

		}

		public static function fplleague_admin_get_players_from_team_js() {
?>	
		
			<script type="text/javascript">

				(function ($) {
					'use strict';

					var FPLLeague_Backend = {

						doubles: function () {

							$('#doubles_select_team').change(function () {
								var
									id_team = $('option:selected', this).val(),
									data = {
										'action': 'fplleague_admin_get_players_from_team_action',
										'id_team': id_team
									};

								$.post(ajaxurl, data, function (resp) {
									console.log(resp);
									var html;
									$.each(resp, function (k, v) {
										html +=
											'<tr>' +
												'<td>' +
													'<input type="checkbox">' +
												'</td>' +
											'</tr>';
									});
									$('[data-scope="players-select-table"] tbody').html(html);
									$('[data-scope="players-select"], [data-scope="players-select-table"]').show();
								});

							});
						}

					};

					jQuery(document).ready(function ($) {

						FPLLeague_Backend.doubles();

					});

				})(jQuery);

</script>
<?php		
		}
		
	}
	
}