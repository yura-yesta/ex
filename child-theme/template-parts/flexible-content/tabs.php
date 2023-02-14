<section class="section-info-tabs tabs">
	<div class="inner">
		<?php while ( theme_loop( 'nav_items', '<div class="tabs-nav">' ) ) : ?>
			<button><?php theme_the( 'nav_text' ); ?></button>
		<?php endwhile; ?>
		<?php while ( theme_loop( 'tabs_items' ) ) : ?>
			<div class="tab">
				<?php while ( theme_loop( 'items', '<div class="item">' ) ) : ?>
					<?php $type = theme_get( 'type' ); ?>
					<?php if ( 'text' == $type ) : ?>
						<?php theme_the( 'text' ); ?>
					<?php elseif ( 'checkbox' == $type ) : ?>
						<?php theme_the( 'title' ); ?>
						<?php while ( theme_loop( 'check_items', '<div class="checkbox">' ) ) : ?>
							<?php theme_the( 'checkbox' ); ?>
						<?php endwhile; ?>
					<?php elseif ( 'text_checkbox' ): ?>
						<?php theme_the( 'tc_title' ); ?>
						<ul>
							<li>
								<?php theme_the( 'first_subtitle' ); ?>
								<?php theme_the( 'tc_checkbox' ); ?>
								<?php theme_the( 'tc_text' ); ?>
							</li>
							<li>
								<?php theme_the( 'second_subtitle' ); ?>
								<?php theme_the( 'tc_phone_mail_text' ); ?>
							</li>
						</ul>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>
