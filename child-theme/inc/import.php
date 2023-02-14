<?php /** @noinspection SqlNoDataSourceInspection, SqlDialectInspection */

$imports = array(
	array(
		'name'   => 'store_locations',
		'table'  => WLD_Custom_DB::LOCATIONS,
		'fields' => array(
			array(
				'name'          => 'clear_old',
				'label'         => esc_html__( 'Clear Old', 'parent-theme' ),
				'type'          => 'checkbox',
				'default_value' => 'on',
			),
			array(
				'name'          => 'add_new',
				'label'         => esc_html__( 'Add New', 'parent-theme' ),
				'type'          => 'checkbox',
				'default_value' => 'on',
			),
			array(
				'name'          => 'set_coordinates',
				'label'         => esc_html__( 'Set Coordinates from here.com', 'parent-theme' ),
				'type'          => 'checkbox',
				'default_value' => 'on',
			),
			array(
				'name'          => 'set_coordinates_from_google',
				'label'         => esc_html__( 'Set Coordinates from google.com', 'parent-theme' ),
				'type'          => 'checkbox',
				'default_value' => 'off',
				'description'   => 'Attention, run this action separately after import, as Google has a limit on operations and you can overcome it. Each call will handle a maximum of 200 addresses.',
			),
		),
	)

);
foreach ( $imports as $import ) {
	WLD_Importer::add(
		$import['name'],
		static function ( array $current_rows ) use ( $import ): void {
			$fields = WLD_Importer::$current['fields'] ?? array();

			$progress        = 0;
			$clear_old       = $fields['clear_old'] ?? 'off';
			$add_new         = $fields['add_new'] ?? 'off';
			$set_coordinates = $fields['set_coordinates'] ?? 'off';
			$need_progress   = ( 'on' === $clear_old ) * 10 + ( 'on' === $set_coordinates ) * 100;
			$next            = true;
			$count           = count( $current_rows );
			$add_count       = 5000;
			$add_steps       = ceil( $count / $add_count );
			$add_step        = WLD_Importer::$current['add_step'] ?? 1;

			if ( 'on' === $add_new ) {
				$need_progress += $add_steps * 10;
			}

			try {
				if ( 'on' === $clear_old ) {
					$progress += 10;
					if ( empty( WLD_Importer::$current['clear_old'] ) ) {
						theme_importer_clear_old_import_items( $import['table'] );
						WLD_Importer::$current['clear_old'] = 1;
						WLD_Importer::log( 'Clear all', 'success' );
						$next = false;
					}
				}

				if ( 'on' === $add_new && $next ) {
					$progress += $add_step * 10;
					if ( empty( WLD_Importer::$current['add_new'] ) ) {
						$step_rows = array_slice( $current_rows, ( $add_step - 1 ) * $add_count, $add_count );

						if ( $step_rows ) {
							if ( 'store_locations' === $import['name'] ) {
								$values = theme_importer_locations_prepare_date( $step_rows );
							} //else {
							// 	$values = theme_importer_trade_partners_prepare_date( $step_rows );
							// }

							if ( 'store_locations' === $import['name'] ) {
								theme_importer_locations_add_new( $values );
							} //else {
							// 	theme_importer_trade_partners_add_new( $values );
							// }

							WLD_Importer::log( 'Add (' . count( $step_rows ) . '/' . $count . ') rows', 'success' );
							WLD_Importer::$current['add_step'] = ++ $add_step;
						} else {
							WLD_Importer::$current['add_new'] = 1;
						}

						$next = false;
					}
				}

				if ( 'on' === $set_coordinates && 'store_locations' === $import['name'] && $next ) {
					if ( empty( WLD_Importer::$current['full_empty_coordinates'] ) ) {
						$full_empty_coordinates = theme_importer_get_count_locations_without_coordinates();

						WLD_Importer::$current['full_empty_coordinates'] = $full_empty_coordinates;
						WLD_Importer::log( 'Set coordinates', 'progress', 'sc' );
					}

					$set_coordinates_from_db = theme_importer_locations_set_coordinates_from_db();

					if ( 0 === $set_coordinates_from_db ) {
						theme_importer_locations_set_coordinates();
					}

					$full      = (int) WLD_Importer::$current['full_empty_coordinates'];
					$remaining = theme_importer_get_count_locations_without_coordinates();

					WLD_Importer::log( 'Set coordinates(remaining ' . $remaining . ')', 'progress', 'sc' );

					$progress += $full ? 100 - round( $remaining * 100 / $full ) : 100;

					if ( 0 === $remaining ) {
						WLD_Importer::log( 'All coordinates are set', 'success' );
					}
				}
			} catch ( RuntimeException $e ) {
				WLD_Importer::log( $e->getMessage(), 'error' );
			}

			if ( $progress < $need_progress ) {
				set_transient( WLD_Importer::get_transient_name( $import['name'] ), WLD_Importer::$current, DAY_IN_SECONDS );

				wp_send_json_success(
					array(
						'log'      => WLD_Importer::$current['log'],
						'progress' => round( $progress * 100 / $need_progress ),
					)
				);
			}
		},
		0,
		$import['fields']
	);
}

