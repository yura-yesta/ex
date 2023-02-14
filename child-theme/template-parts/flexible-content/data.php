<section class="section-data">
	<div class="inner">
		<?php while ( theme_loop( 'items', '<div class="wrapper">' ) ) : ?>
			<?php $type = theme_get( 'type' ); ?>
			<div class="item <?php echo $type; ?>">
				<?php if ( $type == 'image' ) : ?>
					<div>
						<?php theme_the( 'title' ); ?>
						<?php theme_the( 'text' ); ?>
					</div>
					<?php theme_the( 'image', '40x0', '<div class="img">' ); ?>
				<?php else: ?>
					<?php theme_the( 'title' ); ?>
					<?php theme_the( 'text' ); ?>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
