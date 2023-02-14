<section class="section-blog-post<?php theme_the( 'variations' ); ?>">
	<header>
		<!--		<div class="text">-->
		<!--			--><?php //if ( 'about' === theme_get( 'type' ) ) : ?>
		<!--				--><?php //the_title( '<h1>', '</h1>' ); ?>
		<!--			--><?php //else : ?>
		<!--				<p class="date">-->
		<!--					--><?php
		//					printf( // translators: %s: posted date for archive entry
		//						esc_html__( 'Posted - %s', 'theme' ),
		//						get_the_date( 'F j, Y' )
		//					);
		//					?>
		<!--				</p>-->
		<!--				--><?php //the_title( '<h1>', '</h1>' ); ?>
		<!--				--><?php //if ( has_category() ) : ?>
		<!--					<aside class="categories">-->
		<!--						<h4 class="screen-reader-text">-->
		<!--							--><?php
		//							printf( // translators: %s: post title for archive entry categories info
		//								esc_html__(
		//									'The post "%s" is contained in the following categories:',
		//									'theme'
		//								),
		//								esc_html( get_the_title() )
		//							);
		//							?>
		<!--						</h4>-->
		<!--						--><?php //the_category( ' ' ); ?>
		<!--					</aside>-->
		<!--				--><?php //endif; ?>
		<!--			--><?php //endif; ?>
		<!--		</div>-->
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="thumbnail">
				<?php the_post_thumbnail( '0x548', array( 'alt' => get_the_title() ) ); ?>
			</div>
		<?php endif; ?>
	</header>
	<div class="text">
		<?php theme_the( 'blog_post' ); ?>
	</div>
	<?php
	get_template_part(
		'template-parts/share-links',
		null,
		array( 'title' => esc_html__( 'Don’t forget to share this post!', 'theme' ) )
	);
	?>
	<?php if ( is_active_sidebar( 'blog_sidebar' ) ) : ?>
		<aside class="sidebar">
			<h2 class="screen-reader-text">
				<?php esc_html_e( 'Posts widgets', 'theme' ); ?>
			</h2>
			<ul>
				<?php dynamic_sidebar( 'blog_sidebar' ); ?>
			</ul>
		</aside>
	<?php endif; ?>
</section>
