<?php

class WLD_College_Import {


	public static array $ready_csv_array_fields = [];
	public static array $ready_csv_array_columns = [];
	public static array $ready_table_direction = [];
	public static array $ready_db_tables = [];
	public static $db_id_values_array;



	/** Function get table array and will save on dataBase
	 *
	 * @param $table
	 */
	public static function set_table( $table ): void {
		while ( ( $line1 = fgetcsv( $table ) ) !== false ) {
			if ( $line1[0] !== 'Code' ) {
				$res[ $line1[0] ] = $line1[1];
			}
		}
		update_option( $_POST['table'], $res, false );
	}


	/** Function return all or by name table with coding from dataBase
	 *
	 * @param string $table
	 *
	 * @return array
	 */
	public static function get_table( string $table = '' ): array {
		$tables    = [];
		$tables_db = [];
		foreach ( self::$ready_table_direction as $item ) {
			if ( empty( $tables[ $item ] ) ) {
				$tables_db[ $item ] = get_option( $item );
			}
			$tables[ $item ] = $item;
		}

		return $tables_db[ $table ] ?? $tables_db;
	}


	/**
	 * Check if dataBase exist
	 */
	public static function db_exist(): ?bool {
		global $wpdb;
		$table_name = $wpdb->get_blog_prefix() . 'import_table';

		return $wpdb->get_var( "show tables like '" . $table_name . "'" ) === $table_name ?? ' ';
	}


	/**
	 * Return column list for creating
	 */
	public static function add_columns() {
		global $wpdb;

		$table_name       = $wpdb->get_blog_prefix() . 'import_table';
		$existing_columns = $wpdb->get_col( "DESC {$table_name}", 0 );
		$ar               = [];
		$fields_for_save  = [];

		foreach ( self::$ready_csv_array_columns as $key ) {
			$ar[ $key ] = $key;
			for ( $i = 0; $i < count( $existing_columns ); $i ++ ) {
				if ( $key === $existing_columns[ $i ] ) {
					unset( $ar[ $key ] );
				}
			}
			if ( isset( $ar[ $key ] ) ) {
				$fields_for_save[] = $ar[ $key ];
			}
		}

		return $fields_for_save;
	}


	/**
	 * Prepare db fields string for sql
	 */
	public static function prepare_db_fields( $arr = '' ) {
		$dbFields = [];
		$array    = $arr ? $arr : self::$ready_csv_array_columns;

		foreach ( $array as $key ) {

			$dbFields[] = $key === 'id' ? mb_strtolower( $key ) . ' bigint(20) unsigned NOT NULL auto_increment' : '`' . mb_strtolower( $key ) . '`' . " text(500) NOT NULL default ''";
		}

		return str_replace( '-', '_', implode( ', ', $dbFields ) );
	}


	/**
	 * Save in dataBase
	 */
	public static function save() {
		global $wpdb;
		$table_name = $wpdb->get_blog_prefix() . 'import_table';

		foreach ( self::$ready_csv_array_fields as $arr ) {
			if ( isset( self::$db_id_values_array[ $arr['id'] ] ) ) {
				$res[] = $wpdb->update( $table_name, $arr, [ 'id' => $arr['id'] ] );
			} else {
				$res[] = $wpdb->insert( $table_name, $arr );
			}
		}

		return $res;
	}


	/**
	 * Get values by column name
	 */
	public static function get_column_values( $column, $table_name ) {
		global $wpdb;

		foreach ( $wpdb->get_col( "SELECT $column FROM $table_name " ) as $key ) {
			$id_table[ $key ] = $key;
		}

		return $id_table;
	}


	/**
	 * Create table or add new columns for it
	 */
	public static function create_db_table() {
		global $wpdb;
		$table_name = $wpdb->get_blog_prefix() . 'import_table';

		if ( self::db_exist() ) {
			self::$db_id_values_array = self::get_column_values( 'id', $table_name ) ?? '';
			if ( ! empty( $columns = self::add_columns() ) ) {
				$columns = self::prepare_db_fields( $columns );

				return $wpdb->query( "ALTER TABLE  $table_name  ADD COLUMN ( $columns ) " );
			} else {
				return 2;
			}
		} else {

			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset COLLATE  $wpdb->collate  ";
			$fields          = self::prepare_db_fields();
			$sql             = "CREATE TABLE  $table_name  ( $fields , PRIMARY KEY (id) )  $charset_collate  ";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			return dbDelta( $sql );

		}
	}


	/**
	 * load and replacing values
	 */
	public static function load_csv( $file ) {

		$instruction = self::$ready_table_direction;
		$tables      = self::get_table();

		while ( ( $line = fgetcsv( $file ) ) !== false ) {

			if ( $line[0] === 'ID' ) {
				for ( $i = 0; $i < count( $line ); $i ++ ) {
					$column[ $i ]                        = $line[ $i ];
					self::$ready_csv_array_columns[ $i ] = str_replace( '-', '_', strtolower( $line[ $i ] ) );;
				}
			}
			if ( $line[0] !== 'ID' ) {
				for ( $i = 0; $i < count( $line ); $i ++ ) {
					$table                                       = $instruction[ $column[ $i ] ] ?? '';
					$arr[ self::$ready_csv_array_columns[ $i ] ] = $tables[ $table ][ $line[ $i ] ] ?? $line[ $i ];
				}
				self::$ready_csv_array_fields[] = $arr;
			}
		}
		self::create_db_table();
	}

}
