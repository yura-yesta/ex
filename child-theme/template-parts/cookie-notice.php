<?php if ( empty( $_COOKIE['cookie-notice'] ) && theme_has( 'theme_cookies_notice_text' ) ) : ?>
	<div id="cookie-notice">
		<div class="inner">
			<div class="cookie-notice-text">
				<?php theme_the( 'theme_cookies_notice_text' ); ?>
			</div>
			<div class="cookie-notice-btn">
				<button id="accept-cookie" class="btn">
					<?php esc_html_e( 'Accept', 'parent-theme' ); ?>
				</button>
			</div>
			<button id="cookie-notice-close" title="<?php esc_attr_e( 'Close', 'parent-theme' ); ?>"></button>
		</div>
	</div>
<?php endif; ?>
