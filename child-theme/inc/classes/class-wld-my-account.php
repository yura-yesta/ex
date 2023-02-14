<?php


class WLD_My_Account {

	protected const NAMESPACE = 'wld-filter/v1/';

	public static function init() {
		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);
		add_filter(
			'login_message',
			array( self::class, 'change_message' )
		);
		add_filter(
			'wp_login_errors',
			array( self::class, 'filter_function_name_3928' ),
			10, 2
		);
		add_filter(
			'login_message',
			array( self::class, 'change_message' )
		);
		add_filter(
			'wp_login_errors',
			array( self::class, 'filter_function_name_3928' ),
			10, 2
		);
	}

	public static function registration_rest_routes() {
		register_rest_route( self::NAMESPACE, 'account',
			array(
				'methods'  => 'POST',
				'callback' => array( self::class, 'account' ),
			),
		);

		register_rest_route( self::NAMESPACE, 'wishlist',
			array(
				'methods'  => 'POST',
				'callback' => array( self::class, 'wishlist' ),
			),
		);
	}

	public static function account() {
		switch ( $_POST['action'] ) {
			case 'update':
				require_once( ABSPATH . 'wp-admin/includes/user.php' );
				$user = wp_get_current_user();

				$res = self::wld_update_user_data_by_id( $user->data->ID, $_POST );

				break;
			case 'delete':
				require_once( ABSPATH . 'wp-admin/includes/user.php' );
				$user = wp_get_current_user();

				return wp_delete_user( $user->data->ID );
			case 'auth':
				return wp_get_current_user();
			case 'sign_on':

				$res = self::account_sign_on( $_POST['user_login'], $_POST['user_password'], $_POST['remember'] );

				break;
			case 'reg':
				$res = wp_create_user( $_POST['email'], $_POST['user_password'], $_POST['email'] );

				if ( ! is_wp_error( $res ) ) {

					self::send_mail( $_POST['email'] );
					self::wld_update_user_data_by_id( $res, $_POST );
					update_user_meta( $res, 'auth_info', $_POST );
					$res = self::account_sign_on( $_POST['email'], $_POST['user_password'], $_POST['remember'] );
				};

				break;
			case 'logout':
				wp_logout();

				return 1;
			case 'forgot':

				$res = retrieve_password( $_POST['user_login'] );
				if ( ! is_wp_error( $res ) ) {

					$res       = new stdClass();
					$res->data = '<span style="color: green">Please check your email</span>';
				}
				break;
			case 'content':
				$content = array(
					'login1' => theme_get( 'theme_popup_reg_1' ),
					'login2' => theme_get( 'theme_popup_reg_2' ),
				);

				return $content;
		}

		return is_wp_error( $res ) ? '<span style="color: red">' . $res->get_error_message() . '</span>' : $res->data;
	}

	public static function account_sign_on( $login, $pass, $remember ) {
		$res = wp_signon( [
			'user_login'    => $login,
			'user_password' => $pass,
			'remember'      => $remember
		] );

		wp_set_current_user( $res->data->ID, $res->user_login );
		if ( ! is_wp_error( $res ) ) {
			$id            = array(
				'nonce' => esc_js( wp_create_nonce( 'wp_rest' ) ),
				'ID'    => $res->data->ID,
			);
			$res->data->ID = $id;
		}

		return $res;
	}

	public static function wishlist() {
		switch ( $_POST['action'] ) {
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
			case 'account':
				global $wpdb;

				$table_name = $wpdb->get_blog_prefix() . 'import_table';
				$current_user = wp_get_current_user();
				$user         = get_user_meta( $current_user->ID );
				$wishlist     = unserialize( $user['wishlist'][0] ) ? unserialize( $user['wishlist'][0] ) : $user['wishlist'];
				$wishlist     = implode( ' OR ', array_map( fn( $h ) => "`id` = $h", $wishlist ) );

				return $wpdb->get_results( "SELECT * FROM $table_name WHERE $wishlist", OBJECT );
			case 'savelist':
				$current_user = wp_get_current_user();
				$user         = get_user_meta( $current_user->ID );

				return unserialize( $user['wishlist'][0] ) ? unserialize( $user['wishlist'][0] ) : $user['wishlist'];
		}

		return false;
	}

	public static function send_mail( $user_email ): void {

		$url     = str_replace( [ 'https://', 'http://' ], '', get_site_url( null, '', 'http' ) );
		$headers = array(
			'From: TheCollegePod <noreply@' . $url . '>',
			'content-type: text/html',
		);

		$message = '
<table style="max-width:600px; margin-top:60px;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tbody>
	<tr>
		<td style="max-width:600px" width="100%" valign="middle" align="center">
			<img style="display:block;border:none;outline:none;text-decoration:none;max-width:200px"
				 src="https://thecollegepod.devbucket.me/wp-content/uploads/2022/01/logo.png" alt="" title=""
				 class="CToWUd a6T" tabindex="0" width="100%" border="0">
			<img style="display:block;border:none;outline:none;text-decoration:none;max-width:600px;"
				 src="https://thecollegepod.devbucket.me/wp-content/themes/child-theme/src/images/image-mail-form.png" alt="" title=""
				 class="CToWUd a6T" tabindex="0" width="100%" border="0">
			<div style="opacity: 0.01; left: 811.6px; top: 369.65px;">
				<div role="button" tabindex="0">
					<div>
						<div></div>
					</div>
				</div>
			</div>
		</td>
	</tr>
	</tbody>
</table>
<table style="max-width:600px;background:#ffffff" width="100%" cellspacing="0" cellpadding="0" border="0"
	   align="center">
	<tbody>
	<tr>
		<td style="padding-bottom:5px;padding-top:5px" valign="middle" align="center">
			<table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tbody>
				<tr>
					<td style="font-size:15px;font-family:" open sans
					",Arial,Helvetica,sans-serif;text-align:left;line-height:26px" align="left">
					<p>
						Dear <strong>' . $user_email . '</strong>!
						Thank you for completing your registration with <strong>' . $url . '</strong>.
						This email serves as a confirmation that your account is <strong>activated</strong>.<br>
						Login : ' . $user_email . '
					</p>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
<table style="max-width:600px" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center">
	<tbody style="display: block">
	<tr style="display: block">
		<td style="padding-bottom:40px;padding-top:15px;display: block;" valign="middle" align="center">
			<table style="max-width:320px" cellspacing="0" cellpadding="0" border="0" align="center">
				<tbody>
				<tr>
					<td style="background-color:#ef454e;border-radius: 4px;font-size:17px;font-family:"open sans",Arial,Helvetica,sans-serif;text-align:center;color:#ffffff;font-weight:bold;padding-left:25px;padding-right:25px;padding-top:16px;padding-bottom:16px"
						width="320" valign="middle" align="center">
						<span style="color:#ffffff">
							<a style="color:#ffffff;text-align:center;text-decoration:none;font-weight:500;background: #ef454e;padding: 10px 15px;border-radius: 5px;" href="' . $url . '"
							name="m_-2974176907707732711_cta_top_ru" target="_blank">Learn more on our website</a>
						</span>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
        ';

		wp_mail( $user_email, 'TheCollegePod registration', $message, $headers );
	}

	public static function wld_update_user_data_by_id( $id, $data ) {
		if ( empty( $id ) ) {
			return false;
		}
		if ( ! empty( $data['user_email'] ) && ! filter_var( $data['user_email'],
				FILTER_VALIDATE_EMAIL ) ) {
			return new WP_Error( 'error_email', 'This email name used or written incorrectly !' );

		}
		if ( ! empty( $data['user_email'] ) && ( email_exists( $data['user_email'] ) || ! is_email( $data['user_email'] ) ) ) {
			return new WP_Error( 'error_email', 'This email name used or written incorrectly !' );
		}
		$userdata_fields = [ 'user_pass', 'user_email', 'first_name', 'last_name', 'description' ];
		$userdata['ID']  = $id;

		foreach ( $userdata_fields as $value ) {
			if ( ! empty( $data[ $value ] ) ) {
				$userdata[ $value ] = $data[ $value ];
			}
		}
		$res = wp_update_user( $userdata );
		if ( is_integer( $res ) ) {
			$object = [ 'data' => [ 'ID' => $res ] ];
			$res    = (object) $object;
		}

		return $res;
	}

	public static function change_message( $message ) {

		if ( ! empty( strripos( $message, 'Your password has been reset' ) ) ) {
			$message = '<p class="message reset-pass">'
			           . __( 'Your password has been reset.' )
			           . ' <a href="' . esc_url( '/?login-popup' )
			           . '">' . __( 'Log in' ) . '</a></p>';
		}

		return $message;
	}


	public static function filter_function_name_3928( $errors, $redirect_to ) {

		if ( ! empty( $errors->errors ) && ! empty( $errors->errors['confirm'][0] ) ) {
			$message                      = $errors->errors['confirm'][0];
			$errors->errors['confirm'][0] = str_replace( 'wp-login.php', '?login-popup', $message );
		}

		return $errors;
	}

}
