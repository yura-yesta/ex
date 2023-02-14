<?php


class WLD_Sticky_Posts_In_Category {
	public static function init(): void {
		add_filter(
			'the_posts',
			array( static::class, 'set' ),
			10,
			2
		);
	}

	public static function set( array $posts, WP_Query $wp_query ): array {
		if ( $wp_query->is_main_query() && $wp_query->is_archive() ) {
			$paged    = (int) $wp_query->get( 'paged' );
			$category = (int) $wp_query->get( 'cat' );
			if ( 0 === $paged && $category ) {
				$sticky_posts = static::get( $category );
				if ( $sticky_posts ) {
					foreach ( $sticky_posts as $sticky_post ) {
						foreach ( $posts as $i => $one_posts ) {
							if ( $one_posts->ID === $sticky_post->ID ) {
								unset( $posts[ $i ] );
							}
						}
					}
					$posts = array_merge( $sticky_posts, $posts );
				}
			}

		}

		if ( ! empty( $wp_query->query['pagename'] ) && 'blog' === $wp_query->query['pagename'] ) {

			$custom_posts = [];
			foreach ( $posts as $key => $post ) {
				if ( 'about-us' !== $post->post_name ) {
					$custom_posts[] = $post;
				}
			}

			$posts = $custom_posts;
		}

		return $posts;
	}

	protected static function get( int $category ): array {
		return get_posts(
			array(
				'category'            => $category,
				'include'             => get_option( 'sticky_posts' ),
				'ignore_sticky_posts' => 0,
			)
		);
	}

	public static function breadcrumbs(){

		global $post;

		if(!empty($post->post_name) && 'about-us' === $post->post_name) {
			?>
			<div class="section-breadcrumb">
				<div class="breadcrumb">
				<span>
					<span>
						<a href="/">Home</a>
						<span class="breadcrumb_last" aria-current="page">About Us</span>
					</span>
				</span>
				</div>
			</div>
			<?php
		}else{
			WLD_ACF_Flex_Content::the_block( 'breadcrumb' );
		}
	}
}
