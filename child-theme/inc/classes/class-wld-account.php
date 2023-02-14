<?php

class WLD_Account {
	protected const NAMESPACE = 'wld-filter/v1/';

	public static function init(): void {
		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);
	}

	public static function registration_rest_routes() {
		register_rest_route( self::NAMESPACE, 'auth/(?P<id>\d+)',
			array(
				'methods'  => 'POST',
				'callback' => array( self::class, 'my_awesome_func' ),
			),
		);
	}

	public static function my_awesome_func() {

		$res = '';
		switch ( $_POST['action'] ) {
			case 'update':
				require_once( ABSPATH . 'wp-admin/includes/user.php' );
				$userdata = [
					'ID'         => get_current_user_id(),
					'user_pass'  => $_POST['user_password'],
					'user_login' => $_POST['user_login'],
					'user_email' => $_POST['email'],
					'last_name'  => $_POST['last_name'],
				];
				$res      = wp_update_user( $userdata );

				return $res;
				break;
			case 'delete':
				require_once( ABSPATH . 'wp-admin/includes/user.php' );
				$user = wp_get_current_user();

				return wp_delete_user( $user->data->ID );
				break;
			case 'auth':
				return wp_get_current_user();
				break;
			case 'sign_on':
				$res = wp_signon( [
					'user_login'    => $_POST['user_login'],
					'user_password' => $_POST['user_password'],
					'remember'      => $_POST['remember']
				] );

				wp_set_current_user( $res->data->ID, $res->user_login );
				if ( ! is_wp_error( $res ) ) {
					$id            = array(
						'nonce' => esc_js( wp_create_nonce( 'wp_rest' ) ),
						'ID'    => $res->data->ID,
					);
					$res->data->ID = $id;
				}
				break;
			case 'reg':
				$res = register_new_user( $_POST['user_login'], $_POST['email'] );
				if ( ! is_wp_error( $res ) ) {
					update_user_meta( $res, 'auth_info', $_POST );
					$res = (object) array(
						'data' => (object) array(
							'ID' => $res,
						),
					);
				};
				break;
			case 'logout':
				wp_logout();

				return 1;
				break;
			case 'forgot':
				$res = retrieve_password( $_POST['user_login'] );
				break;
			case 'wishlist':

				$current_user = wp_get_current_user();
				$user         = get_user_meta( $current_user->ID ) ? get_user_meta( $current_user->ID ) : '';
				if ( $current_user->exists() ) {

					if ( ! empty( $user['wishlist'] ) && $_POST['wishlist'] ) {
						$wishlist = $user['wishlist'];
						$wishlist = unserialize( $wishlist[0] ) ? unserialize( $wishlist[0] ) : $wishlist;
						$key      = array_search( $_POST['wishlist'], $wishlist );

						if ( $key === 0 || $key ) {
							unset( $wishlist[ $key ] );
							if ( empty( $wishlist ) ) {
								return delete_user_meta( $current_user->ID, 'wishlist', );
							};
						} else {
							array_push( $wishlist, $_POST['wishlist'] );
						}

						return update_user_meta( $current_user->ID, 'wishlist', $wishlist );

					}
					if ( empty( $user['wishlist'] ) && $_POST['wishlist'] ) {
						return update_user_meta( $current_user->ID, 'wishlist', $_POST['wishlist'] );
					}
				}

				return unserialize( $user['wishlist'][0] ) ? unserialize( $user['wishlist'][0] ) : $user['wishlist'];
				break;
			case 'account':
				global $wpdb;
				$current_user = wp_get_current_user();
				$user         = get_user_meta( $current_user->ID );
				$wishlist     = unserialize( $user['wishlist'][0] ) ? unserialize( $user['wishlist'][0] ) : $user['wishlist'];
				$wishlist     = implode( ' OR ', array_map( fn( $h ) => "`id` = $h", $wishlist ) );

				return $wpdb->get_results( "SELECT * FROM wld_import_table WHERE $wishlist" );
				break;
			case 'savelist':
				global $wpdb;
				$current_user = wp_get_current_user();
				$user         = get_user_meta( $current_user->ID );

				return unserialize( $user['wishlist'][0] ) ? unserialize( $user['wishlist'][0] ) : $user['wishlist'];
		}

		return is_wp_error( $res ) ? '<span style="color: red">' . $res->get_error_message() . '</span>' : $res->data;


	}

}
