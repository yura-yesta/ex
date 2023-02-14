<section class="section-latest-blog">
	<div class="inner">
		<?php theme_the( 'title', 'title' ); ?>
		<?php while ( theme_loop( 'items', '<div class="wrapper">' ) ) : ?>
			<a class="item" href="<?php the_permalink(); ?>">
				<?php
				the_post_thumbnail(
					1 === get_row_index() ? '576x0' : '493x0',
					array( 'class' => 'object-fit object-fit-cover' )
				);
				?>
				<div class="date"><?php the_date(); ?></div>
				<h3 class="title"><?php the_title(); ?></h3>
			</a>
		<?php endwhile; ?>
	</div>
</section>
