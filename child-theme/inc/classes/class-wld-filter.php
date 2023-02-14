<?php

class WLD_Filter {

	protected const NAMESPACE = 'wld-filter/v1/';

	public static function init(): void {

		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);
		add_action(
			'init',
			array( self::class, 'rewrite_rule_for_filter' )
		);

	}

	public static function registration_rest_routes() {

		register_rest_route( self::NAMESPACE, 'search/',
			array(
				'methods'  => 'GET',
				'callback' => array( self::class, 'search' ),
			),
		);
	}

	public static function search( $request ) {
		global $wpdb;
		$request         = json_decode( $request['req'] );
		$table_name = $wpdb->get_blog_prefix() . 'import_table';
		$default_columns = $wpdb->get_col( "DESC $table_name", 0 );
		$type_student    = array(
			'high schooler' => 'fresh',
			'freshman'      => 'fresh',
			'sophomore'     => 'soph',
			'junior'        => 'junior',
			'senior'        => 'senior',
		);

		$state 			= '';
		$amount 		= '';
		$gender 		= '';
		$disability 	= '';
		$financial 	 	= '';
		$deadline   	= '';
		$ethnicity  	= '';
		$area_of_study  = '';
		$type_of_study  = '';

		$amount_default = array(
			'Less than $500'  => [ " '' ", 500 ],
			'$500 - $1000'    => [ 500, 1000 ],
			'$1000 - $1500'   => [ 1000, 1500 ],
			'$1500 - $2500'   => [ 1500, 2500 ],
			'$2500 - $5000'   => [ 2500, 4500 ],
			'More than $5000' => [ 4500, 2000000 ],
		);

		$states_default = [
			'All states'     => 'all',
			'Alabama'        => 'AL',
			'Alaska'         => 'AK',
			'Arizona'        => 'AZ',
			'Arkansas'       => 'AR',
			'California'     => 'CA',
			'Colorado'       => 'CO',
			'Connecticut'    => 'CT',
			'Delaware'       => 'DE',
			'Florida'        => 'FL',
			'Georgia'        => 'GA',
			'Hawaii'         => 'HI',
			'Idaho'          => 'ID',
			'Illinois'       => 'IL',
			'Indiana'        => 'IN',
			'Iowa'           => 'IA',
			'Kansas'         => 'KS',
			'Kentucky'       => 'KY',
			'Louisiana'      => 'LA',
			'Maryland'       => 'MA',
			'Maine'          => 'ME',
			'Massachusetts'  => 'MD',
			'Michigan'       => 'MI',
			'Minnesota'      => 'MN',
			'Mississippi'    => 'MS',
			'Missouri'       => 'MO',
			'Montana'        => 'MT',
			'Nebraska'       => 'NE',
			'Nevada'         => 'NV',
			'New Hampshire'  => 'NH',
			'New Jersey'     => 'NJ',
			'New Mexico'     => 'NM',
			'New York'       => 'NY',
			'North Carolina' => 'NC',
			'North Dakota'   => 'ND',
			'Ohio'           => 'OH',
			'Oklahoma'       => 'OK',
			'Oregon'         => 'OR',
			'Pennsylvania'   => 'PA',
			'Rhode Island'   => 'RI',
			'South Carolina' => 'SC',
			'South Dakota'   => 'SD',
			'Tennessee'      => 'TN',
			'Texas'          => 'TX',
			'Utah'           => 'UT',
			'Vermont'        => 'VT',
			'Virginia'       => 'VA',
			'Washington'     => 'WA',
			'West Virginia'  => 'WV',
			'Wisconsin'      => 'WI',
			'Wyoming'        => 'WY',
		];

		$gender_default = array(
			'male'   => 'gender',
			'female' => 'gender',
		);

		$ethnicity_default = array(
			'African American' => 'black',
			'American Indian'  => 'indesk',
			'Hispanic'         => 'hisp',
			'Asian American'   => 'asian',
		);

		$disability_default = array(
			'Physical' => 'phys',
			'Visual'   => 'blind',
			'Hearing'  => 'deaf',
		);

		$financial_default = array(
			'Financial Need - Yes' => 'applfine'
		);

		$deadline_default = array(
			'In less than two weeks'    => '2weeks',
			'In less than one month'    => '1month',
			'In less than three months' => '3months',
			'More than three months'    => '3more',
		);

		$area_of_study_default = array(
			'Agribusiness',
			'Architecture',
			'Academic Advising',
			'Area/Ethnic Studies',
			'African Studies',
			'Agriculture',
			'Art History',
			'Asian Studies',
			'American Studies',
			'Animal/Veterinary Sciences',
			'Anthropology',
			'Arts',
			'Applied Sciences',
			'Accounting',
			'Audiology',
			'Aviation/Aerospace',
			'Archaeology',
			'Behavioral Science',
			'Biology',
			'Business/Consumer Services',
			'Campus Activities',
			'Canadian Studies',
			'Construction Engineering/Management',
			'Child and Family Studies',
			'Chemical Engineering',
			'Criminal Justice/Criminology',
			'Classics',
			'Communications',
			'Computer Science/Data Processing',
			'Culinary Arts',
			'Civil Engineering',
			'Cosmetology',
			'Dental Health/Services',
			'Drafting',
			'Economics',
			'Education',
			'Environmental Health',
			'Electrical Engineering/Electronics',
			'Entomology',
			'Energy and Power Engineering',
			'Engineering-Related Technologies',
			'Earth Science',
			'Engineering/Technology',
			'European Studies',
			'Environmental Science',
			'Fashion Design',
			'Flexography',
			'Filmmaking/Video',
			'Foreign Language',
			'Finance',
			'Food Service/Hospitality',
			'Fire Sciences',
			'Food Science/Nutrition',
			'Geography',
			'Gemology',
			'Graphics/Graphic Arts/Printing',
			'German Studies',
			'Health Administration',
			'Hydrology',
			'Home Economics',
			'Health Information Management/Technology',
			'Health and Medical Sciences',
			'Horticulture/Floriculture',
			'Historic Preservation and Conservation',
			'Human Resources',
			'History',
			'Humanities',
			'Hospitality Management',
			'Insurance and Actuarial Science',
			'Interior Design',
			'International Migration',
			'Industrial Design',
			'International Studies',
			'Journalism',
			'Landscape Architecture',
			'Literature/English/Writing',
			'Library and Information Sciences',
			'Law/Legal Services',
			'Law Enforcement/Police Administration',
			'Meteorology/Atmospheric Science',
			'Marine Biology',
			'Music',
			'Military and Defense Studies',
			'Mechanical Engineering',
			'Mathematics',
			'Marketing',
			'Marine/Ocean Engineering',
			'Heating, Air-Conditioning, and Refrigeration Mechanics',
			'Funeral Services/Mortuary Science',
			'Materials Science Engineering, and Metallurgy',
			'Museum Studies',
			'Neurobiology',
			'Natural Sciences',
			'Near and Middle East Studies',
			'Natural Resources',
			'Nuclear Science',
			'Nursing',
			'Oceanography',
			'Oncology',
			'Optometry',
			'Occupational Safety and Health',
			'Osteopathy',
			'Performing Arts',
			'Public Health',
			'Peace and Conflict Studies',
			'Paper and Pulp Engineering',
			'Pharmacy',
			'Photojournalism/Photography',
			'Psychology',
			'Political Science',
			'Public Policy and Administration',
			'Advertising/Public Relations',
			'Physical Sciences',
			'Philosophy',
			'Radiology',
			'Real Estate',
			'Recreation, Parks, Leisure Studies',
			'Religion/Theology',
			'Surveying, Surveying Technology, Cartography, or Geographic Information Science',
			'Special Education',
			'Statistics',
			'Social Sciences',
			'Sports-Related/Exercise Science',
			'Social Services',
			'Science, Technology, and Society',
			'Therapy/Rehabilitation',
			'Transportation',
			'Travel/Tourism',
			'Trade/Technical Specialties',
			'TV/Radio Broadcasting',
			'Urban and Regional Planning',
			"Women's Studies",
		);

		$type_of_study_default = array(
			'part-time' => 'pt_only',
			'full-time' => 'ft_only',
		);

		if ( ! empty( $request->checked ) ) {

			$checked       = $request->checked;

			if ( ! is_array( $checked ) && ! empty( $checked ) ) {
				$check [] = $checked;
				$checked  = $check;
			}

			foreach ( $checked as $key => $value ) {
				/** Amount */
				if ( ! empty( $amount_default[ $value ] ) ) {
					$arg1   = $amount_default[ $value ][0];
					$arg2   = $amount_default[ $value ][1];
					$amount .= empty( $amount ) ? "loamnt BETWEEN $arg1 AND $arg2" : " OR loamnt BETWEEN $arg1 AND $arg2";
				}
				/** States */
				if ( ! empty( $states_default[ $value ] ) ) {
					if ( $states_default[ $value ] === 'all' ) {
						foreach ( $states_default as $key => $value ) {
							if ( $value !== 'all' ) {
								$state .= empty( $state ) ? " `st` = '$value' " : " OR `st` = '$value' ";
							}
						}
					} else {
						$st    = $states_default[ $value ];
						$state .= empty( $state ) ? " `st` = '$st' " : " OR `st` = '$st' ";
					}
				}
				/** Gender */
				if ( ! empty( $gender_default[ $value ] ) ) {
					$gender = empty( $gender ) ? " `$value` = 'X' " : "";
				}
				/** Disability */
				if ( ! empty( $disability_default[ $value ] ) ) {
					$field      = $disability_default[ $value ];
					$disability .= empty( $disability ) ? " `$field` = 'X' " : " OR `$field` = 'X' ";
				}
				/** Financial */
				if ( ! empty( $financial_default[ $value ] ) ) {
					$field     = $financial_default[ $value ];
					$financial .= empty( $financial ) ? " `$field` = 'X' " : " OR `$field` = 'X' ";
				}
				/** Deadline */
				if ( ! empty( $deadline_default[ $value ] ) ) {
					$deadline = ! empty( $deadline ) ? $deadline . ' OR ' . self::app_deadline( $deadline_default[ $value ] ) : self::app_deadline( $deadline_default[ $value ] );
				}
				/** Ethnicity */
				if ( ! empty( $ethnicity_default[ $value ] ) ) {
					$field     = $ethnicity_default[ $value ];
					$ethnicity .= empty( $ethnicity ) ? " `$field` = 'X' " : " OR `$field` = 'X' ";
				}
				/** Area of study */
				if ( in_array( $value, $area_of_study_default ) ) {
					$field = '';
					for ( $i = 1; $i <= 12; $i ++ ) {
						$field .= ! empty( $field ) ? " OR `m_stud$i` = '$value'" : "`m_stud$i` = '$value'";
					}
					$area_of_study .= empty( $area_of_study ) ? $field : " OR $field";
				}
				/** Type of study */
				if ( ! empty( $type_of_study_default[ $value ] ) ) {
					$field         = $type_of_study_default[ $value ];
					$type_of_study = empty( $type_of_study ) ? " `$field` = 'X' " : "";
				}
			}
			$state         = ! empty( $state ) ? " ($state) AND " : '';
			$amount        = ! empty( $amount ) ? "($amount) AND " : '';
			$gender        = ! empty( $gender ) ? "($gender) AND " : '';
			$disability    = ! empty( $disability ) ? "($disability) AND " : '';
			$financial     = ! empty( $financial ) ? "($financial) AND " : '';
			$deadline      = ! empty( $deadline ) ? "($deadline) AND " : '';
			$ethnicity     = ! empty( $ethnicity ) ? "($ethnicity) AND " : '';
			$area_of_study = ! empty( $area_of_study ) ? "($area_of_study) AND " : '';
			$type_of_study = ! empty( $type_of_study ) ? "($type_of_study) AND " : '';
		}

		/**  Type student */
		$select = !empty($request->select) ? $request->select : '';
		$input = !empty($request->input) ? $request->input : '';
		$tsq    = !empty($type_student[ $select ]) ? $type_student[ $select ] . " = 'X' AND " : '';
		$order_by = !empty($request->load_more) ? "ORDER BY id ASC LIMIT $request->load_more, 5" : "ORDER BY id ASC LIMIT 0, 5";

		if ( is_integer( $request ) ) {
			$res = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $request", OBJECT );
		} else {
			$search  = !empty($input) ? str_replace( [ '%20', ' ' ], '_', trim( $input ) ) . '%' : '';
			$res_req = implode( ' OR ', array_map( fn( $h ) => "`$h` like '$search'", $default_columns ) );
			$res_req = $tsq . $amount . $state . $gender . $disability . $financial . $deadline . $ethnicity . $area_of_study . $type_of_study . '(' . $res_req . ')';
			$res['query']     = $wpdb->get_results( "SELECT * FROM $table_name WHERE $res_req $order_by", OBJECT );
			$res['count']     = $wpdb->get_results( "SELECT COUNT(*) as count FROM $table_name WHERE $res_req ", OBJECT );
		}

		return $res;
	}

	public static function app_deadline( $req ): string {

		$current_month      = self::del_null( date( 'm' ) );
		$current_day        = self::del_null( date( 'd' ) );
		$current_month_days = self::got_days_count();
		$apdlmo_month       = self::del_null( date( 'm', strtotime( '+ ' . $req ) ) );
		$apdlda_day         = self::del_null( date( 'd', strtotime( '+ ' . $req ) ) );
		$apdlmo_days        = self::got_days_count( $req );
		$sql                = '';

		if ( $current_month !== $apdlmo_month || $req === '3more' ) {
			if ( $req === '2weeks' ) {
				$sql = "`apdlmo` = $current_month AND `apdlda` BETWEEN $current_day AND $current_month_days ";
				$sql = "($sql) OR (`apdlmo` = $apdlmo_month AND `apdlda` BETWEEN 1 AND $apdlda_day )";
			}
			if ( $req === '1month' ) {
				$sql = "`apdlmo` = $current_month AND `apdlda` BETWEEN $current_day AND $current_month_days ";
				$sql = "($sql) OR (`apdlmo` = $apdlmo_month AND `apdlda` BETWEEN 1 AND $apdlda_day )";
			}
			if ( $req === '3months' ) {

				$sql = "(`apdlmo` = $current_month AND `apdlda` BETWEEN $current_day AND $current_month_days )";
				for ( $i = 1; $i <= 3; $i ++ ) {

					$month    = self::del_null( date( 'm', strtotime( "+ {$i}month" ) ) );
					$days     = self::got_days_count( "+ $i'month'" );
					$last_day = $month === $apdlmo_month ? $apdlda_day : $days;

					$sql = "$sql OR (`apdlmo` = $month AND `apdlda` BETWEEN 1 AND $last_day )";
				}
			}
			if ( $req === '3more' ) {
				$apdlmo_month = self::del_null( date( 'm', strtotime( '3months' ) ) );
				$apdlda_day   = self::del_null( date( 'd', strtotime( '3months' ) ) );
				$apdlmo_days  = self::got_days_count( '3months' );
				$sql          = "(`apdlmo` = $apdlmo_month AND `apdlda` BETWEEN $apdlda_day AND $apdlmo_days ) OR (`apdlmo` > $apdlmo_month)";
			}
		} else {
			$sql = "`apdlmo` = $current_month AND `apdlda` BETWEEN $current_day AND $apdlda_day ";
		}

		return $sql;
	}

	public static function del_null( $number ) {
		$res = stripos( $number, '0' );
		if ( $res === 0 ) {
			$number = substr( $number, 1 );
		}

		return $number;
	}

	public static function got_days_count( $req = '' ) {
		$dat = date( 'Y-m-d', strtotime( $req ) );
		$d   = new DateTime( $dat );

		return $d->format( 't' );
	}


	public static function rewrite_rule_for_filter() {
		add_rewrite_rule( '^scholarship/(\d+)/?$', 'index.php?pagename=filter', 'top' );
	}


	public static function create_app_path() {
		$vendors_js = glob( WLD_Theme::$child_path . 'src/app-vue/dist/js/chunk-vendors.*.js' )[0] ?? '';
		$vue_app_js = glob( WLD_Theme::$child_path . 'src/app-vue/dist/js/app.*.js' )[0] ?? '';
		if ( $vendors_js && $vue_app_js ) {
			$vendors_js = str_replace(
				wp_normalize_path( get_stylesheet_directory() ),
				get_stylesheet_directory_uri(),
				wp_normalize_path( $vendors_js )
			);
			$vue_app_js = str_replace(
				wp_normalize_path( get_stylesheet_directory() ),
				get_stylesheet_directory_uri(),
				wp_normalize_path( $vue_app_js )
			);
			wp_register_script(
				'app-vue-vendors',
				$vendors_js,
				array(),
				1,
				true
			);
			wp_enqueue_script(
				'app-vue',
				$vue_app_js,
				array( 'app-vue-vendors' ),
				1,
				true
			);
		}
	}

}
