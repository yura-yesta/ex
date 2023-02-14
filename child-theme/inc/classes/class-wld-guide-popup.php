<?php

class WLD_Guide_Popup {

	public static function init() {

		add_action(
			'template_redirect',
			array( self::class, 'redirect_customize_page' )
		);
		add_action(
			'wp_footer',
			array( self::class, 'display_guide_popup' ), 999
		);
		add_filter(
			'gform_confirmation',
			array( self::class, 'add_cookies_before_confirmation' ),
			10, 4
		);
	}

	public static function redirect_customize_page() {
		global $post;


		$cookie_name = 'check_email_access_5';
		if ( empty( $post ) ) {
			return;
		}
		$post_type = get_post_type( $post->ID );
		if ( 'guide' === $post_type && 'restricted' === theme_get( 'type_guide' ) && empty( $_COOKIE[ $cookie_name ] ) ) {
			?>
			<script>
				if (document.cookie.indexOf('check_email_access_5=1') === -1) window.location.replace("/");
			</script>
			<?php

		}
	}

	public static function display_guide_popup() {

		$cookie_name = 'check_email_access_5';
		$secure      = is_ssl() ? ' ;secure' : '';

		//if (empty($_COOKIE[$cookie_name])) :
		?>
		<script type="text/javascript" id="check-email-access">

			let current_url;

			if (document.querySelectorAll('.menu-guide-popup') && (document.cookie.indexOf('check_email_access_5=1') === -1)) {

				let guide = document.querySelectorAll('.menu-guide-popup')
				const div = document.getElementById('sharing-your-grade');
				for (let elem of guide) {
					elem.addEventListener('click', (e) => {

						if (e.target.text) {
							let str = e.target.text
							str = str.toUpperCase()
							let index = e.target.text.indexOf(' ')
							str = str.substr(0, index) + '<br>' + str.substr(index);

							document.getElementById('guide_content').innerHTML = '<h2 class="title">GET THE ' + str + '</h2>'
						}

						if (e.target.parentElement.classList.contains('menu-guide-popup') && e.target.href !== undefined) {
							e.preventDefault()
							document.getElementById('sharing').classList.remove('hidden-popup')
							current_url = e.target.href
						}
					})
				}

				document.addEventListener('click', (e) => {
					let withinBoundaries = true;
					if (div) {
						withinBoundaries = e.composedPath().includes(div);
					}


					let inc = false;
					for (let i = 0; i < guide.length; i++) {
						inc = e.composedPath().includes(guide[i]) ? true : inc;
					}
					if (!inc && !withinBoundaries) {
						document.getElementById('sharing').classList.add('hidden-popup')
					}
				})
			}
		</script>
		<?php //endif;
		?>
		<script>
			setTimeout(function tick() {
				jQuery(document).on('gform_confirmation_loaded', function (event, formId) {

					if ('sharing-your-grade' === document.getElementById('gform_fields_' + formId).offsetParent.id) {
						setTimeout(function tick() {
							document.getElementById('sharing').classList.add('hidden-popup')
							window.location.href = current_url
						}, 2400);
					}
					setTimeout(function run() {
						console.log('magnific')
						$.magnificPopup.close();
					}, 3000);
				});
			}, 3000);
		</script>
		<?php

	}

	public static function add_cookies_before_confirmation( $confirmation, $form, $entry, $ajax ) {
		$secure = is_ssl() ? ' ;secure' : '';
		?>
		<script>
			let id = '<?php echo $form['id']; ?>'
			document.cookie = 'check_email_access_' + id + '=1; path=/; max-age=31536000;<?php echo $secure; ?>';
		</script>
		<?php
		return $confirmation;
	}
}