add_action(
	'theme_importer_after_blocks',
	static function (): void {
		$key = get_field( 'theme_api_google_maps_key', 'option' );
		?>
		<script src='https://maps.googleapis.com/maps/api/js?key=<?php echo $key; ?>&callback=wldInitGoogleMaps'
				defer></script>
		<script>
			document.getElementById('store_locations-set_coordinates_from_google').setAttribute('disabled', 'disabled');

			function wldInitGoogleMaps() {
				const $ = jQuery;
				const $block = $('[data-name="store_locations"]');
				const $fromGoogle = $('#store_locations-set_coordinates_from_google');
				const $button = $block.find('.start');
				const $progress = $block.find('.progress progress')
				const data = {
					nonce: '<?php echo wp_create_nonce( 'ajax-nonce' ); ?>',
					action: ''
				};
				const geocoder = new google.maps.Geocoder();

				let locations = [];

				$fromGoogle.prop('disabled', false);

				$button.on('click', function (e) {
					if ($fromGoogle.prop('checked')) {
						e.preventDefault();
						e.stopPropagation();
						setInProgress();

						data.action = 'empty_store_locations';

						$.post({
							url: ajaxurl,
							data: data,
							dataType: 'json',
							success: function (response) {
								if (response.data.locations.length) {
									locations = response.data.locations;
									geocode(0);
								} else {
									alert('Coordinates are filled in for all local stores.');
								}
							}
						});
					}
				});

				function geocode(i) {
					const location = locations[i];
					geocoder.geocode({
						address: location.address
					}, (results, status) => {
						if (status === 'OK' && results[0]) {
							data.id = location.id;
							data.address = location.address;
							data.latitude = parseFloat(results[0].geometry.location.lat()).toFixed(6);
							data.longitude = parseFloat(results[0].geometry.location.lng()).toFixed(6);
							data.action = 'save_coordinates_from_google';

							$.post({
								url: ajaxurl,
								data: data
							}).always(function () {
								$progress.val(100 * i / locations.length);
								++i;
								if (locations.length === i) {
									setNotProgress();
								} else {
									setTimeout(function () {
										geocode(i);
									}, 200);
								}
							});
						} else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
							setTimeout(function () {
								geocode(i);
							}, 4000);
						} else {
							alert('Geocode was not successful for the following reason: ' + status);
						}
					});
				}

				function setInProgress() {
					$block.addClass('in-progress')
						.removeClass('has-progress')
						.find('.button').addClass('button-disabled');
				}

				function setNotProgress() {
					$block.removeClass('in-progress')
						.removeClass('has-progress')
						.find('.button').removeClass('button-disabled');
					$progress.val(0);
				}
			}

			(function ($) {
				const $file = $('#store_locations-file'),
					$addNew = $('#store_locations-add_new');
				$addNew.on('change', () => $file.toggleClass('required', $addNew.prop('checked'))).trigger('change');
				const $file2 = $('#trade_partners-file'),
					$addNew2 = $('#trade_partners-add_new');
				$addNew2.on('change', () => $file2.toggleClass('required', $addNew2.prop('checked'))).trigger('change');
			})(jQuery);
		</script>
		<?php
	}
);

add_filter(
	'theme_importer_file_required',
	static function ( bool $file_required, string $name ): bool {
		if ( 'store_locations' === $name || 'trade_partners' === $name ) {
			$file_required = false;
		}

		return $file_required;
	},
	10,
	2
);

add_filter(
	'theme_importer_show_step_count_field',
	static function ( bool $show_step_count_field, string $name ): bool {
		if ( 'store_locations' === $name || 'trade_partners' === $name ) {
			$show_step_count_field = false;
		}

		return $show_step_count_field;
	},
	10,
	2
);

