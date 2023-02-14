<section class="section-get-info">
	<?php while ( theme_loop( 'items', '<div class="inner">' ) ) : ?>
		<div class="wrapper">
			<?php $type = theme_get( 'type', false ); ?>
			<?php if ( $type == 'left' ) : ?>
				<?php theme_the( 'image', '570x0', '<div class="image">' ); ?>
			<?php endif; ?>
			<div class="text">
				<?php theme_the( 'title', 'title' ); ?>
				<?php theme_the( 'subtitle' ); ?>
				<?php theme_the( 'text' ); ?>
				<?php theme_the( 'button', 'btn', '<p class="menu-guide-popup">' ); ?>
			</div>
			<?php if ( $type == 'right' ): ?>
				<?php theme_the( 'image', '570x0', '<div class="image">' ); ?>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
</section>
