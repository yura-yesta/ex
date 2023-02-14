<?php echo '</main>'; ?>
<?php get_template_part('template-parts/popup'); ?>
<div id="popup_menu"></div>
<div id="vue_popup"></div>
<footer id="page-footer" xmlns="http://www.w3.org/1999/html">
	<div class="top">
		<div class="inner">
			<div class="left">
				<div class="logo">
					<?php theme_the_logo('theme_footer_logo', '300x0'); ?>
				</div>
				<?php theme_the_nav('Footer Main'); ?>
				<?php theme_the('theme_footer_social_links'); ?>
			</div>
			<div class="right">
				<?php theme_the('theme_footer_title'); ?>
				<?php theme_the('theme_footer_text'); ?>
				<?php theme_the('theme_footer_form', '<div class="form-subscribe">%s</div>'); ?>
			</div>
		</div>
	</div>
	<div class="bottom">
		<div class="inner">
			<div class="links">
				<?php theme_the('theme_footer_copyright'); ?>
				<?php theme_the_nav('Footer Links'); ?>
			</div>
			<?php theme_the_by(); ?>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/cookie-notice' ); ?>