function theme_importer_locations_prepare_date( array $current_rows ): array {
	global $wpdb;

	$values = array();
	foreach ( $current_rows as $row ) {
		[ // phpcs:ignore
			,
			$name,
			$address,
			$city,
			$state,
			$zip,
		] = $row;

		$zip = preg_replace( '/[^0-9-]/', '', $zip );


		$values[] = $wpdb->prepare(
			'( %s, %s, %s, %s, %s )',
			trim( $name ),
			trim( $address ),
			trim( $city ),
			trim( $state ),
			trim( $zip )
		);
	}

	return $values;
}

// function theme_importer_trade_partners_prepare_date( array $current_rows ) : array {
// 	global $wpdb;

// 	$values = array();
// 	foreach ( $current_rows as $row ) {
// 		[ // phpcs:ignore
// 			,
// 			$number,
// 			$account_type,
// 			$customer,
// 			$company,
// 			, // Representative
// 			$phone,
// 			, // Mobile Tel.No.
// 			$fax,
// 			, // Sales State
// 			, // Shipping State
// 			$trade_status,
// 			$address,
// 			$city_state_zip,
// 		] = $row;

// 		preg_match( '/^([^,0-2]{3,}?)?,? ?([a-zA-Z]{2}[.,]?)? ?([\d-]+)?$/', $city_state_zip, $matches );

// 		$city    = trim( $matches[1] ?? '' );
// 		$state   = mb_strtoupper( trim( $matches[2] ?? '', ' \t\n\r\0\x0B.,' ) );
// 		$zip     = preg_replace( '/[^0-9-]/', '', $matches[3] ?? '' );
// 		$phone   = preg_replace( '/[^0-9+-]/', '', $phone );
// 		$fax     = preg_replace( '/[^0-9+-]/', '', $fax );
// 		$address = str_replace( $city_state_zip, '', $address );

// 		$values[] = $wpdb->prepare(
// 			'( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s  )',
// 			trim( $number ),
// 			trim( $account_type ),
// 			trim( $customer ),
// 			trim( $company ),
// 			$phone,
// 			$fax,
// 			trim( $trade_status ),
// 			trim( $address ),
// 			$city,
// 			$state,
// 			$zip,
// 			trim( $city_state_zip )
// 		);
// 	}

// 	return $values;
// }

