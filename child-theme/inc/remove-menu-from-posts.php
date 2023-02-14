<?php
if ( isset( $_GET['remove-menu-from-posts'] ) && is_admin() ) {
	add_action(
		'acf/init',
		static function () {
			$blog_posts = get_posts(
				array(
					'numberposts' => - 1,
					'post_status' => 'any',
				)
			);

			foreach ( $blog_posts as $blog_post ) {
				$blocks = get_field( 'content', $blog_post, false );
				foreach ( $blocks as $i => $block ) {
					if ( 'menu' === $block['acf_fc_layout'] ) {
						unset( $blocks[ $i ] );
					}
				}

				update_field( 'content', array_values( $blocks ), $blog_post->ID );
			}

			WLD_ACF_Flex_Content::rebuild_flex_content();

			exit( 'Done' );
		}
	);
}
