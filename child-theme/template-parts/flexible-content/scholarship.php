<?php
$all_posts = get_posts(
	array(
		'post_type' => 'guide',
		'exclude'   => get_the_ID(),
	)
);
?>
<section class="section-scholarship">
	<div class="inner">
		<div class="wrapper">
			<div class="left">
				<?php theme_the( 'main-title', 'title' ); ?>
				<div class="img">
					<?php the_post_thumbnail( '720x450' ); ?>
				</div>
				<?php theme_the( 'title' ); ?>
				<?php theme_the( 'scholarship' ); ?>
				<?php theme_the( 'button', 'btn', '<p>' ); ?>
			</div>
			<div class="right">
				<?php while ( theme_loop( $all_posts ) ) : ?>
					<a href="<?php the_permalink(); ?>" class="item">
						<div class="img">
							<?php the_post_thumbnail( $post->ID, array( 330, 180 ) ); ?>
						</div>
						<?php theme_the( 'scholarship:main-title', 'title' ); ?>
					</a>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</section>