function theme_importer_locations_add_new( array $values ): void {
	global $wpdb;

	$wpdb->hide_errors();
	$wpdb->locations = WLD_Custom_DB::LOCATIONS;

	// $query = "
	// 	INSERT INTO `$wpdb->locations` (
	// 		`name`,
	// 		`address`,
	// 		`city`,
	// 		`state`,
	// 		`zip`,
	// 		`category_time`,
	// 		 `category_carnival`,
	// 		 `category_this`
	// 	) VALUES " . WLD_Custom_DB::fix_null( implode( ', ', $values ) );
	// $values=implode($values);


	$count = count( $values );
	for ( $i = 0; $i < $count; $i ++ ) {
		$values[ $i ] = str_replace( "'", "", $values[ $i ] );
		$values[ $i ] = substr( $values[ $i ], 1, - 1 );
		$values[ $i ] = str_replace( ",", "','", $values[ $i ] );
		$values[ $i ] = str_replace( "\"", "", $values[ $i ] );
		$values[ $i ] = str_replace( "\\", "", $values[ $i ] );
		$tmp          = explode( ',', $values[ $i ] );
		$output       = array_slice( $tmp, 0, 11 );
		$tmp          = implode( ',', $output );
		$tmp          = "('" . $tmp . ")";
		$values[ $i ] = $tmp;
	}

	$tmp1 = implode( ', ', $values );
//	$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//WHERE table_name = 'customer'"  );
//	if(empty($row)){
//		$wpdb->query("ALTER TABLE ".$wpdb->locations." ADD customer varchar(255) NOT NULL");
//	}
//	$row2 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//WHERE table_name = 'main_phone'"  );
//	if(empty($row2)){
//		$wpdb->query("ALTER TABLE ".$wpdb->locations." ADD main_phone varchar(255) NOT NULL");
//	}
//	$row3 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//WHERE table_name = 'main_email'"  );
//	if(empty($row3)){
//		$wpdb->query("ALTER TABLE ".$wpdb->locations." ADD main_email varchar(255) NOT NULL");
//	}
//	$row4 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//WHERE table_name = 'ship_to_street2'"  );
//	if(empty($row4)){
//		$wpdb->query("ALTER TABLE ".$wpdb->locations." ADD ship_to_street2 varchar(255) NOT NULL");
//	}
//	$row5 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//WHERE table_name = 'account_NO'"  );
//	if(empty($row5)){
//		$wpdb->query("ALTER TABLE ".$wpdb->locations." ADD account_NO varchar(255) NOT NULL");
//	}
//	$row6 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//WHERE table_name = 'Rep'"  );
//	if(empty($row6)){
//		$wpdb->query("ALTER TABLE ".$wpdb->locations." ADD Rep varchar(255) NOT NULL");
//	}

	$name1 = 'wld_locator_store_locations';//self::LOCATIONS;
	if ( $wpdb->get_var( "show tables like '" . $name1 . "'" ) != $name1 ) {
		$sql1 = "
			CREATE TABLE $name1 (
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				customer varchar(255) NOT NULL,
				name varchar(255) NOT NULL,
				main_phone varchar(255) NOT NULL,
				main_email varchar(255) NOT NULL,
				address varchar(255) NOT NULL,
				ship_to_street2 varchar(255) NOT NULL,
				city varchar(255) NOT NULL,
				state varchar(255) NOT NULL,
				zip varchar(255) NOT NULL,
				account_NO varchar(255) NOT NULL,
				Rep varchar(255) NOT NULL,
				category_time tinyint(1) NOT NULL,
				category_carnival tinyint(1) NOT NULL,
				category_this tinyint(1) NOT NULL,
				latitude float(10,6) DEFAULT NULL,
				longitude float(10,6) DEFAULT NULL,
				PRIMARY KEY (id),
				KEY category_time (category_time),
				KEY category_carnival (category_carnival),
				KEY category_this (category_this),
				KEY latitude (latitude),
				KEY longitude (longitude)
		    ) DEFAULT CHARACTER SET $wpdb->charset COLLATE $wpdb->collate;
		";
		$wpdb->query( $sql1 );
	};
	$name2 = 'wld_locator_coordinates';//self::COORDINATES;
	if ( $wpdb->get_var( "show tables like '" . $name2 . "'" ) != $name2 ) {
		$sql2 = "
			CREATE TABLE $name2 (
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				address varchar(255) NOT NULL,
				latitude float(10,6) NOT NULL,
				longitude float(10,6) NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY address (address)
		    ) DEFAULT CHARACTER SET $wpdb->charset COLLATE $wpdb->collate;
		";
		$wpdb->query( $sql2 );
	}


	$query = "
		INSERT INTO `$wpdb->locations` (
			customer,
			name,
			main_phone,
			main_email,
			address,
			ship_to_street2,
			city,
			state,
			zip,
			account_NO,
			Rep
		) VALUES  " . WLD_Custom_DB::fix_null( $tmp1 );


	$wpdb->query( $query ); // phpcs:ignore

	if ( $wpdb->last_error ) {
		throw new RuntimeException( theme_importer_get_db_error() );
	}
}

// function theme_importer_trade_partners_add_new( array $values ) : void {
// 	global $wpdb;

// 	$wpdb->hide_errors();
// 	$wpdb->trade_partners = WLD_Custom_DB::TRADE_PARTNERS;

// 	$query = "
// 		INSERT INTO `$wpdb->trade_partners` (
// 			`number`,
// 			`account_type`,
// 			`customer`,
// 			`company`,
// 			`phone`,
// 			`fax` ,
// 			`trade_status`,
// 			`address`,
// 			`city`,
// 			`state`,
// 			`zip`,
// 			`city_state_zip`
//         ) VALUES " . WLD_Custom_DB::fix_null( implode( ', ', $values ) );

// 	$wpdb->query( $query ); // phpcs:ignore

// 	if ( $wpdb->last_error ) {
// 		throw new RuntimeException( theme_importer_get_db_error() );
// 	}
// }

