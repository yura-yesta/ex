<div class="blog-title section-blog-posts">
	<?php the_title('<h1>','</h1>'); ?>
</div>
<section class="section-blog-posts<?php theme_the( 'variations' ); ?>">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Posts', 'theme' ); ?></h2>
	<?php WLD_Fake_Queries::set_original_query(); ?>
	<?php if ( have_posts() ) : ?>
		<ul class="wrapper">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<li<?php echo is_home() && is_sticky() ? ' class="sticky-post"' : ''; ?>>
					<article<?php echo has_post_thumbnail() ? ' class="has-thumbnail"' : ''; ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="thumbnail" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( '569x548' ); ?>
							</a>
						<?php endif; ?>
						<div class="text">
							<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="date">
								<?php
								printf( // translators: %s: posted date for archive entry
									esc_html__( 'Posted - %s', 'theme' ),
									get_the_date( 'F j, Y' )
								);
								?>
							</p>
							<p class="except">
								<?php theme_the_excerpt( 150, false, true, '...' ); ?>
							</p>
							<p class="link">
								<a href="<?php the_permalink(); ?>">
									<?php esc_html_e( 'Read more', 'theme' ); ?>
									<span class="screen-reader-text">
										<?php
										printf( // translators: %s: post title for archive entry read more link
											esc_html__( 'post "%s"', 'theme' ),
											esc_html( get_the_title() )
										);
										?>
									</span>
								</a>
							</p>
							<?php if ( has_category() ) : ?>
								<aside class="categories">
									<h4 class="screen-reader-text">
										<?php
										printf( // translators: %s: post title for archive entry categories info
											esc_html__(
												'The post "%s" is contained in the following categories:',
												'theme'
											),
											esc_html( get_the_title() )
										);
										?>
									</h4>
									<?php the_category( ' ' ); ?>
								</aside>
							<?php endif; ?>
						</div>
					</article>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<div class="wrapper not-found">
			<p><?php esc_html_e( 'Nothing found', 'theme' ); ?></p>
		</div>
	<?php endif; ?>
	<nav class="pagination">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'theme' ); ?></h2>
		<?php theme_the_pagination(); ?>
	</nav>
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
	<?php WLD_Fake_Queries::set_last_query(); ?>
</section>


