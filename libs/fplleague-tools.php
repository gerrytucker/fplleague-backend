<?php

if ( ! class_exists( 'FPLLeague_Tools' ) ) {

	class FPLLeague_Tools {

		public function __construct() {}


		public static function show_message( $message_code = NULL ) {

			global $wpdb;

			if ( $message_code == NULL )
				return;

			$db = new FPLLeague_Database;
			if ( $message = $db->get_message( $message_code ) ) {

				echo $message;

			}

		}

	}

}