function theme_importer_locations_set_coordinates(): int {
	global $wpdb;

	$wpdb->hide_errors();
	$wpdb->coordinates = WLD_Custom_DB::COORDINATES;
	$wpdb->locations   = WLD_Custom_DB::LOCATIONS;

	$not_in_error = WLD_Importer::$current['not_in_error_locations'] ?? array( 0 );
	$not_in       = implode( ', ', $not_in_error );
	$query        = "
		SELECT `id`, CONCAT_WS( ' ', `address`, `city`, `state`, `zip` ) AS `address`
		FROM $wpdb->locations
		WHERE ( `latitude` IS NULL OR `longitude` IS NULL ) AND `id` NOT IN ( $not_in ) LIMIT 10";

	$locations = $wpdb->get_results( $query, OBJECT_K ); // phpcs:ignore
	if ( $locations ) {
		$coordinates      = theme_importer_coordinates_by_locations( $locations );
		$new_coordinates  = array();
		$update_latitude  = array();
		$update_longitude = array();

		foreach ( $locations as $id => $location ) {
			$address = mb_strtolower( theme_importer_fix_utf8_non_breaking( $location->address ) );
			if ( isset( $coordinates[ $address ] ) ) {
				$lat_lng = array( $coordinates[ $address ]->latitude, $coordinates[ $address ]->longitude );
			} else {
				$lat_lng = WLD_Custom_DB::get_lat_lng( $address );
				if ( is_array( $lat_lng ) ) {
					$new_coordinates[] = $wpdb->prepare( '( %s, %f, %f )', $address, $lat_lng[0], $lat_lng[1] );

					$coordinates[ $address ]            = new stdClass();
					$coordinates[ $address ]->latitude  = $lat_lng[0];
					$coordinates[ $address ]->longitude = $lat_lng[1];
				} elseif ( is_wp_error( $lat_lng ) && $lat_lng->get_error_message( 'empty_result_error' ) ) {
					WLD_Importer::log(
						'For the address "' . $address . '" coordinates are not defined',
						'error'
					);

					$new_coordinates[] = $wpdb->prepare( '( %s, %f, %f )', $address, 0, 0 );

					$coordinates[ $address ]            = new stdClass();
					$coordinates[ $address ]->latitude  = 0;
					$coordinates[ $address ]->longitude = 0;
				} else {
					WLD_Importer::log( 'Error: ' . $lat_lng->get_error_message(), 'error' );
					$not_in_error[] = $id;
				}
			}

			if ( is_array( $lat_lng ) ) {
				$update_latitude[]  = $wpdb->prepare( 'WHEN %d THEN %f', $id, $lat_lng[0] );
				$update_longitude[] = $wpdb->prepare( 'WHEN %d THEN %f', $id, $lat_lng[1] );
			}
		}

		if ( $new_coordinates ) {
			$query = "
				INSERT INTO `$wpdb->coordinates` ( `address`, `latitude`, `longitude` )
				VALUES " . implode( ', ', $new_coordinates );
			$wpdb->query( $query ); // phpcs:ignore
			if ( $wpdb->last_error ) {
				throw new RuntimeException( theme_importer_get_db_error() );
			}
		}

		if ( $update_latitude ) {
			$update_latitude  = implode( ' ', $update_latitude );
			$update_longitude = implode( ' ', $update_longitude );
			$query            = "
				UPDATE `$wpdb->locations` SET
				`latitude` = CASE `id` $update_latitude ELSE `latitude` END,
				`longitude` = CASE `id` $update_longitude ELSE `longitude` END";
			$wpdb->query( $query ); // phpcs:ignore
			if ( $wpdb->last_error ) {
				throw new RuntimeException( theme_importer_get_db_error() );
			}
		}
	} elseif ( $wpdb->last_error ) {
		throw new RuntimeException( theme_importer_get_db_error() );
	}

	WLD_Importer::$current['not_in_error_locations'] = $not_in_error;

	return count( $locations );
}

