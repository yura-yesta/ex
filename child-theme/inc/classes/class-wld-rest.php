<?php

class WLD_Rest {
	protected const NAMESPACE = 'wld-rest/v1';

	public static function init(): void {
		add_action(
			'rest_api_init',
			array( static::class, 'registration_rest_routes' )
		);

		add_filter(
			'template_include',
			array( static::class, 'output' )
		);
	}

	public static function registration_rest_routes(): void {
		register_rest_route(
			static::NAMESPACE,
			'login',
			array(
				'methods'             => 'GET',
				'callback'            => array( static::class, 'login' ),
				'permission_callback' => static function () {
					return ! is_user_logged_in();
				},
				'args'                => array(
					'login'    => array(
						'type'     => 'string',
						'required' => true,
					),
					'password' => array(
						'type'     => 'string',
						'required' => true,
					),
				),
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'get',
			array(
				'methods'  => 'GET',
				'callback' => array( static::class, 'get' ),
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'check',
			array(
				'methods'  => 'GET',
				'callback' => array( static::class, 'check' ),
			)
		);
	}

	public static function login( WP_REST_Request $request ): WP_REST_Response {
		return new WP_REST_Response( wp_signon( $request ) );
	}

	public static function get(): WP_REST_Response {
		return new WP_REST_Response( wp_get_current_user() );
	}

	public static function check(): WP_REST_Response {
		return new WP_REST_Response( is_user_logged_in() );
	}

	public static function output( $template ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['wld-rest'] ) ) {
			return $template;
		}
		?>
		<button data-get>Get</button>
		<button data-check>Check</button>
		<script>
			const restRoot = '<?php echo esc_url_raw( rest_url() ); ?>';
			const restNonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';
			const restNamespace = '<?php echo esc_js( static::NAMESPACE ); ?>';

			function errorMessage(results) {
				let error = '';

				// eslint-disable-next-line no-console
				console.error(results);

				if (results.errors) {
					for (const code in results.errors) {
						error += results.errors[code] + '\r\n';
					}
				} else {
					error = results.message;
				}

				// eslint-disable-next-line no-alert
				alert(error);
			}

			async function request(endpoint) {
				let resource = restRoot + restNamespace + '/' + endpoint + '/';

				const headers = {
					'X-WP-Nonce': restNonce,
				};

				const response = await fetch(resource, {method: 'GET', headers});

				let results;

				try {
					results = await response.json();
				} catch (error) {
					// eslint-disable-next-line no-console
					console.error(error);

					// eslint-disable-next-line no-alert
					alert('An error has occurred. Try restarting performing an action again.');

					return [];
				}

				if (!response.ok) {
					errorMessage(results);

					return [];
				}

				if (typeof results === 'object' && results !== null && (results.errors || results.error)) {
					errorMessage(results);

					return [];
				}

				if (results === null) {
					return [];
				}

				return results;
			}

			document.querySelector('[data-get]').addEventListener('click', function () {
				request('get').then((results) => console.info('get', results));
			});
			document.querySelector('[data-check]').addEventListener('click', function () {
				request('check').then((results) => console.info('check', results));
			});
		</script>
		<?php
		exit();
	}
}