function theme_importer_locations_set_coordinates_from_db(): int {
	global $wpdb;

	$wpdb->hide_errors();
	$wpdb->coordinates = WLD_Custom_DB::COORDINATES;
	$wpdb->locations   = WLD_Custom_DB::LOCATIONS;

	$not_in_error = WLD_Importer::$current['not_in_error_locations'] ?? array( 0 );
	$not_in_db    = WLD_Importer::$current['not_in_db_locations'] ?? array();
	$not_in       = implode( ', ', array_unique( array_merge( $not_in_error, $not_in_db ) ) );
	$query        = "
		SELECT `id`, CONCAT_WS( ' ', `address`, `city`, `state`, `zip` ) AS `address`
		FROM $wpdb->locations
		WHERE ( `latitude` IS NULL OR `longitude` IS NULL ) AND `id` NOT IN ( $not_in ) LIMIT 5000";

	$locations = $wpdb->get_results( $query, OBJECT_K ); // phpcs:ignore
	if ( $locations ) {
		$coordinates      = theme_importer_coordinates_by_locations( $locations );
		$update_latitude  = array();
		$update_longitude = array();
		foreach ( $locations as $id => $location ) {
			$address = mb_strtolower( theme_importer_fix_utf8_non_breaking( $location->address ) );
			if ( isset( $coordinates[ $address ] ) ) {
				$update_latitude[]  = $wpdb->prepare( 'WHEN %d THEN %f', $id, $coordinates[ $address ]->latitude );
				$update_longitude[] = $wpdb->prepare( 'WHEN %d THEN %f', $id, $coordinates[ $address ]->longitude );
			} else {
				$not_in_db[] = $id;
			}
		}

		if ( $update_latitude ) {
			$update_latitude  = implode( ' ', $update_latitude );
			$update_longitude = implode( ' ', $update_longitude );
			$query            = "
				UPDATE `$wpdb->locations` SET
				`latitude` = CASE `id` $update_latitude ELSE `latitude` END,
				`longitude` = CASE `id` $update_longitude ELSE `longitude` END";
			$wpdb->query( $query ); // phpcs:ignore
			if ( $wpdb->last_error ) {
				throw new RuntimeException( theme_importer_get_db_error() );
			}
		}
	} elseif ( $wpdb->last_error ) {
		throw new RuntimeException( theme_importer_get_db_error() );
	}

	WLD_Importer::$current['not_in_db_locations'] = $not_in_db;

	return count( $locations );
}

function theme_importer_get_count_locations_without_coordinates(): int {
	global $wpdb;

	$wpdb->hide_errors();
	$wpdb->locations = WLD_Custom_DB::LOCATIONS;
	$not_in          = implode( ', ', WLD_Importer::$current['not_in_error_locations'] ?? array( 0 ) );
	$query           = "
		SELECT COUNT(1)
		FROM $wpdb->locations
		WHERE ( `latitude` IS NULL OR `longitude` IS NULL ) AND `id` NOT IN ( $not_in )";

	$count = $wpdb->get_var( $query ); // phpcs:ignore
	if ( $wpdb->last_error ) {
		throw new RuntimeException( theme_importer_get_db_error() );
	}

	return (int) $count;
}

function theme_importer_clear_old_import_items( string $table ): void {
	global $wpdb;

	$wpdb->hide_errors();
	$wpdb->table = $table;
	$wpdb->query( "TRUNCATE TABLE `$wpdb->table`" );

	if ( $wpdb->last_error ) {
		throw new RuntimeException( theme_importer_get_db_error() );
	}
}

function theme_importer_get_db_error(): string {
	global $wpdb;

	return "Database error:  $wpdb->last_error\r\nSQL: $wpdb->last_query";
}

function theme_importer_fix_utf8_non_breaking( string $string ): string {
	return preg_replace( '/\x{00a0}/u', ' ', $string );
}

function theme_importer_coordinates_by_locations( array $locations ): array {
	global $wpdb;

	$coordinates = array();
	if ( $locations ) {
		// We split into several requests to bypass the limitation on the length of the request in WPEngine.
		// https://wpengine.com/support/troubleshoot-wordpress-wp-engine-error-log/#killedquery
		$chunks = array_chunk( $locations, 200 );
		foreach ( $chunks as $chunk ) {
			$format       = implode( ',', array_fill( 0, count( $chunk ), '%s' ) );
			$addresses    = $wpdb->prepare( $format, array_column( $chunk, 'address' ) ); // phpcs:ignore
			$query        = "
				SELECT LOWER( `address` ), `latitude`, `longitude`
				FROM `$wpdb->coordinates`
				WHERE `address` IN ( $addresses )";
			$_coordinates = $wpdb->get_results( $query, OBJECT_K ); // phpcs:ignore
			if ( $wpdb->last_error ) {
				throw new RuntimeException( theme_importer_get_db_error() );
			}

			foreach ( $_coordinates as $address => $value ) {
				$coordinates[ theme_importer_fix_utf8_non_breaking( $address ) ] = $value;
			}
		}
	}

	return $coordinates;
}
